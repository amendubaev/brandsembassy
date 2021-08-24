<?php
/*
    Plugin Name: Платежный шлюз RBS
    Plugin URI:
    Description: Позволяет использовать платежный шлюз RBS с Инструментом электронной торговли WooCommerce.
    Version: 3.0.6
	Author: RBS
	Author URI: http://www.rbspayment.ru
 */

if (!defined('ABSPATH')) exit;
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(__DIR__ . '/include.php');

add_filter('plugin_row_meta', 'rbs_register_plugin_links', 10, 2);
function rbs_register_plugin_links($links, $file)
{
    $base = plugin_basename(__FILE__);
    if ($file == $base) {
        $links[] = '<a href="admin.php?page=wc-settings&tab=checkout&section=rbspayment">' . __('Settings', 'woocommerce') . '</a>';
    }
    return $links;
}


add_action('plugins_loaded', 'woocommerce_rbspayment', 0);
function woocommerce_rbspayment()
{
    if (!class_exists('WC_Payment_Gateway'))
        return;
    if (class_exists('WC_RBSPAYMENT'))
        return;

    class WC_RBSPAYMENT extends WC_Payment_Gateway
    {

        public function __construct()
        {

            $this->id = 'rbspayment';

            // Load the settings
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title = $this->get_option('title');
            $this->merchant = $this->get_option('merchant');
            $this->password = $this->get_option('password');
            $this->test_mode = $this->get_option('test_mode');
            $this->stage = $this->get_option('stage');
            $this->description = $this->get_option('description');
            $this->icon = plugin_dir_url(__FILE__) . 'logo.png';

            $this->send_order = $this->get_option('send_order');
            $this->tax_system = $this->get_option('tax_system');
            $this->tax_type = $this->get_option('tax_type');

            $this->pData = get_plugin_data(__FILE__);

            // Actions
            add_action('valid-rbspayment-standard-ipn-reques', array($this, 'successful_request'));
            add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));

            // Save options
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            // filters
//            add_filter('woocommerce_order_button_text', 'woo_custom_order_button_text');
//            function woo_custom_order_button_text()
//            {
//                return __('Перейти к оплате', 'woocommerce');
//            }

            if (!$this->is_valid_for_use()) {
                $this->enabled = false;
            }

            $this->callb();
        }


        public function callb()
        {

            if (isset($_GET['rbspayment']) AND $_GET['rbspayment'] == 'result') {
                if ($this->test_mode == 'yes') {
                    $action_adr = API_TEST_URL;
                } else {
                    $action_adr = API_PROD_URL;
                }

                $action_adr .= 'getOrderStatusExtended.do';

                $args = array(
                    'userName' => $this->merchant,
                    'password' => $this->password,
                    'orderId' => $_GET['orderId'],
                );

                $rbsCurl = curl_init();
                curl_setopt_array($rbsCurl, array(
                    CURLOPT_URL => $action_adr,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_POSTFIELDS => http_build_query($args, '', '&'),
                    CURLOPT_HTTPHEADER => array(
                        'CMS: Wordpress ' . get_bloginfo('version') . " + woocommerce version: " . wpbo_get_woo_version_number(),
                        'Module-Version: ' . $this->pData['Version']
                    ),
                ));

                $response = curl_exec($rbsCurl);
                curl_close($rbsCurl);

                if (LOGGING) {
                    $this->rbs_logger('Request: ' . $action_adr . ': ' . print_r($args, true) . 'Response: ' . $response);
                }
                $response = json_decode($response, true);

                $orderStatus = $response['orderStatus'];
                if ($orderStatus == '1' || $orderStatus == '2') {
                    $order_id = $_GET['order_id'];
                    $order = new WC_Order($order_id);
                    $order->update_status('processing', __('Платеж успешно оплачен', 'woocommerce'));

                    try {
                        $order->reduce_order_stock();
                    } catch (Exception $e) {
                        //noop
                    }

                    $order->payment_complete();
                    wp_redirect($this->get_return_url($order));

                    exit;
                } else {
                    $order_id = $_GET['order_id'];
                    $order = new WC_Order($order_id);
                    $order->update_status('failed', __('Платеж не оплачен', 'woocommerce'));
                    add_filter('woocommerce_add_to_cart_message', 'my_cart_messages', 99);
                    $order->cancel_order();

                    wc_add_notice(__('Ошибка в проведении оплаты<br/>' . $response['actionCodeDescription'], 'woocommerce'), 'error');
                    wp_redirect($order->get_cancel_order_url());
                    exit;
                }
            }
        }


        /**
         * Check if this gateway is enabled and available in the user's country
         */
        function is_valid_for_use()
        {
            if (!in_array(get_option('woocommerce_currency'), array('RUB'))) {
                return false;
            }
            return true;
        }

        /*
         * Admin Panel Options
         */
        public function admin_options()
        {
            ?>
            <h3><?php _e(RBSPAYMENT_TITLE_1, 'woocommerce'); ?></h3>
            <p><?php _e(RBSPAYMENT_TITLE_2, 'woocommerce'); ?></p>

            <?php if ($this->is_valid_for_use()) : ?>

            <table class="form-table">

                <?php
                // Generate the HTML For the settings form.
                $this->generate_settings_html();
                ?>
            </table>

        <?php else : ?>
            <div class="inline error"><p>
                    <strong><?php _e('Шлюз отключен', 'woocommerce'); ?></strong>: <?php _e($this->id . ' не поддерживает валюты Вашего магазина.', 'woocommerce'); ?>
                </p></div>
            <?php
        endif;

        }

        /*
         * Initialise Gateway Settings Form Fields
         */
        function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Включить/Выключить', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Включен', 'woocommerce'),
                    'default' => 'yes'
                ),
                'test_mode' => array(
                    'title' => __('Тест режим', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Включен', 'woocommerce'),
                    'description' => __('В этом режиме плата за товар не снимается.', 'woocommerce'),
                    'default' => 'no'
                ),

                'title' => array(
                    'title' => __('Название', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Заголовок, который видит пользователь в процессе оформления заказа.', 'woocommerce'),
                    'default' => __(RBSPAYMENT_NAME, 'woocommerce'),
                    'desc_tip' => true,
                ),
                'merchant' => array(
                    'title' => __('Логин', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Пожалуйста введите Логин', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'password' => array(
                    'title' => __('Пароль', 'woocommerce'),
                    'type' => 'password',
                    'description' => __('Пожалуйста введите пароль.', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'stage' => array(
                    'title' => __('Стадийность платежей', 'woocommerce'),
                    'type' => 'select',
                    'default' => 'one-stage',
                    'options' => array(
                        'one-stage' => __('Одностадийные платежи', 'woocommerce'),
                        'two-stage' => __('Двухстадийные платежи', 'woocommerce'),
                    ),
                ),

                'description' => array(
                    'title' => __('Description', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Описание метода оплаты которое клиент будет видеть на Вашем сайте.', 'woocommerce'),
                    'default' => 'Оплата с помощью ' . RBSPAYMENT_NAME
                ),

                'send_order' => array(
                    'title' => __('Передача корзины товаров', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Включена', 'woocommerce'),
                    'description' => __('При выборе опции, будет сформирован и отправлен в налоговую и клиенту чек. Опция платная, за подключением обратитесь в сервисную службу банка. При использовании необходимо настроить НДС продаваемых товаров. НДС рассчитывается согласно законодательству РФ, возможны расхождения в размере НДС с суммой рассчитанной магазином.', 'woocommerce'),
                    'default' => 'no'
                ),
                'tax_system' => array(
                    'title' => __('Система налогообложения', 'woocommerce'),
                    'type' => 'select',
                    'default' => '0',
                    'options' => array(
                        '0' => __('Общая', 'woocommerce'),
                        '1' => __('Упрощённая, доход', 'woocommerce'),
                        '2' => __('Упрощённая, доход минус расход', 'woocommerce'),
                        '3' => __('Eдиный налог на вменённый доход', 'woocommerce'),
                        '4' => __('Eдиный сельскохозяйственный налог', 'woocommerce'),
                        '5' => __('Патентная система налогообложения', 'woocommerce'),
                    ),
                ),
                'tax_type' => array(
                    'title' => __('Ставка НДС по умолчанию', 'woocommerce'),
                    'type' => 'select',
                    'default' => '0',
                    'options' => array(
                        '0' => __('Без НДС', 'woocommerce'),
                        '1' => __('НДС по ставке 0%', 'woocommerce'),
                        '2' => __('НДС чека по ставке 10%', 'woocommerce'),
                        '3' => __('НДС чека по ставке 18%', 'woocommerce'),
                        '4' => __('НДС чека по расчетной ставке 10/110', 'woocommerce'),
                        '5' => __('НДС чека по расчетной ставке 10/118', 'woocommerce'),
                    ),
                ),


            );
        }


        function get_product_price_with_discount($price, $type, $c_amount, &$order_data)
        {

            switch ($type) {
                case 'percent':
                    $new_price = ceil($price * ( 1 - $c_amount / 100 ));

                    // remove this discount from discount_total
                    $order_data['discount_total'] -= ($price - $new_price);
                    break;

//                case 'fixed_cart':
//                    //wrong
//                    $new_price = $price;
//                    break;

                case 'fixed_product':
                    $new_price = $price - $c_amount;

                    // remove this discount from discount_total
                    $order_data['discount_total'] -= $c_amount / 100;
                    break;

                default:
                    $new_price = $price;
            }
            return $new_price;
        }

        /*
         * Generate the dibs button link
         */
        public function generate_form($order_id)
        {

            $order = new WC_Order($order_id);
            $amount = $order->order_total * 100;

            // COUPONS
            $coupons = array();
            global $woocommerce;
            if (!empty($woocommerce->cart->applied_coupons)) {
                foreach ($woocommerce->cart->applied_coupons as $code) {
                    $coupons[] = new WC_Coupon($code);
                }
            };


            if ($this->test_mode == 'yes') {
                $action_adr = API_TEST_URL;
            } else {
                $action_adr = API_PROD_URL;
            }

            $extra_url_param = '';
            if ($this->stage == 'two-stage') {
                $action_adr .= 'registerPreAuth.do';
            } else if ($this->stage == 'one-stage') {
                $extra_url_param = '&wc-callb=callback_function';
                $action_adr .= 'register.do';
            }

            $order_data = $order->get_data();

            // prepare args array
            $args = array(
                'userName' => $this->merchant,
                'password' => $this->password,
                'amount' => $amount,
                'language' => substr(get_bloginfo("language"), 0, 2),
                'returnUrl' => get_option('siteurl') . '?wc-api=WC_RBSPAYMENT&rbspayment=result&order_id=' . $order_id . $extra_url_param,
//                'currency' => CURRENCY_CODE,
                'jsonParams' => json_encode(
                    array(
                        'CMS:' => 'Wordpress ' . get_bloginfo('version') . " + woocommerce version: " . wpbo_get_woo_version_number(),
                        'Module-Version: ' => $this->pData['Version'],
//                        'Name' => $order_data['billing']['first_name'],
//                        'Famil' => $order_data['billing']['last_name']
                    )
                ),

            );


            if ($this->send_order == 'yes') {

                $args['taxSystem'] = $this->tax_system;

                $order_items = $order->get_items();

                $order_timestamp_created = $order_data['date_created']->getTimestamp();
                $order_billing_email = $order_data['billing']['email'];

                $items = array();
                $itemsCnt = 1;

                /* Заполнение массива данных корзины */
                foreach ($order_items as $value) {
                    $item = array();
                    $tax = new WC_Tax();
                    $product_variation_id = $value['variation_id'];

                    if ($product_variation_id) {
                        $product = new WC_Product_Variation($value['variation_id']);
                        $item_code = $value['variation_id'];
                    } else {
                        $product = new WC_Product($value['product_id']);
                        $item_code = $value['product_id'];
                    }

                    $base_tax_rates = $tax->get_base_tax_rates($product->get_tax_class(true));
                    if (!empty($base_tax_rates)) {
                        $rates = array_shift($tax->get_rates($product->get_tax_class()));
                        $item_rate = round(array_shift($rates));
                        if ($item_rate == 18) {
                            $tax_type = 3;
                        } else if ($item_rate == 10) {
                            $tax_type = 2;
                        } else if ($item_rate == 0) {
                            $tax_type = 1;
                        } else {
                            $tax_type = 0;
                        }
                    } else {
                        $tax_type = 0;
                    }

                    $product_price = round(($product->get_price()) * 100);


                    // if discount (coupon etc)
                    // see DISCOUNT SECTION
//                    foreach ($coupons as $coupon) {
//                        $coupon_amount = $coupon->get_amount() * 100;
//                        $product_price = $this->get_product_price_with_discount($product_price, $coupon->get_discount_type(), $coupon_amount, $order_data );
//                    }

                    $item['positionId'] = $itemsCnt++;
                    $item['name'] = $value['name'];
                    $item['quantity'] = array(
                        'value' => $value['quantity'],
                        'measure' => 'piece'
                    );
                    $item['itemAmount'] = $product_price * $value['quantity'];
                    $item['itemCode'] = $item_code;
                    $item['tax'] = array(
                        'taxType' => $tax_type
                    );
                    $item['itemPrice'] = $product_price;
                    $items[] = $item;
                }


                // DISCOUNT
                if (!empty($order_data['discount_total'])) {
                    $discount = ($order_data['discount_total'] + $order_data['discount_tax']) * 100;

                    $new_order_total = 0;

                    // coze delivery will be another position
                    $delivery_sum = ($order->shipping_total > 0) ? $order->shipping_total * 100 : 0;

                    foreach ($items as &$i) {

                        $p_discount = intval(round(($i['itemAmount']  / ($amount - $delivery_sum + $discount)) * $discount, 2));

                        $this->correctBundleItem($i, $p_discount);
                        $new_order_total += $i['itemAmount'];
                    }

                    // reset order amount
                    // return delivery_sum into amount
                    $args['amount'] = $new_order_total + $delivery_sum;
                }


                // DELIVERY POSITION
                if ($order->shipping_total > 0) {
                    $itemShipment['positionId'] = $itemsCnt;
                    $itemShipment['name'] = 'Доставка';
                    $itemShipment['quantity'] = array(
                        'value' => 1,
                        'measure' => 'piece'
                    );
                    $itemShipment['itemAmount'] = $itemShipment['itemPrice'] = $order->shipping_total * 100;
                    $itemShipment['itemCode'] = 'Delivery';
                    $itemShipment['tax'] = array(
                        'taxType' => $this->tax_type
                    );

                    $items[] = $itemShipment;
                }

                /* Создание и заполнение массива данных заказа для фискализации */
                $order_bundle = array(
                    'orderCreationDate' => $order_timestamp_created,
                    'customerDetails' => array(
                        'email' => $order_billing_email
                    ),
                    'cartItems' => array('items' => $items)
                );


                /* Заполнение массива данных для запроса c фискализацией */
                $args['orderBundle'] = json_encode($order_bundle);
            }


            for ($i = 0; $i++ < 30;) {

                $args['orderNumber'] = $order_id . '_' . $i;

                $rbsCurl = curl_init();
                curl_setopt_array($rbsCurl, array(
                    CURLOPT_HTTPHEADER => array(
                        'CMS: Wordpress ' . get_bloginfo('version') . " + woocommerce version: " . wpbo_get_woo_version_number(),
                        'Module-Version: ' . $this->pData['Version'],
                    ),
                    CURLOPT_URL => $action_adr,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => http_build_query($args, '', '&')
                ));

                $response = curl_exec($rbsCurl);
                curl_close($rbsCurl);

                if (LOGGING) {
                    $this->rbs_logger('Request: ' . $action_adr . ': ' . print_r($args, true) . 'Response: ' . $response, true);
                }

                $response = json_decode($response, true);
                if ($response['errorCode'] != '1') break;
            }


            $errorCode = $response['errorCode'];

            if ($errorCode == 0) {

                wp_redirect($response['formUrl']);
                exit;

            } else {
                return '<p>' . __('Ошибка #' . $errorCode . ': ' . $response['errorMessage'], 'woocommerce') . '</p>' .
                '<a class="button cancel" href="' . $order->get_cancel_order_url() . '">' . __('Отказаться от оплаты и вернуться в корзину', 'woocommerce') . '</a>';
            }
        }


        function correctBundleItem(&$item, $discount) {

            $item['itemAmount'] -= $discount;
            $item['itemPrice'] = $item['itemAmount'] % $item['quantity']['value'];
            if ($item['itemPrice'] != 0)  {
                $item['itemAmount'] += $item['quantity']['value'] - $item['itemPrice'];
            };

            $item['itemPrice'] = $item['itemAmount'] / $item['quantity']['value'];
        }


        /*
         * Process the payment and return the result
         */
        function process_payment($order_id)
        {
            $order = new WC_Order($order_id);

            return array(
                'result' => 'success',
                'redirect' => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(wc_get_page_id('pay'))))
            );
        }

        /*
         * Receipt page
         */
        function receipt_page($order)
        {
            echo $this->generate_form($order);
        }


        function rbs_logger($var, $info = false)
        {
            $information = "";
            if ($var) {
                if ($info) {
                    $information = "\n\n";
                    $information .= str_repeat("-=", 64);
                    $information .= "\nDate: " . date('Y-m-d H:i:s');
                    $information .= "\nWordpress version " . get_bloginfo('version') . "; Woocommerce version: " . wpbo_get_woo_version_number() . "\n";
                }

                $result = $var;
                if (is_array($var) || is_object($var)) {
                    $result = "\n" . print_r($var, true);
                }
                $result .= "\n\n";
                $path = dirname(__FILE__) . '/rbspayment.log';
                error_log($information . $result, 3, $path);
                return true;
            }
            return false;
        }

    }


    function add_rbspayment_gateway($methods)
    {
        $methods[] = 'WC_RBSPAYMENT';
        return $methods;
    }


    function wpbo_get_woo_version_number()
    {
        // If get_plugins() isn't available, require it
        if (!function_exists('get_plugins'))
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');

        // Create the plugins folder and file variables
        $plugin_folder = get_plugins('/' . 'woocommerce');
        $plugin_file = 'woocommerce.php';

        // If the plugin version number is set, return it
        if (isset($plugin_folder[$plugin_file]['Version'])) {
            return $plugin_folder[$plugin_file]['Version'];

        } else {
            // Otherwise return null
            return NULL;
        }
    }

    add_filter('woocommerce_payment_gateways', 'add_rbspayment_gateway');
}