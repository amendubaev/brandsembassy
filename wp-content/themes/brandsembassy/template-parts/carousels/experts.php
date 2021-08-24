<?php
    global $post;

    if ( ! isset( $terms ) ) {
        $terms = new WP_Query([
            'post_type' => 'speakers',
            'posts_per_page' => 120,
        ]);
    }

    if ( ! isset( $filter_url ) ) {
        $title = "<h2 class='section--title'><a href='/experts/'>" . __('Experts', 'brandsembassy') . "<span class='icon icon-arrow--right'></span></a></h2>
                <div class='section--description'>" . __('Persons with knowledge, experience and abilities that have received public recognition as a result of their activities.', 'brandsembassy') . "</div>";
    } else {
        $title = "<h2 class='section--title'><a href='$filter_url'>" . __('Related Experts', 'brandsembassy') . "<sup><small>$terms->found_posts</small></sup><span class='icon icon-arrow--right'></span></a></h2>";
    }
?>

<section class="section_experts">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-8">
                <?php echo $title; ?>
            </div>
            <div class="col-md-4">
                <div class="slider-controllers">
                    <div class="slider-counter" data-carousel="experts">
                        <span class="slider-counter--current">1</span> / <span class="slider-counter--total"></span>
                    </div>
                    <div class="slick-arrows">
                        <div class="slick-arrow slick-prev" data-carousel="experts"><span class="icon icon-arrow--prev"></span></div>
                        <div class="slick-arrow slick-next" data-carousel="experts"><span class="icon icon-arrow--next"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="cards-slider" data-carousel="experts">
            <?php while ($terms->have_posts()) : $terms->the_post(); ?>
                <?php get_template_part('template-parts/cards/expert'); ?>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    </div>
</section>