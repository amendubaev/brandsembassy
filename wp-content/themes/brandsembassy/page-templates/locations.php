<?php
/*
	Template Name: Локации
*/
get_header(); ?>
    <div class="page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </div>
                <div class="col-md-4">
                    <?php
                        $placeholder = __('Search for location', 'brandsembassy');
                        require_once  get_stylesheet_directory() . '/template-parts/blocks/search.php';
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
                            get_template_part('template-parts/blocks/locations-filter');
                        ?>
                    </div>
                </div>
            </div>

            <div class="posts-list">
                <div class="header-posts">
                    <div class="row justify-content-between">
                        <div class="col-md-4 col-sm-6 col-12">
                            <?php require_once get_stylesheet_directory() . '/template-parts/blocks/sorting.php'; ?>
                        </div>
                        <div class="col-md-4 col-sm-6 d-none d-sm-block">
                            <?php
                                $locations = get_filtered_cities_with_regions();
                                $locations_amount = count($locations);
                                bre_the_founded_posts($locations_amount, _n('%s location', '%s locations', $locations_amount, 'brandsembassy'));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="posts">
                    <div class="row">
                        <?php foreach (array_slice($locations, 0, 9) as $location) : ?>
                            <div class="col-md-4 col-sm-6 col-12">
                                <?php include get_stylesheet_directory() . '/template-parts/cards/location.php'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if($locations && $locations_amount > 9) : ?>
                    <?php bre_the_load_more_button('locations')?>
                <?php endif;?>
            </div>
        </div>

        <?php get_template_part('template-parts/modals/filter'); ?>
    </div>
<?php get_footer();