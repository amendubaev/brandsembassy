<a class="card-item megatrend-item" href="<?php echo get_term_link(pll_get_term($megatrend->term_id)); ?>">
    <div class="card-item--content">
        <div class="card-item--counter"><?php echo str_pad($megatrend_number, 2, '0', STR_PAD_LEFT); ?></div>
        <div class="card-item--title"><?php echo $megatrend->name; ?></div>
        <div class="card-item--description">
            <?php echo term_description( $megatrend->term_id ); ?>
        </div>
        <div class="card-item--meta">
            <span class="card-items--count">
                <?php printf( _n('%s expert', '%s experts', bre_get_related_post_types('speakers', $megatrend->taxonomy, $megatrend->term_id)->found_posts, 'brandsembassy') , bre_get_related_post_types('speakers', $megatrend->taxonomy, $megatrend->term_id)->found_posts); ?>
            </span>
            <span class="card-items--count">
                <?php printf( _n('%s event', '%s events', bre_get_related_post_types('events', $megatrend->taxonomy, $megatrend->term_id)->found_posts, 'brandsembassy') , bre_get_related_post_types('events', $megatrend->taxonomy, $megatrend->term_id)->found_posts); ?>
            </span>
        </div>
    </div>
</a>