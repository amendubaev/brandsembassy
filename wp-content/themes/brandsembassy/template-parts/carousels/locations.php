<?php
    if (! isset($locations)) {
        $locations = bre_get_location_regions();
    }

    if ( ! isset( $filter_url ) ) {
        $title = "<h2 class='section--title'><a href='/locations/'>" . __('Locations', 'brandsembassy') . "<span class='icon icon-arrow--right'></span></a></h2>";
        $title .= "<div class='section--description'>" . __('Countries, megacities and ecosystems where trends appear are concentrated experts and companies that influence changes in industries.', 'brandsembassy') . "</div>";
    } else {
        $title = "<h2 class='section--title'><a href='$filter_url'>" . __('Locations', 'brandsembassy') . " <sup><small>" . count($locations) . "</small></sup><span class='icon icon-arrow--right'></span></a></h2>";
    }
?>

<section class="section_locations">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-8">
                <?php echo $title; ?>
            </div>
            <div class="col-md-4">
                <div class="slider-controllers">
                    <div class="slider-counter" data-carousel="locations">
                        <span class="slider-counter--current">1</span> / <span class="slider-counter--total"></span>
                    </div>
                    <div class="slick-arrows">
                        <div class="slick-arrow slick-prev" data-carousel="locations"><span class="icon icon-arrow--prev"></span></div>
                        <div class="slick-arrow slick-next" data-carousel="locations"><span class="icon icon-arrow--next"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="cards-slider" data-carousel="locations">
            <?php foreach($locations as $location) : ?>
                <?php include get_stylesheet_directory() . '/template-parts/cards/location.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>