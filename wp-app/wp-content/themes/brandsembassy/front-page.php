<?php get_header(); ?>

<div class="page-wrapper">
    <section class="section_home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-12 order-1 order-sm-0">
                    <h1 class="section_home--title"><?php echo __('Synchronization of brand interests with partners and customers anywhere in the world.', 'brandsembassy'); ?></h1>
                    <p class="section_home--description"><?php echo __('Global areas of the economy that are responsible for the full cycle of production and marketing of goods and services of a related direction.', 'brandsembassy'); ?></p>
                    <?php get_template_part('template-parts/modals/search'); ?>
                </div>
                <div class="col-md-6 col-12 order-0 order-sm-1">
                    <div class="home-promo">
				        <img class="home-promo--logo" src="<?php echo get_template_directory_uri() . '/assets/img/logo.svg'; ?>" alt="Brands Embassy">
                        <div class="button button-primary button-popup" data-popup="video-promo">
                            <?php echo __('Know more about ISBP', 'brandsembassy'); ?> <i class="far fa-play"></i>
                        </div>
                        <div class="popup" data-popup="video-promo">
                            <div class="popup-container">
                            <video controls>
                                <source src="<?php echo get_template_directory_uri() . '/assets/video/introduction(ru).mp4'; ?>" type="video/mp4">
                            </video>
                            </div>
                            <div class="popup-close"><?php echo __('Close', 'brandsembassy'); ?> <span class="icon icon-close"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php bre_the_tax_carousel('patterns'); ?>

    <?php bre_the_tax_carousel('industries'); ?>

    <?php bre_the_experts_carousel(); ?>

    <?php bre_the_events_carousel([], 6); ?>

    <?php bre_the_locations_carousel(); ?>

    <?php bre_the_companies_carousel(); ?>
</div>

<?php get_footer(); ?>