<?php
/**
 * URL API платежного шлюза
 */

define('RBSPAYMENT_NAME', 'Сбербанк');
define('API_PROD_URL', 'https://securepayments.sberbank.ru/payment/rest/');
define('API_TEST_URL', 'https://3dsec.sberbank.ru/payment/rest/');

/**
 * Логирование
 */
define('LOGGING', true);

/**
 * Настройки отображения
 */

// Заголовки в админке плагина [WooCommerce -> Настройки -> Оплата -> *Плагин*]
define('RBSPAYMENT_TITLE_1', RBSPAYMENT_NAME . ' - Плагин');
define('RBSPAYMENT_TITLE_2', 'Настройка приема электронных платежей через ' . RBSPAYMENT_NAME);
