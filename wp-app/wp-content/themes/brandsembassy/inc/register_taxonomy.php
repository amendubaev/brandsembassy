<?php
/**
 * Register taxonomies
 */

$object_type = array('events', 'speakers', 'companies');
register_taxonomy('industries', $object_type, array(
    'label'                 => 'Индустрия',
    'labels'                => array(
        'name'              => 'Индустрии',
        'singular_name'     => 'Индустрия',
        'search_items'      => 'Поиск индустрии',
        'all_items'         => 'Все индустрии',
        'view_item '        => 'Посмотреть индустрию',
        'parent_item'       => 'Родительская индустрия',
        'edit_item'         => 'Редактировать индустрию',
        'add_new_item'      => 'Добавить новую индустрию',
        'menu_name'         => 'Индустрии',
    ),
    'hierarchical'          => true,
    'sort' 					=> true,
    'rewrite'               => array( 'hierarchical' => true ),
    'capabilities'          => array(),
));

register_taxonomy('patterns', $object_type, array(
    'label'                 => 'Паттерны',
    'labels'                => array(
        'name'              => 'Паттерны',
        'singular_name'     => 'Паттерн',
        'search_items'      => 'Поиск паттернов',
        'all_items'         => 'Все паттерны',
        'view_item '        => 'Посмотреть паттерны',
        'parent_item'       => 'Родительский паттерн',
        'edit_item'         => 'Редактировать паттерн',
        'add_new_item'      => 'Добавить новый паттерн',
        'menu_name'         => 'Паттерны',
    ),
    'hierarchical'          => true,
    'sort' 					=> true,
    'rewrite'               => array( 'hierarchical' => true ),
    'capabilities'          => array(),
));

register_taxonomy('locations', $object_type, array(
    'label'                 => 'Локакции',
    'labels'                => array(
        'name'              => 'Локации',
        'singular_name'     => 'Локация',
        'search_items'      => 'Поиск локации',
        'all_items'         => 'Все локации',
        'view_item '        => 'Посмотреть локацию',
        'parent_item'       => 'Родительская локация',
        'edit_item'         => 'Редактировать локацию',
        'add_new_item'      => 'Добавить новую локацию',
        'menu_name'         => 'Локации',
    ),
    'hierarchical'          => true,
    'sort' 					=> true,
    'rewrite'               => array( 'hierarchical' => true ),
    'capabilities'          => array(),
));

register_taxonomy('institutes', $object_type, array(
    'label'                 => 'Учреждения',
    'labels'                => array(
        'name'              => 'Учреждение',
        'singular_name'     => 'Учреждения',
        'search_items'      => 'Поиск учреждения',
        'all_items'         => 'Все учреждения',
        'view_item '        => 'Посмотреть учреждение',
        'parent_item'       => 'Родительская учреждение',
        'edit_item'         => 'Редактировать учреждение',
        'add_new_item'      => 'Добавить новое учреждение',
        'menu_name'         => 'Учреждения',
    ),
    'hierarchical'          => true,
    'sort' 					=> true,
    'rewrite'               => true,
    'capabilities'          => array(),
));