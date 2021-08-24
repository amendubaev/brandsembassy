<?php get_header(); ?>
    <div class="page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="page-title"><?php echo get_queried_object()->label; ?></h1>
                </div>
                <div class="col-md-4">
                    <?php
                        $placeholder = __('Search for events', 'brandsembassy');
                        require_once 'template-parts/blocks/search.php';
                    ?>
                </div>
            </div>
            <div class="filters-wrapper">
                <div class="row">
                    <div class="col-12 d-md-none d-block">
                        <div class="filter-button"><?php echo __('Show filters', 'brandsembassy'); ?> <span class="icon icon-arrow--down"></span></div>
                    </div>
                </div>
                <div class="filters d-md-block d-none">
                    <div class="row">
                        <?php
                            get_template_part('template-parts/blocks/industries-filter');

                            get_template_part('template-parts/blocks/locations-filter');

                            get_template_part('template-parts/blocks/patterns-filter');
                        ?>
                    </div>
                </div>
            </div>

            <div class="posts-list">
                <div class="header-posts">
                    <div class="row justify-content-between">
                        <div class="col-md-4 col-12">
                            <?php
                                require_once get_stylesheet_directory() . '/template-parts/blocks/sorting.php';
                            ?>
                        </div>
                        <div class="col-md-4 col-12">
                            <?php
                                $query = bre_get_filtered_types('events');
                                bre_the_founded_posts($query->found_posts, _n('%s event', '%s events', $query->found_posts, 'brandsembassy'));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="posts">
                    <div class="row">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <div class="col-md-4 col-sm-6 col-12">
                                <?php get_template_part('template-parts/cards/event'); ?>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>

                <?php if($query->have_posts() && $query->found_posts > 9) : ?>
                    <?php bre_the_load_more_button('events')?>
                <?php endif;?>
            </div>
        </div>

        <?php get_template_part('template-parts/modals/filter'); ?>
    </div>
<?php get_footer();