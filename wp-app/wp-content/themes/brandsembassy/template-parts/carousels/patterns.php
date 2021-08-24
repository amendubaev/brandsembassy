<?php
    $patterns = get_terms([
        'taxonomy' => 'patterns',
        'hide_empty' => false,
        'parent' => 0
    ]);
?>

<section class="section_patterns">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-8">
                <h2 class="section--title"><a href="/patterns/"><?php echo __('Patterns', 'brandsembassy'); ?><span class='icon icon-arrow--right'></span></a></h2>
                <div class="section--description"><?php echo __('Patterns define the basic patterns of global business development.', 'brandsembassy'); ?></div>
            </div>
            <div class="col-md-4">
                <div class="carousel-controllers">
                    <div class="slider-counter" data-carousel="patterns">
                        <span class="slider-counter--current">1</span> / <span class="slider-counter--total"></span>
                    </div>
                    <div class="slick-arrows">
                        <div class="slick-arrow slick-prev" data-carousel="patterns"><span class="icon icon-arrow--prev"></span></div>
                        <div class="slick-arrow slick-next" data-carousel="patterns"><span class="icon icon-arrow--next"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="cards-slider" data-carousel="patterns">
            <?php foreach ($patterns as $key => $pattern) : $pattern_number = $key + 1; ?>
                <?php include get_stylesheet_directory() . '/template-parts/cards/pattern.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>