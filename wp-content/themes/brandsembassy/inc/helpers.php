<?php

/**
 * Debugger
 *
 * @param $data
 * @param bool $stop
 */
if (! function_exists('pr')) {
    function pr($data, $stop = true) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        if ($stop) {
            die;
        }
    }
}

/**
 * Translate cyrillic string to latin or vice versa
 *
 * @param string $textcyr
 * @param string $textlat
 */
if (! function_exists('bre_transliterate')) {
    function bre_transliterate($textcyr = null, $textlat = null) {
        $cyr = array(
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я', 'ы',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я', 'Ы');
        $lat = array(
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q', 'y',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q', 'Y');

        if ($textcyr) {
            return str_replace($cyr, $lat, $textcyr);
        } else if ($textlat) {
            return str_replace($lat, $cyr, $textlat);
        } else {
            return null;
        }
    }
}
