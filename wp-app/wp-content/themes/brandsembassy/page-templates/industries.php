<?php
/**
 * Template Name: Индустрии
 */
get_header(); ?>
<div class="page-wrapper">
    <?php
        global $post;

        $order = 'ASC';
        if ( isset( $_GET['order'] ) ) {
            $order = sanitize_text_field( $_GET['order'] );
        }

        $industries = get_terms([
            'taxonomy' => 'industries',
            'hide_empty' => false,
            'show_count' => true,
            'parent' => 0,
            'order' => $order
        ]);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php bre_the_breadcrumbs(); ?>
            </div>
        </div>
        <div class="row justify-content-between">
            <div class="col-md-6">
                <h1 class="page-title"><?php the_title(); ?> <sup><small><?php echo count($industries);?></small></sup></h1>
                <div class="page-description"><?php echo $post->post_content; ?></div>
            </div>
            <div class="col-md-3">
                <?php
                    $placeholder = __('Search for industry', 'brandsembassy');
                    require_once get_stylesheet_directory() . '/template-parts/blocks/search.php';
                ?>
            </div>
        </div>
        <div class="filter">
            <div class="row justify-content-between">
                <div class="col-8">
                    <?php
                        require_once get_stylesheet_directory() . '/template-parts/blocks/sorting.php';
                    ?>
                </div>
                <div class="col-4">
                    <div class="sort-icons">
                        <span class="icon icon-grid sort-active" data-view="grid"></span>
                        <span class="icon icon-list" data-view="list"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section-sortable--view grid-list d-block" data-view="grid">
        <div class="container">
            <div class="row">
                <?php foreach (array_chunk($industries, 3) as $chunk_industries): ?>
                    <?php foreach ($chunk_industries as $industry) : ?>
                        <div class="col-md-4 industry-item">
                            <?php include get_stylesheet_directory() . '/template-parts/cards/industry.php'; ?>
                        </div>
                    <?php endforeach; ?>
            <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section class="section-sortable--view grid-list d-none" data-view="list">
        <div class="container">
            <div class="row">
                <?php foreach (array_chunk($industries, 3) as $chunk_industries): ?>
                    <div class="col-md-4">
                        <ul>
                            <?php foreach ($chunk_industries as $industry) : ?>
                                <li class="branch-list--item">
                                    <a href="<?php echo $industry->slug; ?>"><?php echo $industry->name; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>
</div>
<?php get_footer(); ?>
