<?php

/**
 * Parser CSV
 *
 * @param bool $file_resource Opened file with file_get_content function
 * @param string $file_name File name on root WordPress directory
 * @return array|bool This array with parent industry and child's branch.
 */
function bre_parse_csv($file_resource = false, $file_name = 'industries.csv')
{
    if (! $file_resource) {
        $file_path = ABSPATH . $file_name;
        if (! file_exists($file_path) && ! is_readable($file_path)) {
            return "Фаил не найден или имеет неверный формат.";
        }

        $file_resource = file_get_contents($file_path);
    }

    $lines = explode(PHP_EOL, $file_resource);
    $clear_data = bre_clear_csv_data($lines);

    // create multidimensional array
    $industry = 0;
    $parse_data = [];
    foreach ($clear_data as $item) {
        if (sizeof($item) > 1) {
            $industry = preg_replace('/\(\d+\)/u', '', $item[1]);
            $industry = trim($industry);

            if (isset($item[2])) {
                $parse_data[$industry][] = $item[2];
            }
        } else {
            $parse_data[$industry][] = array_shift($item);
        }
    }

    return $parse_data;
}

/**
 * Clear CSV data
 *
 * @param $data
 * @return array
 */
function bre_clear_csv_data($data)
{
    unset($data[0]);

    $csv_data = [];
    foreach ($data as $line) {
        $csv_data[] = str_getcsv( $line );
    }

    foreach($csv_data as &$row){
        $row = array_map( 'trim', $row );
    }

    $clear_data = array_map( 'array_filter', $csv_data );

    return array_filter( $clear_data  );
}

/**
 * Add industries with childs
 *
 * @param array $industries Parsed industries with childs
 * @param string $taxonomy Taxonomy name
 * @param string $lang Lang for Polylang plugin
 * @return array List new created industries with childs
 * @throws Exception
 */
function bre_add_industries($industries, $taxonomy = 'industries', $lang = 'ru')
{
    if (! is_array($industries)) {
        throw new Exception('Неправильный формат файла.');
    }

    $created_industries = array();
    $errors = array();
    foreach ($industries as $industry => $industry_childs) {
        $parent_industry = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'parent' => 0,
            'name' => $industry,
        ));

        $parent_industry_id = null;
        if (! $parent_industry) {
            $parent_industry = wp_insert_term($industry, $taxonomy);
            $parent_industry_id = $parent_industry['term_id'];
            pll_set_term_language($parent_industry_id, $lang);
        } else {
            $parent_industry_id = $parent_industry[0]->term_id;
        }

        foreach (array_unique($industry_childs) as $industry_child) {
            $exists_child_industry = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
                'parent' => $parent_industry_id,
                'name' => $industry_child,
            ));

            if (! $exists_child_industry) {
                $parent = get_term($parent_industry_id, $taxonomy);
                $slug = sanitize_text_field($parent->name).'-'.sanitize_text_field($industry_child);

                $child_industry = wp_insert_term(
                    $industry_child,
                    $taxonomy,
                    array(
                        'parent' => $parent_industry_id,
                        'slug' => $slug
                    )
                );

                if (is_wp_error($child_industry)) {
                    $errors[$industry][] = $industry_child;
                    continue;
                }

                pll_set_term_language($child_industry['term_id'], $lang);

                $created_industries[$industry][] = $industry_child;
            }
        }
    }

    return $created_industries + ['errors' => $errors];
}