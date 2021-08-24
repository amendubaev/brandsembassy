<?php
    $industries = get_terms([
        'taxonomy' => 'industries',
        'parent' => false
    ]);
?>

<section class="section_industries">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-8">
                <h2 class="section--title"><a href="/industries/"><?php echo __('Industries', 'brandsembassy'); ?><span class='icon icon-arrow--right'></span></a></h2>
                <div class="section--description"><?php echo __('Global areas of the economy that are responsible for the full cycle of production and marketing of goods and services of a related direction.', 'brandsembassy'); ?></div>
            </div>
            <div class="col-md-4">
                <div class="slider-controllers">
                    <div class="slider-counter" data-carousel="industries">
                        <span class="slider-counter--current">1</span> / <span class="slider-counter--total"></span>
                    </div>
                    <div class="slick-arrows">
                        <div class="slick-arrow slick-prev" data-carousel="industries"><span class="icon icon-arrow--prev"></span></div>
                        <div class="slick-arrow slick-next" data-carousel="industries"><span class="icon icon-arrow--next"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="cards-slider" data-carousel="industries">
            <?php foreach($industries as $industry) : ?>
                <?php include get_stylesheet_directory() . '/template-parts/cards/industry.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>