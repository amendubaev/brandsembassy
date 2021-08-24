<?php
/**
 * Register custom post types
 */

register_post_type('events', array(
    'labels'             => array(
        'name'               => 'События',
        'singular_name'      => 'Событие',
        'add_new'            => 'Добавить новое',
        'add_new_item'       => 'Добавить новое',
        'edit_item'          => 'Редактировать мероприятие',
        'new_item'           => 'Новое мероприятие',
        'view_item'          => 'Посмотреть мероприятие',
        'search_items'       => 'Найти событие',
        'not_found'          => 'Событие не найдено',
        'not_found_in_trash' => 'В корзине событие не найдено',
        'parent_item_colon'  => '',
        'menu_name'          => 'События'
    ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => true,
    'capability_type'    => 'cpt',
    'capabilities' => array(
        'edit_post' => 'edit_cpt',
        'edit_posts' => 'edit_cpts',
        'edit_others_posts' => 'edit_other_cpts',
        'publish_posts' => 'publish_cpts',
        'read_post' => 'read_cpt',
        'read_private_posts' => 'read_private_cpts',
        'delete_post' => 'delete_cpt'
    ),
    'map_meta_cap' => true,
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array('title', 'editor', 'revisions', 'author', 'thumbnail', 'tags'),
));
register_post_type('speakers', array(
    'labels'             => array(
        'name'               => 'Эксперты',
        'singular_name'      => 'Эксперт',
        'add_new'            => 'Добавить нового',
        'add_new_item'       => 'Добавить нового',
        'edit_item'          => 'Редактировать эксперта',
        'new_item'           => 'Новый эксперт',
        'view_item'          => 'Посмотреть экспертов',
        'search_items'       => 'Найти эксперта',
        'not_found'          => 'Эксперты не найдены',
        'not_found_in_trash' => 'В корзине экспертов не найдено',
        'parent_item_colon'  => '',
        'menu_name'          => 'Эксперты'
    ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite' => array(
        'slug' => 'experts'
    ),
    'capability_type'    => 'cpt',
    'map_meta_cap' => true,
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array('title', 'editor', 'revisions', 'author', 'thumbnail', 'tags'),
));
register_post_type('companies', array(
    'labels'             => array(
        'name'               => 'Компании',
        'singular_name'      => 'Компании',
        'add_new'            => 'Добавить новую',
        'add_new_item'       => 'Добавить новую',
        'edit_item'          => 'Редактировать компанию',
        'new_item'           => 'Новая компания',
        'view_item'          => 'Посмотреть компании',
        'search_items'       => 'Найти компании',
        'not_found'          => 'Компании не найдено',
        'not_found_in_trash' => 'В корзине компании не найдено',
        'parent_item_colon'  => '',
        'menu_name'          => 'Компании'
    ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => true,
    'capability_type'    => 'cpt',
    
    'map_meta_cap' => true,
    'has_archive'        => true,
    'hierarchical'       => true,
    'supports'           => array('title', 'editor', 'revisions', 'author', 'thumbnail', 'tags'),
));