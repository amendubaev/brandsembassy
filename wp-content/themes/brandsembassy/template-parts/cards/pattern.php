<?php
    $megatrends = get_terms([
        'taxonomy' => $pattern->taxonomy,
        'parent' => $pattern->term_id,
        'hide_empty' => false
    ]);

    $megatrends_amount = count($megatrends);
    $sliced_megatrends = array_slice($megatrends, 0, 4);
    $remaining_magatrends = $megatrends_amount - count($sliced_megatrends);
?>

<a class="card-item card-hover pattern-item" href="<?php echo get_term_link(pll_get_term($pattern->term_id)); ?>">
    <div class="card-item--content">
        <div class="card-item--counter"><?php echo str_pad($pattern_number, 2, '0', STR_PAD_LEFT);; ?></div>
        <div class="card-item--title"><?php echo $pattern->name; ?></div>
        <div class="card-item--tags">
            <span class="card-tags--count"><?php printf( _n('%s megatrend', '%s megatrends', $megatrends_amount, 'brandsembassy'), $megatrends_amount ); ?></span>
            <ul class="tag-items">
                <?php foreach( $sliced_megatrends as $megatrend ) : ?>
                    <li class="tag-item"><?php echo $megatrend->name; ?></li>
                <?php endforeach; ?>

                <?php if($remaining_magatrends > 0) : ?>
                    <li class="tag-item tag-item--count"><?php printf( _n('and %s more megatrend', 'and %s megatrends', $remaining_magatrends, 'brandsembassy'), $remaining_magatrends ); ?></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-item--controllers d-block d-md-none"><div class="card-controller--dot"></div></div>
    </div>
    <div class="card-item--image" style="background-image: url(<?php the_field('category_image', $pattern->taxonomy . '_' . $pattern->term_id); ?>);"></div>
</a>
