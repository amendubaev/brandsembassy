<?php
/*
	Template Name: Формы
*/
get_header(); ?>
<style>
    .page-wrapper {
        padding-top: 100px;
    }
    .field-row {
        margin-bottom: 30px;
    }
    .field-row--input {
        padding: 10px;
        font-size: 16px;
        border: none;
        outline: none;
        width: 100%;
    }
    .field-row--title {
        margin-bottom: 10px;
    }
    .select2-container {
        width: 100% !important;
    }
    .field-row--submit {
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        outline: none;
        cursor: pointer;
        transition: .4s all;
        -webkit-appearance: none;
    }
    .field-row--submit:hover {
        opacity: .7;
    }
    .field-row--select {
        -webkit-appearance: none;
        padding: 10px 20px;
        border: none;
        width: 100%;
    }
    .field-row--input[type="checkbox"] {
        width: auto;
    }
    .select2-selection {
        border: none;
    }
    .field-row--radio {
        display: none;
    }
    .field-row--radio[type="file"] {
        display: inline-block;
    }
    .photo-type {
        margin-bottom: 10px;
    }
</style>
<div class="page-wrapper">
    <div class="container">
        <div class="form">
            <?php
            if(isset($_POST['submit'])) {
                print_r($_POST);

                if(isset($_POST['last_name'])) {
                    $last_name = $_POST['last_name'];
                }
                if(isset($_POST['first_name'])) {
                    $first_name = $_POST['first_name'];
                }
                if ($_POST['last_name'] && !(isset($_POST['first_name']))) {
                    $title = $_POST['last_name'];
                } elseif ($_POST['first_name'] && !(isset($_POST['last_name']))) {
                    $title = $_POST['first_name'];
                } else {
                    $title = $_POST['first_name'] . ' ' . $_POST['last_name'];
                }
                
                // Check for speaker existing
                $expert_exist = get_page_by_title($title, OBJECT, array('speakers'));
                if(!$expert_exist) {
                    if (isset($_POST['photo_link'])) {
                        $photo_link = $_POST['photo_link'];
                    }
                    if(isset($_POST['middle_name'])) {
                        $middleName = $_POST['middle_name'];
                    }
                    if(isset($_POST['position'])) {
                        $position = $_POST['position'];
                    }
                    if (isset($_POST['email'])) {
                        $email = $_POST['email'];
                    }
                    if (isset($_POST['phone'])) {
                        $phone = $_POST['phone'];
                    }
                    if(isset($_POST['status'])) {
                        $status = 'draft';
                    } else {
                        $status = 'publish';
                    }
                    
                    if(isset($_POST['expert_author'])) {
                        $expert_author = $_POST['expert_author'];
                    }
                    if(isset($_POST['person'])) {
                        $person = $_POST['person'];
                    }
                    if(isset($_POST['activity'])) {
                        $activity = $_POST['activity'];
                    }
            
                    if(isset($_POST['life'])) {
                        $life = $_POST['life'];
                    }
                    if(isset($_POST['facts'])) {
                        $facts = $_POST['facts'];
                    }
                    if(isset($_POST['quote'])) {
                        $quote = $_POST['quote'];
                    }
                    if(isset($_POST['twitter'])) {
                        $twitter = $_POST['twitter'];
                    }
                    if(isset($_POST['facebook'])) {
                        $facebook = $_POST['facebook'];
                    }
                    if(isset($_POST['youtube'])) {
                        $youtube = $_POST['youtube'];
                    }
                    if(isset($_POST['linkedin'])) {
                        $linkedin = $_POST['linkedin'];
                    }
                    if(isset($_POST['vk'])) {
                        $vk = $_POST['vk'];
                    }
                    if(isset($_POST['instagram'])) {
                        $instagram = $_POST['instagram'];
                    }
                    if(isset($_POST['additional_info'])) {
                        $additional_info = $_POST['additional_info'];
                    }
                    if(isset($_POST['expert_from_direction'])) {
                        $direction = $_POST['expert_from_direction'];
                    }
                    
                    // Taxonomies
                    if(isset($_POST['industries'])) {
                        $industries = array();
                        $industriesAttachedArr = $_POST['industries'];

                        foreach ($industriesAttachedArr as $parentId => $childId) {
                            // Get existing parent and child terms
                            $existsParent = term_exists(intval($parentId), 'industries', 0);
                            
                            // Get or create parent tax
                            if ($existsParent) {
                                $industryGet = get_term($existsParent['term_id'], 'industries');
                                pll_set_term_language($industryGet->term_id, 'ru');
                            } else {
                                $industriesCreatedGet = wp_insert_term($parentId, 'industries', array('parent' => 0));
                                $industryGet = get_term($industriesCreatedGet['term_id'], 'industries');
                                pll_set_term_language($industryGet->term_id, 'ru');
                            }
                    
                            $industries[] = $industryGet->name;
                            // Attach and create child terms to parent
                            foreach($childId as $childrens) {
                                $existsChild = term_exists(intval($childrens), 'industries');
                                
                                // Get or create child tax
                                if ($existsChild) {
                                    $branchGet = get_term($existsChild['term_id'], 'industries');
                                    pll_set_term_language($branchGet->term_id, 'ru');
                                } else {
                                    $branchesCreatedGet = wp_insert_term($childrens, 'industries', array('parent' => $industryGet->term_id));
                                    $branchGet = get_term($branchesCreatedGet['term_id'], 'industries');
                                    pll_set_term_language($branchGet->term_id, 'ru');
                                }
                                $industries[] = $branchGet->name;
                            }
                        }
                    }
                    
                    if(isset($_POST['locations'])) {
                        $locationsArr = array();
                        $locationsAttachedArr = $_POST['locations'];
                        foreach ($locationsAttachedArr as $country => $cities) {
                            // Get existing parent and child terms
                            $existsParent = term_exists(intval($country), 'locations', 0);
                            
                            // Get or create parent tax
                            if ($existsParent) {
                                $countryGet = get_term($existsParent['term_id'], 'locations');
                                pll_set_term_language($countryGet->term_id, 'ru');
                            } else {
                                $locationsCreatedGet = wp_insert_term($country, 'locations', array('parent' => 0));
                                $countryGet = get_term($locationsCreatedGet['term_id'], 'locations');
                                pll_set_term_language($countryGet->term_id, 'ru');
                            }
                    
                            $locationsArr[] = $countryGet->name;
                            // Attach and create child terms to parent
                            foreach($cities as $city => $locations) {
                                $existsChild = term_exists(intval($city), 'locations');
                                
                                // Get or create child tax
                                if ($existsChild) {
                                    $cityGet = get_term($existsChild['term_id'], 'locations');
                                    pll_set_term_language($cityGet->term_id, 'ru');
                                } else {
                                    $cityCreatedGet = wp_insert_term($city, 'locations', array('parent' => $countryGet->term_id));
                                    $cityGet = get_term($cityCreatedGet['term_id'], 'locations');
                                    pll_set_term_language($cityGet->term_id, 'ru');
                                }
                                $locationsArr[] = $cityGet->name;

                                foreach($locations as $location) {
                                    $existsChild = term_exists(intval($location), 'locations');
                                
                                    // Get or create child tax
                                    if ($existsChild) {
                                        $locationGet = get_term($existsChild['term_id'], 'locations');
                                        pll_set_term_language($locationGet->term_id, 'ru');
                                    } else {
                                        $locationCreatedGet = wp_insert_term($location, 'locations', array('parent' => $cityGet->term_id));
                                        $locationGet = get_term($locationCreatedGet['term_id'], 'locations');
                                        pll_set_term_language($locationGet->term_id, 'ru');
                                    }
                                    $locationsArr[] = $locationGet->name;
                                }
                            }
                        }
                    }

                    if(isset($_POST['institutes'])) {
                        $institutes = array();
                        $institutesAttachedArr = $_POST['institutes'];
                        foreach ($institutesAttachedArr as $key) {
                            $exists = term_exists(intval($key), 'institutes');
                            if ($exists) {
                                $instituteGet = get_term($exists['term_id'], 'institutes');
                                pll_set_term_language($instituteGet->term_id, 'ru');
                            } else {
                                $institutesCreatedGet = wp_insert_term($key, 'institutes', array('parent' => 0));
                                $instituteGet = get_term($institutesCreatedGet['term_id'], 'institutes');
                                pll_set_term_language($instituteGet->term_id, 'ru');
                            }
                            $institutes[] = $instituteGet->name;
                        }
                    }
                    // bitrix24
                    $queryUrl = 'https://brandsembassy.bitrix24.ru/rest/17/01sq2il2orj2jipq/crm.contact.add.json';
                    $queryData = http_build_query(array(
                        'fields' => array(
                            'TITLE' => $title,
                            'NAME' => $first_name,
                            'LAST_NAME' => $last_name,
                            'STATUS_ID' => 'NEW',
                            'OPENED' => 'Y',
                            'TYPE_ID' => 1,
                            'ASSIGNED_BY_ID' => 1,
                            'PHONE' => array(
                                array(
                                    'VALUE' => $_POST['phone'],
                                    'VALUE_TYPE' => 'WORK'
                                )
                            ),
                            'EMAIL' => array(
                                array(
                                    'VALUE' => $_POST['email'],
                                    'VALUE_TYPE' => 'WORK'
                                )
                            ),
                            'POST' => $_POST['position'],
                        ),
                    'params' => array('REGISTER_SONET_EVENT' => 'Y') ));
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_POST => 1,
                        CURLOPT_HEADER => 0,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => $queryUrl,
                        CURLOPT_POSTFIELDS => $queryData, )
                    );
                    
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $result = json_decode($result, 1);
                    $bitrixID = $result['result'];
                    
                    $post = array(
                        'post_title' => $title,
                        'post_content' => 'From custom form',
                        'post_status' => $status,
                        'post_author' => '1',
                        'post_type' => 'speakers',
                        'meta_input' => array(
                            'expert_author' => ($expert_author ? $expert_author : ''),
                            'expert_skills' => (isset($_POST['position']) ? $position : ''),
                            'expert_activity' => (isset($_POST['activity']) ? $activity : ''),
                            'expert_middlename' => ($middleName ? $middleName : ''),
                            'expert_photo' => ($photo_link ? $photo_link : ''),
                            'expert_person' => ($person ? $person : ''),
                            'expert_life' => (isset($_POST['life']) ? $life : ''),
                            'expert_facts' => ($facts ? $facts : ''),
                            'expert_quote' => ($quote ? $quote : ''),
                            'expert_facebook' => ($facebook ? $facebook : ''),
                            'expert_linkedin' => ($linkedin ? $linkedin : ''),
                            'expert_instagram' => ($instagram ? $instagram : ''),
                            'expert_twitter' => ($twitter ? $twitter : ''),
                            'expert_youtube' => ($youtube ? $youtube : ''),
                            'expert_vk' => ($vk ? $vk : ''),
                            'expert_additionalinfo' => ($additional_info ? $additional_info : ''),
                            'bitrix_id' => ($bitrixID ? $bitrixID : ''),
                            'expert_phone' => ($phone ? $phone : ''),
                            'expert_email' => ($email ? $email : ''),
                            'expert_from_direction' => ($direction ? $direction : ''),
                        ),
                    );
                    $post_id = wp_insert_post($post);
                    if(isset($_POST['company'])) {
                        $companies = $_POST['company'];
                        $company_ids = array();
                        foreach($companies as $company) {
                            if(!get_post($company)) {
                                $company_post = array(
                                    'post_title' => $company,
                                    'post_content' => 'From custom form',
                                    'post_status' => $status,
                                    'post_author' => '1',
                                    'post_type' => 'companies',
                                    'meta_input' => array(
                                        'attached_expert' => ($post_id ? $post_id : ''),
                                    ),
                                );
                                $created_company_ids[] = wp_insert_post($company_post);
                            } else {
                                $created_company_ids[] = $company;
                            }
                        }
                        array_push($company_ids, $created_company_ids);
                    }
                    
                    if(isset($_POST['industries'])) {
                        wp_set_object_terms($post_id, $industries, 'industries');
                    }
                    if(isset($_POST['locations'])) {
                        wp_set_object_terms($post_id, $locationsArr, 'locations');
                    }
                    if(isset($_POST['institutes'])) {
                        wp_set_object_terms($post_id, $institutes, 'institutes');
                    }

                    if(isset($_POST['company'])) {
                        foreach($company_ids as $company_id) {
                            update_field('field_5d06751a590d5', $company_id, $post_id);
                        }
                    }

                    if($_POST['photo_type'] == 'file') {
                        if(is_uploaded_file($_FILES['photo_file']['tmp_name'])) {
                            $photo = wp_upload_bits($_FILES['photo_file']['name'], null, file_get_contents($_FILES['photo_file']['tmp_name']));
                            $filename = $photo['file'];
                            $wp_filetype = wp_check_filetype($filename, null );
                            $attachment = array(
                                'post_mime_type' => $wp_filetype['type'],
                                'post_title' => sanitize_file_name($filename),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );
                            $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
                            require_once(ABSPATH . 'wp-admin/includes/image.php');
                            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                            wp_update_attachment_metadata( $attach_id, $attach_data );
                            set_post_thumbnail( $post_id, $attach_id );
                        }
                    }

                    if($_POST['photo_type'] == 'file') {
                        if(is_uploaded_file($_FILES['photo_file']['tmp_name'])) {
                            $photo = wp_upload_bits($_FILES['photo_file']['name'], null, file_get_contents($_FILES['photo_file']['tmp_name']));
                            $filename = $photo['file'];
                            $wp_filetype = wp_check_filetype($filename, null );
                            $attachment = array(
                                'post_mime_type' => $wp_filetype['type'],
                                'post_title' => sanitize_file_name($filename),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );
                            $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
                            require_once(ABSPATH . 'wp-admin/includes/image.php');
                            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                            wp_update_attachment_metadata( $attach_id, $attach_data );
                            set_post_thumbnail( $post_id, $attach_id );
                        }
                    } else {
                        if($_POST['photo_link']) {
                            if(strpos($photo_link, 'drive.google.com/open') !== false) {
                                $parse_image_path = array();
                                if (preg_match('/id=([^&]+)/', $photo_link)) {
                                    preg_match('/id=([^&]+)/', $photo_link, $parse_image_path);
                                }
                                $parse_image_url = 'https://docs.google.com/uc?id=' . $parse_image_path[1];
                                $image_url = $parse_image_url;
                            } else {
                                $image_url = $photo_link;
                            }
                            $image_name       = $bitrixID . '.png';
                            $upload_dir       = wp_upload_dir();
                            $image_data       = file_get_contents($image_url);
                            $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name);
                            $filename         = basename($unique_file_name);
            
                            if (wp_mkdir_p($upload_dir['path'])) {
                                $file = $upload_dir['path'] . '/' . $filename;
                            } else {
                                $file = $upload_dir['basedir'] . '/' . $filename;
                            }
            
                            file_put_contents($file, $image_data);
            
                            $wp_filetype = wp_check_filetype($filename, null);
                            $attachment = array(
                                'post_mime_type' => $wp_filetype['type'],
                                'post_title'     => sanitize_file_name($filename),
                                'post_content'   => '',
                                'post_status'    => 'inherit'
                            );
            
                            $attach_id = wp_insert_attachment($attachment, $file, $post_id);
                            require_once(ABSPATH . 'wp-admin/includes/image.php');
                            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
                            wp_update_attachment_metadata($attach_id, $attach_data);
                            set_post_thumbnail($post_id, $attach_id);
                        }
                    }
                    if(is_uploaded_file($_FILES['antiplagiat']['tmp_name'])) {
                        $antiplagiat = wp_upload_bits($_FILES['antiplagiat']['name'], null, file_get_contents($_FILES['antiplagiat']['tmp_name']));
                        $filename = $antiplagiat['file'];
                        $wp_filetype = wp_check_filetype($filename, null );
                        $attachment = array(
                            'post_mime_type' => $wp_filetype['type'],
                            'post_title' => sanitize_file_name($filename),
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );
                        $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                        wp_update_attachment_metadata( $attach_id, $attach_data );
                        update_field('check_antiplagiat' , $attach_id, $post_id);
                    }
                    if($post_id) {
                        echo '<div class="expert-form--result">';
                        echo '<p>Эксперт успешно добавлен!</p>';
                        echo '<a href="' . get_the_permalink($post_id) . '" target="_blank">Посмотреть эксперта на сайте</a><br/>';
                        echo '<a href="https://brandsembassy.bitrix24.ru/crm/contact/details/' . $bitrixID . '" target="_blank">Посмотреть эксперта в Bitrix24</a>';
                        echo '</div>';
                    }
                } else {
                    echo 'Эксперт уже существует <a href="' . get_the_permalink($expert_exist->ID) . '" target="_blank">Посмотреть эксперта на сайте</a><br/>';
                }
            }
            ?>
            <form enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="create-expert" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Фамилия и имя автора</div>
                            <input type="text" required name="expert_author" class="field-row--input" placeholder="Фамилия и имя Автора">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Фамилия</div>
                            <input type="text" required name="last_name" class="field-row--input" placeholder="Фамилия">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Имя</div>
                            <input type="text" required name="first_name" class="field-row--input" placeholder="Имя">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Отчество</div>
                            <input type="text" name="middle_name" class="field-row--input" placeholder="Отчество">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Фото эксперта <a target="_blank" href="http://flatonika.ru/skruglitel-kartinok-online/">Скгрулитель фото</a></div>
                            <div class="photo-type">
                                <input type="radio" class="photo-type" checked name="photo_type" id="photo_file" value="file"><label for="photo_file">Фото файл</label>
                                <input type="radio" class="photo-type" name="photo_type" id="photo_link" value="link"><label for="photo_link">Фото ссылка</label>
                            </div>
                            <input type="file" accept=".png,.jpg,.jpeg" class="field-row--input field-row--radio" name="photo_file">
                            <input type="text" class="field-row--input field-row--radio" name="photo_link" placeholder="Ссылка на фото эксперта">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Должность, Статус</div>
                            <input type="text" name="position" class="field-row--input" placeholder="Должность, Статус">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Личность</div>
                            <?php wp_editor('Личность', 'person', array('media_buttons' => 0, 'textarea_rows' => 3, 'teeny' => 1, 'quicktags' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Деятельность</div>
                            <?php wp_editor('Деятельность', 'activity', array('textarea_rows' => 3, 'teeny' => 1, 'quicktags' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Интересные факты и события</div>
                            <?php wp_editor('Интересные факты и события', 'facts', array('textarea_rows' => 3, 'teeny' => 1, 'quicktags' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Цитата</div>
                            <?php wp_editor('Цитата', 'quote', array('textarea_rows' => 3, 'teeny' => 1, 'quicktags' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Образование</div>
                            <select id="select-institutes" required class="field-selectable" name="institutes[]" multiple="multiple">
                                <?php $institutesArgs = array(
                                    'taxonomy' => 'institutes',
                                    'hide_empty' => 0,
                                    'parent' => 0
                                );
                                $institutes = get_terms($institutesArgs);
                                foreach($institutes as $institute) : ?>
                                    <option value="<?php echo $institute->term_id; ?>"><?php echo $institute->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Страны</div>
                            <select id="select-countries" required class="field-selectable" name="countries[]" multiple="multiple">
                                <?php $locationsArgs = array(
                                    'taxonomy' => 'locations',
                                    'hide_empty' => 0,
                                    'parent' => 0
                                );
                                $locations = get_terms($locationsArgs);
                                foreach($locations as $location) : ?>
                                    <option value="<?php echo $location->term_id; ?>"><?php echo $location->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="cities-custom"></div>
                    </div>
                </div>
                <div class="locations-custom">
                    <div class="row"></div>
                </div>
                <div class="row" style="display: none;">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Города</div>
                            <select id="select-cities" class="field-selectable" name="allcities[]" multiple="multiple">
                                <?php $countriesArgs = array(
                                    'taxonomy' => 'locations',
                                    'hide_empty' => 0,
                                    'parent' => 0,
                                    'fields' => 'ids',
                                );
                                $countries = get_terms($countriesArgs);
                                foreach($countries as $country) :
                                        $citiesArgs = array(
                                            'taxonomy' => 'locations',
                                            'hide_empty' => 0,
                                            'parent' => $country,
                                        );
                                        $cities = get_terms($citiesArgs);
                                        foreach($cities as $city) : ?>
                                            <option value="<?php echo $city->term_id; ?>" data-country="<?php echo get_term_by('id', $city->parent, $city->taxonomy)->term_id; ?>"><?php echo $city->name; ?></option>
                                        <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                            <select id="select-locations" class="field-selectable" name="alllocations[]" multiple="multiple">
                                <?php $countriesArgs = array(
                                    'taxonomy' => 'locations',
                                    'hide_empty' => 0,
                                    'parent' => 0,
                                    'fields' => 'ids',
                                );
                                $countries = get_terms($countriesArgs);
                                foreach($countries as $country) :
                                    $citiesArgs = array(
                                        'taxonomy' => 'locations',
                                        'hide_empty' => 0,
                                        'parent' => $country,
                                        'fields' => 'ids'
                                    );
                                    $cities = get_terms($citiesArgs);
                                    foreach($cities as $city) :
                                        $locationsArgs = array(
                                            'taxonomy' => 'locations',
                                            'hide_empty' => 0,
                                            'parent' => $city,
                                        );
                                        $locations = get_terms($locationsArgs);
                                        foreach($locations as $location) :?>
                                            <option value="<?php echo $location->term_id; ?>" data-city="<?php echo get_term_by('id', $location->parent, $location->taxonomy)->term_id; ?>"><?php echo $location->name; ?></option>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Компании</div>
                            <select id="select-companies" required class="field-selectable" name="company[]" multiple="multiple">
                                <?php $companiesArgs = array(
                                    'post_type' => 'companies',
                                    'numberposts' => -1,
                                    'post_status' => 'publish'
                                );
                                $companies = get_posts($companiesArgs);
                                foreach($companies as $company) : setup_postdata($company); ?>
                                    <option value="<?php echo $company->ID; ?>"><?php echo get_the_title($company->ID); ?></option>
                                <?php endforeach; wp_reset_postdata(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row field-row--industries">
                            <div class="field-row--title">Индустрии</div>
                            <select id="select-industries" required class="field-selectable" name="" multiple="multiple">
                                <?php $industriesArgs = array(
                                    'taxonomy' => 'industries',
                                    'hide_empty' => 0,
                                    'parent' => 0
                                );
                                $industries = get_terms($industriesArgs);
                                foreach($industries as $industry) : ?>
                                    <option value="<?php echo $industry->term_id; ?>"><?php echo $industry->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="branches-custom"></div>
                    </div>
                </div>
                <div class="row" style="display: none;">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Отрасли</div>
                            <select id="select-branches" class="field-selectable" name="allbranches[]" multiple="multiple">
                            <?php $industriesArgs = array(
                                    'taxonomy' => 'industries',
                                    'hide_empty' => 0,
                                    'parent' => 0,
                                    'fields' => 'ids',
                                );
                                $industries = get_terms($industriesArgs);
                                foreach($industries as $industry) :
                                        $branchesArgs = array(
                                            'taxonomy' => 'industries',
                                            'hide_empty' => 0,
                                            'parent' => $industry,
                                        );
                                        $branches = get_terms($branchesArgs);
                                        foreach($branches as $branch) : ?>
                                            <option value="<?php echo $branch->term_id; ?>" data-parent="<?php echo get_term_by('id', $branch->parent, 'industries')->term_id; ?>"><?php echo $branch->name; ?></option>
                                        <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Эксперт из направления</div>
                            <select class="field-row--select" required id="select-direction" name="expert_from_direction">
                                <option value="0" selected>Не выбрано</option>
                                <option value="1">Представитель отраслевых СМИ</option>
                                <option value="2">Представитель Компании - лидера отрасли</option>
                                <option value="3">Представитель отраслевого образовательного учреждения</option>
                                <option value="4">Организатор отраслевых выставок</option>
                                <option value="5">Организатор отраслевых мероприятий, форумов и конференций</option>
                                <option value="6">Эксперт отрасли</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Дополнительная информация</div>
                            <?php wp_editor('Дополнительная информация', 'additional_info', array('textarea_rows' => 3, 'teeny' => 1, 'quicktags' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Facebook</div>
                            <input type="text" id="facebook" name="facebook" class="field-row--input" placeholder="Facebook">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Twitter</div>
                            <input type="text" id="twitter" name="twitter" class="field-row--input" placeholder="Twitter">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Linkedin</div>
                            <input type="text" id="linkedin" name="linkedin" class="field-row--input" placeholder="Linkedin">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Instagram</div>
                            <input type="text" id="instagram" name="instagram" class="field-row--input" placeholder="Instagram">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">YouTube</div>
                            <input type="text" id="youtube" name="youtube" class="field-row--input" placeholder="YouTube">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">VK</div>
                            <input type="text" id="vk" name="vk" class="field-row--input" placeholder="VK">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Email</div>
                            <input type="email" name="email" class="field-row--input" placeholder="E-mail">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Телефон</div>
                            <input type="phone" name="phone" class="field-row--input" placeholder="Телефон">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Статус</div>
                            <input type="checkbox" name="status" id="status" class="field-row--input"><label for="status">Не публиковать</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <div class="field-row--title">Проверка на антиплагиат</div>
                            <input type="file" required name="antiplagiat" accept=".png,.jpg,.jpeg" id="antiplagiat" class="field-row--input">
                        </div>
                    </div>
                </div>
                    <div class="row">
                    <div class="col-md-12">
                        <div class="field-row">
                            <input type="submit" name="submit" class="field-row--submit" value="Добавить эксперта">
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="<?php echo get_template_directory_uri() . '/assets/js/add-expert.js'; ?>"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" type="text/css">
<script>
    $(document).ready(function() {
        $('.field-selectable').select2({
            tags: true
        });
    });
</script>