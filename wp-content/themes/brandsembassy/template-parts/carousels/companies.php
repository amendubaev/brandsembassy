<?php
    global $post;
    if ( ! isset( $terms ) ) {
        $terms = new WP_Query([
            'post_type' => 'companies',
            'posts_per_page' => 32,
        ]);
    }

    if ( ! isset( $filter_url ) ) {
        $title = "<h2 class='section--title'><a href=" . '/companies/' . ">" . __('Companies', 'brandsembassy') . "<span class='icon icon-arrow--right'></span></a></h2>";
    } else {
        $title = "<h2 class='section--title'><a href='$filter_url'>" . __('Companies', 'brandsembassy') . " <sup><small>$terms->found_posts</small></sup><span class='icon icon-arrow--right'></span></a></h2>";
    }
?>

<section class="section_companies">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-8">
                <?php echo $title; ?>
            </div>
            <div class="col-md-4">
                <div class="slider-controllers">
                    <div class="slider-counter" data-carousel="companies">
                        <span class="slider-counter--current">1</span> / <span class="slider-counter--total"></span>
                    </div>
                    <div class="slick-arrows">
                        <div class="slick-arrow slick-prev" data-carousel="companies"><span class="icon icon-arrow--prev"></span></div>
                        <div class="slick-arrow slick-next" data-carousel="companies"><span class="icon icon-arrow--next"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="cards-slider" data-carousel="companies">
            <?php while ($terms->have_posts()) : $terms->the_post(); ?>
                <?php get_template_part('template-parts/cards/company'); ?>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    </div>
</section>