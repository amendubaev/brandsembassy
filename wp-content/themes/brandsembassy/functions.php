<?php
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/industry_parser.php';
require_once __DIR__ . '/classes/Walker_Taxonomy.php';

function theme_setup() {
    load_theme_textdomain( 'brandsembassy', get_template_directory_uri() . '/languages' );
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	register_nav_menus(array(
		'primary' => __('Primary Menu'),
		'footer_1' => __('Footer Menu 1'),
		'footer_2' => __('Footer Menu 2'),
		'footer_3' => __('Footer Menu 3'),
		'footer_4' => __('Footer Menu 4'),
	));
	// Remove meta tag generator
    remove_action('wp_head', 'wp_generator');
    add_filter('use_block_editor_for_post', '__return_false');
}
add_action('after_setup_theme', 'theme_setup');

function register_my_widgets() {
	for ($i = 0; $i < 4; $i++) {
		register_sidebar(array(
			'name'          => sprintf(__('Footer %d'), $i),
			'id'            => "footer-$i",
			'description'   => '',
			'class'         => '',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<div class="footer_col-title">',
			'after_title'   => "</div>\n",
		));
	}
}
add_action('widgets_init', 'register_my_widgets');

function unset_unused_sizes( $sizes ){
    unset($sizes['thumbnail']);
    unset($sizes['small']);
    unset($sizes['medium_large']);
	unset($sizes['large']);
	unset($sizes['shop_thumbnail']);
	unset($sizes['shop_catalog']);
	unset($sizes['shop_single']);
    return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'unset_unused_sizes' );

function theme_scripts() {
	wp_deregister_script('jquery');
	wp_register_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');
	wp_enqueue_script('jquery');
    wp_enqueue_script('slick-script', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', false, null, true);
    wp_enqueue_script('match-height', '//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js');
    wp_enqueue_script('search-script', get_template_directory_uri() . '/assets/js/search.js', false, null, true);
    wp_enqueue_script('jssocial-script', '//cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.min.js', false, null, true);
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.js', false, null, true);
    wp_enqueue_script('home-script', get_template_directory_uri() . '/assets/js/home.js', false, null, true);
    
	wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/assets/css/bootstrap-grid.min.css');
	wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/assets/css/bootstrap-grid.min.css');
	wp_enqueue_style('jssocialflat-style', '//cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials-theme-flat.css');
	wp_enqueue_style('jssocials-style', '//cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.css');
	wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/assets/css/bootstrap-grid.min.css');
    wp_enqueue_style('slick-style', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css');
	wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/main.css');
	wp_enqueue_style('fontawesome-style', get_template_directory_uri() . '/assets/css/fontawesome-all.min.css');
	// AJAX link for search/filter popup
    wp_localize_script( 'search-script', 'search',
        array(
            'url' => admin_url('admin-ajax.php').'?lang=' . icl_get_current_language(),
        )
    );
}
add_action('wp_enqueue_scripts', 'theme_scripts');

add_filter('wpcf7_autop_or_not', '__return_false');

add_action('init', 'my_custom_init');
function my_custom_init() {
    require_once __DIR__ . '/inc/register_custom_post_type.php';
	require_once __DIR__ . '/inc/register_taxonomy.php';

	flush_rewrite_rules();
}

function cc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

// Filter which removes p wrapper in img tag
function filter_ptags_on_images($content) {
	return preg_replace('/<p>(\s*)(<img .* \/>)(\s*)<\/p>/iU', '\2', $content);
}

add_filter('the_content', 'filter_ptags_on_images');

function tinymce_paste_as_text( $init ) {
    $init['paste_as_text'] = true;
    $init['paste_remove_spans'] = true;
    $init['paste_remove_styles'] = true;

    return $init;
}
add_filter('tiny_mce_before_init', 'tinymce_paste_as_text');

// Add industry import page to tools
add_management_page( 'Импорт индустрий', 'Импорт индустрий', 'edit_published_posts', 'industries-import', 'industries_import_options_page' );
function industries_import_options_page() {
    require_once 'views/industries-import.php';
}

// Parser handler
add_action( 'admin_action_industries-import', 'bre_parser_run' );
function bre_parser_run() {
    $file_path = $_FILES['industries_csv']['tmp_name'];
    if (! is_uploaded_file($file_path)) {
        wp_redirect( $_SERVER['HTTP_REFERER'] );
        exit;
    }

    $file = file_get_contents($file_path);
    $industries = bre_parse_csv($file);

    try {
        bre_add_industries($industries);
    } catch (Exception $e) {
        echo "Произошла ошибка '" . $e->getMessage() . "'<br>";
        echo "Код ошибки '" . $e->getCode() . "'";

        return null;
    }

    wp_redirect( $_SERVER['HTTP_REFERER'] );
    exit;
}

// Search and filter
add_action('wp_ajax_search', 'search');
add_action('wp_ajax_nopriv_search', 'search');
function search() {
    $result = [];
    if (isset( $_POST['s'] )) {
        $search = sanitize_text_field( $_POST['s'] );

        $terms = get_terms(array(
            'taxonomy' => sanitize_text_field( $_POST['taxonomy'] ),
            'order' => 'ASC',
            'hide_empty' => false,
            'hierarhical' => true,
            'search' => $search
        ));

        foreach ($terms as $term) {
            $type = $term->parent ? 'child' : 'parent';

            if ($type == 'child') {
                $parent = get_term($term->parent);
                $term->parent_name = htmlspecialchars_decode($parent->name);
            }

            $term->name = htmlspecialchars_decode($term->name);

            $result[$type][] = $term;
        }
    } else {
        $result = get_terms(array(
            'taxonomy' => sanitize_text_field( $_POST['taxonomy'] ),
            'parent' => sanitize_text_field( $_POST['parent_id'] ),
            'hide_empty' => false
        ));
    }

    wp_send_json($result);

    wp_die();
}

/**
 * Print terms + count
 *
 * @param array $terms
 * @param string $empty_text | Text will print if terms is empty
 * @param bool $link | False for count without link on taxonomy
 * @return bool|null
 */
function bre_get_the_names_with_count($terms, $empty_text = '', $link = false) {
    if (! isset($terms) || empty($terms)) {
        echo $empty_text;

        return false;
    }

    $count_string = count($terms) > 1 ? ', +' . (count($terms) - 1) : '';

    $name = $terms[0]->name;
    if (! $link) {
        echo "<span class='card-link--blue'>" . $name . $count_string . "</span>";

        return null;
    }

   $link = get_term_link( pll_get_term($terms[0]->term_id) );

    echo "<a class='card-link--blue' href='$link'>" . $name . $count_string . "</a>";
}

/**
 * Get parent terms from childrens terms
 *
 * @param array $terms | Terms array
 * @param bool $taxonomy
 * @return array|int|WP_Error
 */
function bre_get_term_parents(array $terms, $taxonomy = false) {
    if (empty($terms)) {
        return [];
    }

    $args = array(
        'include' => array_unique(array_column($terms, 'parent')),
        'hide_empty' => false,
    );

    if ($taxonomy) {
        $args['taxonomy'] = $taxonomy;
    }

    return get_terms($args);
}

/**
 * Get HTML after open popup
 */
add_action('wp_ajax_open_popup', 'bre_append_html_on_popup_load');
add_action('wp_ajax_nopriv_open_popup', 'bre_append_html_on_popup_load');
function bre_append_html_on_popup_load() {
    $taxonomy = sanitize_text_field( $_POST['taxonomy'] );

    $searchName = [];
        switch ($taxonomy) {
            case 'industries':
                $searchName['parent'] = __('Industries', 'brandsembassy');
                $searchName['child'] = __('Branches', 'brandsembassy');
            break;
            case 'locations':
                $searchName['parent'] = __('Countries', 'brandsembassy');
                $searchName['child'] = __('Cities', 'brandsembassy');
            break;
            case 'patterns':
                $searchName['parent'] = __('Patterns', 'brandsembassy');
                $searchName['child'] = __('Megatrends', 'brandsembassy');
            break;
        }

    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'parent' => 0
    ));

    $filterResult = bre_append_filtered_terms_to_popup();

    wp_send_json_success(array(
        'parent' => array(
            'html' => bre_append_parent_terms_to_popup($terms, $taxonomy, false),
            'count' => count($terms),
            'name' => $searchName['parent'],
            'childName' => $searchName['child']
        ),

        'filterResult' => array(
            'html' => $filterResult['html'],
            'parentCount' => $filterResult['parentCount'],
            'childCount' => $filterResult['childsCount'],
        )
    ));
}

/**
 * Get items slice and return formatting array with main and slice amount
 *
 * @param array $items
 * @param int $slice_end
 * @return array
 */
function bre_get_sliced_items(array $items, $slice_end = 3) {
    $result = [
        'sliced' => [],
        'ids' => [],
        'main_amount' => count($items),
        'sliced_amount' => count($items)
    ];

    $sliced_items = array_slice($items, 0, $slice_end);

    $result['sliced'] = $sliced_items;
    $result['ids'] = array_column($items, 'ID');
    $result['sliced_amount'] = count($items) - count($sliced_items);

    return $result;
}

/**
 *  Generate HTML to the first column in popup
 *
 * @param array $terms
 * @param $taxonomy
 * @param bool $echo
 * @return string
 */
function bre_append_parent_terms_to_popup(array $terms, $taxonomy, $echo = true) {
    if (empty($terms)) {
        echo 'Terms not found';

        return false;
    }

    $html = '';
     foreach ($terms as $term) {
         $html .= "<div class='search-term search-term-parent'>";
         $html .= "<input type='checkbox' name='$taxonomy' id='$term->term_id' value='$term->term_id' class='search-term--input' data-taxonomy='$taxonomy'>";
         $html .= "<label for='$term->term_id' class='search-term--label'>$term->name</label>";
         $html .= "<span class='icon icon-chevron--right'></span>";
         $html .= "</div>";
     }

     if ($echo) {
         echo $html;
     } else {
         return $html;
     }
}

/**
 * Generate HTML to the 3rd column in popup
 *
 * @return array
 */
function bre_append_filtered_terms_to_popup() {
    $filtered_terms = bre_get_filtered_terms();

    $html = '';
    if ($filtered_terms) {
        foreach ($filtered_terms['parent'] as $parent) {
            $html .= "<div class='category-find--term'>";
            $html .= "<div class='category-find--parent'>$parent->name</div>";
            $html .= "<div class='category-find--childrens' data-search-parent-id='$parent->term_id'>";
                foreach ($filtered_terms['childrens'] as $children) {
                    if ($children->parent == $parent->term_id) {
                        $html .= "<div class='category-childrens--item'>";
                        $html .= "<span>$children->name</span>";
                        $html .= "<input type='hidden' name='{$parent->taxonomy}_id[]' value='$children->term_id'>";
                        $html .= "</div>";
                    }
                }
           $html .= "</div>";
           $html .= "</div>";
        }
    }

    return [
        'html' => $html,
        'parentCount' => count($filtered_terms['parent']),
        'childsCount' => count($filtered_terms['childrens'])
    ];
}

/**
 * Get filtered terms after open popup
 * 
 * @return array
 */
function bre_get_filtered_terms() {
    $find_childrens = [];
    $find_parents = [];

    if (isset($_POST['ids'])) {
        $find_childrens = get_terms(array(
            'taxonomy' =>  $_POST['taxonomy'],
            'include' => $_POST['ids'] ,
            'hide_empty' => false,
        ));

        $find_parents = bre_get_term_parents($find_childrens);
    }

    return [
        'parent' => $find_parents,
        'childrens' => $find_childrens
    ];
}

/**
 * Get searched terms to filters
 *
 * @return array
 */
function bre_get_searched_terms() {
    $search_taxonomies = [];

    if (isset($_GET['industries_id'])) {
        $search_taxonomies['industries'] = $_GET['industries_id'];
    }

    if (isset($_GET['locations_id'])) {
        $search_taxonomies['locations'] = $_GET['locations_id'];
    }

    if (isset($_GET['patterns_id'])) {
        $search_taxonomies['patterns'] = $_GET['patterns_id'];
    }

    $search_ids = [];
    if (! empty($search_taxonomies)) {
        foreach ($search_taxonomies as $taxonomy => $ids) {
            foreach ($ids as $id) {
                $search_ids[] = $id;
            }
        }
    }

    $searched_terms = [
        'industries' => [],
        'locations' => [],
        'patterns' => []
    ];

    if (! empty($search_taxonomies) && ! empty($search_ids)) {
        $find_terms = get_terms(array(
            'taxonomy' => array_keys($search_taxonomies),
            'include' => $search_ids,
            'hide_empty' => false,
        ));

        foreach ($find_terms as $find_term) {
            $searched_terms[$find_term->taxonomy][] = $find_term;
        }
    }

    return $searched_terms;
}

/**
 * Get filtered post types
 *
 * @param $post_type
 * @param bool $page
 * @return WP_Query
 */
function bre_get_filtered_types($post_type, $page = false) {
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => 9,
        'order' => $_GET['order'] ?? 'asc',
        's' => $_GET['text'] ?? '',
        'tax_query' => [
            'relation' => 'OR',
        ]
    );

    if ($page) {
        $args['paged'] = $page;
    }

    if (isset($_GET['industries_id'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'industries',
            'field' => 'id',
            'terms' => $_GET['industries_id'],
        ];
    }

    if (isset($_GET['locations_id'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'locations',
            'field' => 'id',
            'terms' => $_GET['locations_id'],
        ];
    }

    if (isset($_GET['patterns_id'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'patterns',
            'field' => 'id',
            'terms' => $_GET['patterns_id'],
        ];
    }

    if (isset($_GET['expert_id'])) {
        $args['post__in'] = $_GET['expert_id'];
    }

    if (isset($_GET['event_id'])) {
        $args['post__in'] = $_GET['event_id'];
    }

    if (isset($_GET['company_id'])) {
        $args['post__in'] = $_GET['company_id'];
    }

    return new WP_Query($args);
}

/**
 * Ajax get more posts
 */
add_action('wp_ajax_load_more_posts', 'bre_get_more_posts_template');
add_action('wp_ajax_nopriv_load_more_posts', 'bre_get_more_posts_template');
function bre_get_more_posts_template() {
    $type = $_GET['type'];
    $posts = bre_get_filtered_types($type, $_GET['page']);

    if ($type === 'locations') {
        bre_get_more_locations();
        wp_die();
    }

    $html ='';
    if ( $posts->have_posts() ) {
        while ( $posts->have_posts() ) {
            $posts->the_post();

            ob_start();
            get_template_part('template-parts/cards/' . bre_get_card_for_type($type));
            $template = ob_get_clean();

            $html .= "<div class='col-md-4 col-sm-6 col-12'>";
            $html .= $template;
            $html .= "</div>";
        }
    }

    echo $html;

    wp_die();
}

/**
 * Get location template on click load more button on locations page
 */
function bre_get_more_locations() {
    $locations = get_filtered_cities_with_regions();

    $html = '';
    foreach ($locations as $location) {
        ob_start();
        include get_stylesheet_directory() . '/template-parts/cards/location.php';
        $template = ob_get_clean();

        $html .= "<div class='col-md-4 col-sm-6 col-12'>";
        $html .= $template;
        $html .= "</div>";
    }

    echo $html;
}

/**
 * Get card for post type or taxonomy
 *
 * @param $type
 * @return string
 */
function bre_get_card_for_type($type) {
    switch ($type) {
        case 'speakers':
            return 'expert';
            break;
        case 'companies':
            return 'company';
            break;
        case 'events':
            return 'event';
            break;
        case 'locations':
            return 'location';
            break;
    }
}

/**
 * Breadcrumbs
 *
 * @param string $separator
 * @param string $home
 * @return bool
 */
function bre_the_breadcrumbs($separator = '<span class="breadcrumb-divider">></span>', $home = 'Главная') {
    global $post;
    $html = '<div id="breadcrumbs" class="breadcrumbs"><ul class="breadcrumbs-list">';
    $html .= '<li class="breadcrumb-list--item"><a class="breadcrumb-item--link" href="' . home_url() .'">' . $home . '</a>'.$separator . '</li>';

    if ( is_page() ) {
        if ($post->post_parent) {
            $parent_post = get_post($post->post_parent);
            $html .= '<li class="breadcrumb-list--item"><a class="breadcrumb-item--link" href="' . get_permalink( $parent_post ) . '">' . $parent_post->post_title . '</a>'.$separator.'</li>';
        }

        $html .= '<span class="breadcrumb-item--current">' . $post->post_title . '</span>';
    }

    // Rewrite global post on single, archive and taxonomies pages
    $post = get_queried_object();

    if ( is_tax() ) {
        $taxonomy = get_taxonomy( $post->taxonomy );
        $taxonomy_name = $taxonomy->name;

        // Taxonomy
        $html .= '<li class="breadcrumb-list--item"><a class="breadcrumb-item--link" href="' . home_url( pll_current_language() . '/' . $taxonomy_name ) . '">' .
            esc_html( $taxonomy->labels->name ) . '</a>' . $separator . '</li>';

        // Create a list of all the term's parents
        $parent = $post->parent;
        $parents = [];
        while ( $parent ):
            $parents[] = $parent;
            $parent = get_term( $parent, $taxonomy_name )->parent;
        endwhile;

        // For each parent, create a breadcrumb item
        if ( !empty( $parents ) ):
            foreach ( array_reverse( $parents ) as $parent ):
                $child_term = get_term( $parent, $taxonomy_name );
                $html .= '<li class="breadcrumb-list--item"><a class="breadcrumb-item--link" href=' . get_term_link( $child_term ) . '">' . $child_term->name . '</a>' . $separator . '</li>';
            endforeach;
        endif;

        // Child/current taxonomy
        $html .= '<span class="breadcrumb-item--current">' . $post->name . '</span>';
    }

    if ( is_single() ) {
        $post_type = get_post_type_object( get_post_type( $post ) );

        if ($post_type) {
            $html .= '<li class="breadcrumb-list--item"><a class="breadcrumb-item--link" href="' . home_url( pll_current_language() . '/' . $post_type->rewrite['slug'] ) . '">' .
                esc_html( $post_type->labels->name ) . '</a>' . $separator . '</li>';
        }

        $html .= '<span class="breadcrumb-item--current">' . $post->post_title . '</span>';
    }

    if ( is_archive() && ! is_tax()) {
        $html .= '<span class="breadcrumb-item--current">' . $post->labels->name . '</span>';
    }

    if ( is_home() || is_front_page() ) {
        return false;
    }

    $html .= '</ul></div>';

    echo $html;
}

// Acf options page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page('BrandsEmbassy');
}

/**
 * Get posts with count of any given post type in the term
 *
 * @param string $post_type
 * @param string $taxonomy
 * @param $term_id
 * @param int $post_per_page
 * @return WP_Query
 */
function bre_get_related_post_types($post_type, $taxonomy, $term_id, $post_per_page = -1) {
    $query = new WP_Query([
        'post_type' => $post_type,
        'posts_per_page' => $post_per_page,
        'tax_query' => [
            [
                'taxonomy' => $taxonomy,
                'field' => 'id',
                'terms' => $term_id
            ]
        ]
    ]);

    return $query;
}


/**
 * Get child terms for current post
 *
 * @param $post_id
 * @param $taxonomy
 * @param bool $amount | Set for return amount of child terms
 * @return array|bool
 */
function bre_get_post_child_terms( $post_id, $taxonomy, $amount = false) {
    $terms = get_the_terms($post_id, $taxonomy);

    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return false;
    }

    $child_terms = [];
    foreach ( $terms as $term ) {
        if ( $term->parent ) {
            $child_terms[] = $term;
        }
    }

    if ( empty( $child_terms ) ) {
        return false;
    }

    if ($amount) {
        $child_terms = array_slice($child_terms, 0, $amount, true);
    }

    return $child_terms;
}

/**
 * Generate url with query string for filter page
 *
 * @param $type
 * @param $taxonomy
 * @param $post_ids
 * @return string
 */
function bre_generate_filter_url($type, $taxonomy, $post_ids) {
    $url = pll_home_url() . $type . '?';
    if (! is_array($post_ids) || empty($post_ids)) {
        return $url;
    }

    $glue = $taxonomy . urlencode('[]') . '=';
    $query_string = $glue . implode('&' . $glue, $post_ids);

    return $url . $query_string;
}

/**
 * Show taxonomy carousel (for patterns and industries)
 *
 * @param $taxonomy
**/
function bre_the_tax_carousel($taxonomy) {
    ob_start();
    require_once get_stylesheet_directory() . '/template-parts/carousels/' . $taxonomy . '.php';
    $template = ob_get_clean();

    echo $template;
}

/**
 * Show main full carousel for post type
 *
 * @param $post_type
 * @param int $post_per_page
 */
function bre_the_post_type_carousel($post_type, $post_per_page = 16) {
    $terms = new WP_Query([
        'post_type' => $post_type,
        'posts_per_page' => $post_per_page
    ]);

    ob_start();
    get_template_part('template-parts/carousels/' . $post_type);
    $template = ob_get_clean();

    echo $template;
}

/**
 * Show experts carousel
 *
 * @param array $post_ids
 * @param int $post_per_page
 */
function bre_the_experts_carousel($post_ids = [], $post_per_page = 16) {
    $terms = new WP_Query([
        'post_type' => 'speakers',
        'posts_per_page' => $post_per_page,
        'post__in' => $post_ids
    ]);

    if ($post_ids) {
        $filter_url = bre_generate_filter_url('experts', 'expert_id', $post_ids);
    }

    ob_start();
    require_once get_stylesheet_directory() . '/template-parts/carousels/experts.php';
    $template = ob_get_clean();

    echo $template;
}

/**
 * Show events carousel
 *
 * @param array $post_ids
 * @param int $posts_per_page
 */
function bre_the_events_carousel($post_ids = [], $posts_per_page = 16) {
    $terms = new WP_Query([
        'post_type' => 'events',
        'post__in' => $post_ids,
        'posts_per_page' => $posts_per_page
    ]);

    if ($post_ids) {
        $filter_url = bre_generate_filter_url('events', 'event_id', $post_ids);
    }

    ob_start();
    require_once get_stylesheet_directory() . '/template-parts/carousels/events.php';
    $template = ob_get_clean();

    echo $template;
}

/**
 * Show companies carousel
 *
 * @param array $post_ids
 * @param int $posts_per_page
 */
function bre_the_companies_carousel($post_ids = [], $posts_per_page = 16) {
    $terms = new WP_Query([
        'post_type' => 'companies',
        'post__in' => $post_ids,
        'posts_per_page' => $posts_per_page
    ]);

    if ($post_ids) {
        $filter_url = bre_generate_filter_url('companies', 'company_id', $post_ids);
    }

    ob_start();
    require_once get_stylesheet_directory() . '/template-parts/carousels/companies.php';
    $template = ob_get_clean();

    echo $template;
}

/**
 * Show locations(regions) carousel
 *
 * @param array $term_ids
 * @return bool
 */
function bre_the_locations_carousel($term_ids = []) {
    $locations = bre_get_location_regions();

    if ( ! empty($term_ids) ) {
        $terms = get_terms([
            'include' => $term_ids,
            'fields' => 'ids'
        ]);

        $result = [];
        foreach ($locations as $location) {
            if (in_array($location->term_id, $terms)) {
                $result[] = $location;
            }
        }

        $locations = $result;

        if ( empty($locations) ) return false;

        $filter_url = bre_generate_filter_url('locations', 'locations_id', array_column($locations, 'term_id'));
    }

    ob_start();
    require_once get_stylesheet_directory() . '/template-parts/carousels/locations.php';
    $template = ob_get_clean();

    echo $template;
}

add_filter('acf/location/rule_types', 'acf_location_rules_types');
function acf_location_rules_types( $choices ) {
    $choices[ 'Формы' ][ 'taxonomy_term_child' ] = 'Таксономия (дочерняя)';
    return $choices;
}

add_filter('acf/location/rule_values/taxonomy_term_child', 'acf_location_rules_values_taxonomy_term_child');
function acf_location_rules_values_taxonomy_term_child( $choices ) {
    if ( $taxonomies = get_taxonomies( array(), 'objects' ) ) {
        foreach( $taxonomies as $taxonomy ) {
            $choices[ $taxonomy->name ] = sprintf( '%s (%s)', $taxonomy->label, $taxonomy->name );
        }
    }

    return $choices;
}

add_filter('acf/location/rule_match/taxonomy_term_child', 'acf_location_rules_match_taxonomy_term_child', 10, 3);
function acf_location_rules_match_taxonomy_term_child( $match, $rule, $options ) {

    // Apply for taxonomies and only to single term edit screen
    if ( ! isset( $options[ 'taxonomy' ] ) || ! isset( $_GET[ 'tag_ID' ] ) ) {
        return $match;
    }

    // Ensure that taxonomy matches the rule
    if ( ( $rule[ 'operator' ] === "==" ) && ( $rule[ 'value' ] !== $options[ 'taxonomy' ] ) ) {
        return $match;
    }
    elseif ( ( $rule[ 'operator' ] === "!=" ) && ( $rule[ 'value' ] === $options[ 'taxonomy' ] ) ) {
        return $match;
    }

    // Get the term and ensure it's valid
    $term = get_term( $_GET[ 'tag_ID' ], $rule[ 'value' ] );
    if ( ! is_a( $term, 'WP_Term' ) ) {
        return $match;
    }

    // Apply for those that have parent only
    $match = $term->parent ? true : false;

    return $match;
}

/**
 * Get taxonomy with childrens items and count childrens
 *
 * @param $taxonomy
 * @return array
 */
function bre_get_hierarchical_taxonomies($taxonomy) {
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'parent' => false
    ]);

    $hierarhical_taxonomies = [];
    foreach ($terms as $term) {
        $childrens = get_terms([
            'taxonomy' => $taxonomy,
            'parent' => $term->term_id,
            'hide_empty' => false
        ]);

        $hierarhical_taxonomies[] = [
            'parent' => $term,
            'childrens' => $childrens,
            'childrens_count' => count($childrens)
        ];
    }

    return $hierarhical_taxonomies;
}

// Add experts post type to print pdf
if( function_exists('set_pdf_print_support') ) {
    set_pdf_print_support(['speakers', 'companies']);
}

// Encode cyrillic url
function bre_encode_uri($url){
    $exp = "{[^0-9a-z_.!~*'();,/?:@&=+$#%\[\]-]}i";

    return preg_replace_callback($exp, function($m){
        return sprintf('%%%02X',ord($m[0]));
    }, $url);
}


// Generate image url with protocol and convert cyrillic symbols
/**
 * @param string $size
 * @param bool $image_url
 * @param bool $protocol | Need for tests on localhost
 * @return null
 */
function bre_the_post_thumbnail_url($size = 'full', $image_url = false, $protocol = false) {
    if (! $image_url) {
        $image_url = get_the_post_thumbnail_url(get_the_ID(), $size);
    }

    $image_path = bre_encode_uri($image_url);

    if ($protocol) {
        $protocol = is_ssl() ? 'https:' : 'http:';

        echo $protocol . $image_path;

        return null;
    }

    echo $image_path;
}

/**
 * Show load more button
 *
 * @param $type
 */
function bre_the_load_more_button($type) {
    echo '<div class="experts-load">';
    echo '<button type="button" id="loadMore" data-type="' . $type . '" class="load--button">' . __('Load more', 'brandsembassy') . '</button>';
    echo '</div>';
}

/**
 * Show founded posts
 *
 * @param $count
 * @param $label
 */
function bre_the_founded_posts($count, $label) {
    echo '<div class="posts-result">' . __('Search result:', 'brandsembassy');
    echo '<span class="posts-result--text"> ' . sprintf($label, $count) . '</span>';
    echo '</div>';
}

/**
 * Generate element id from cyrillic to latin, with concatenate and uppercase symbols
 *
 * @param $str | Cyrillic string
 * @return string
 */
function bre_generate_id_from_cyr($str) {
    $str = bre_transliterate($str);
    $str = ucwords($str);
    $str = str_replace(' ', '', $str);

    return lcfirst($str);
}

/**
 * Get locations childrens(cities) and grandchildren's
 *
 * @param bool $with_cities
 * @return array
 */
function bre_get_location_regions($with_cities = false) {
    $parents = get_terms([
        'taxonomy' => 'locations',
        'hide_empty' => false,
        'parent' => 0
    ]);

    $result = [];
    foreach ($parents as $parent) {
        $childrens = get_terms('locations', [
            'hide_empty' => 0,
            'child_of' => $parent->term_id,
        ]);

        foreach ($childrens as $children) {
            if ($with_cities) {
                if ($parent->term_id !== $children->parent) {
                    $children->main_parent = $parent->term_id;
                }

                $result[] = $children;
            } else {
                if ($parent->term_id !== $children->parent) {
                    $children->main_parent = $parent->term_id;
                    $result[] = $children;
                }
            }
        }
    }

    return $result;
}

/**
 * Get locations with cities for locations page template
 * Filters: id, order, page, text
 *
 * @return array
 */
function get_filtered_cities_with_regions() {
    $locations = bre_get_location_regions(true);

    if (isset($_GET['order'])) {
        $order = strtolower($_GET['order']);

        usort($locations, function($a, $b) use ($order) {
            if ($order == 'asc') {
                return strcmp($a->name, $b->name);
            } else {
                return strcmp($b->name, $a->name);
            }
        });
    }

    if (isset($_GET['locations_id'])) {
        $ids = $_GET['locations_id'];
        $locations = array_filter($locations, function ($item) use ($ids) {
            if (in_array($item->term_id, $ids) || in_array($item->parent, $ids)) {
                return $item;
            }
        });
    }

    if (isset($_GET['text'])) {
        $search_text = $_GET['text'];

        $matches = array();
        foreach($locations as $key => $item) {
            if (preg_match("/$search_text/ui", $item->name)) {
                $matches[] = $item;
            } elseif ($item->parent) {
                $parent = get_term($item->parent);

                if (preg_match("/$search_text/ui", $parent->name)) {
                    $matches[] = $item;
                }
            }
        }

        $locations = $matches;
    }

    if (isset($_GET['page'])) {
        if ($page = $_GET['page']) {
            $amount = 9;
            $offset = $page * $amount - $amount;
            $locations = array_slice($locations, $offset, $amount);
        }
    }

    return $locations;
}

/**
 * Get location(regions) related on locations
 *
 * @param array $terms | array location terms
 * @return array
 */
function bre_get_related_location_regions(array $terms) {
    $regions = bre_get_location_regions();
    $related_location_ids = array_column($terms, 'term_id');

    $result = [];
    foreach ($regions as $region) {
        if (in_array($region->parent, $related_location_ids)) {
            $result[] = $region;
        }
    }

    return $result;
}

/**
 * Get list of parents locations in hierarchy sort, e.g. [0 => 10231, 1=> 10246]
 *
 * @param $term
 * @return array
 */
function bre_get_parents_hierarchy_location($term) {
    $parent = $term->parent;
    $parents = [];
    while ( $parent ):
        $parents[] = $parent;
        $parent = get_term( $parent, 'locations' )->parent;
    endwhile;

    return array_reverse($parents, true);
}

/**
 * Return string hierarchy locations (parent, child), e.g. 'Russia, Moscow'
 *
 * @param $term
 * @return string
 */
function bre_the_list_hierarchy_locations($term) {
    $locations = bre_get_parents_hierarchy_location($term);

    $result = '';
    foreach($locations as $key => $location) {
        $location = get_term($location);

        $result .= count($locations) - 1 == $key
            ? $location->name . ', '
            : $location->name;
    }

    return $result;
}

/**
 * Get only children terms
 *
 * @param $taxonomy
 * @return array
 */
function bre_get_children_terms($taxonomy) {
    $terms = get_terms($taxonomy);

    $childrens = [];
    foreach($terms as $term) {
        if ($term->parent != 0) {
            $childrens[] = $term;
        }
    }

    return $childrens;
}

/**
 * Generate hidden inputs from global GET variable
 *
 * @param array $exclude
 * @return string
 */
function bre_generate_hidden_inputs($exclude = []){
    if (! isset($_GET)) return '';

    $result = '';
    foreach ($_GET as $name => $value) {
        if (in_array($name, $exclude)) {
            continue;
        }

        if (! is_array($value)) {
            $result .= '<input type="hidden" name="' . $name . '" value="'. $value . '">';
        } else {
            foreach ($value as $item) {
                $result .= '<input type="hidden" name="'. $name . '[]" value="' . $item . '">';
            }
        }
    }

    echo $result;
}
