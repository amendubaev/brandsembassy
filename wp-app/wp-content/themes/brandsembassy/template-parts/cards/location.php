<?php
    $location_background = get_field('category_image', $location->taxonomy . '_' . $location->term_id);
    $location_description = term_description($location->term_id, $location->taxonomy);

    $industries = wp_get_object_terms(
            array_column( bre_get_related_post_types('speakers', $location->taxonomy, $location->term_id)->posts, 'ID'), 'industries'
    );

    $branches = array_filter($industries, function($industry) {
        if ($industry->parent) {
            return $industry;
        }
    });
?>

<a class="card-item location-item" href="<?php echo get_term_link(pll_get_term($location->term_id)); ?>">
    <div class="card-item--content">
        <div class="card-item--locations">
            <?php if(isset($location->main_parent)) : ?>
                <span><?php echo get_term( $location->main_parent )->name; ?></span>, &nbsp;
            <?php endif; ?>
            <span><?php echo get_term( $location->parent )->name; ?></span>
        </div>
        <div class="card-item--data">
            <div class="card-item--title">
                <?php echo $location->name; ?>
            </div>
            <div class="card-item--tags">
                <?php printf( _n('%s branch', '%s branches', count($branches), 'brandsembassy'), count($branches)); ?>
            </div>

            <?php if($location_description) : ?>
                <div class="card-item--description"><?php echo $location_description; ?></div>
            <?php endif; ?>
        </div>
        <div class="card-item--meta">
            <span class="card-items--count">
                <?php printf( _n('%s expert', '%s experts', bre_get_related_post_types('speakers', $location->taxonomy, $location->term_id)->found_posts, 'brandsembassy') , bre_get_related_post_types('speakers', $location->taxonomy, $location->term_id)->found_posts); ?>
            </span>
            <span class="card-items--count">
                <?php printf( _n('%s company', '%s companies', bre_get_related_post_types('companies', $location->taxonomy, $location->term_id)->found_posts, 'brandsembassy') , bre_get_related_post_types('companies', $location->taxonomy, $location->term_id)->found_posts); ?>
            </span>
        </div>
        <div class="card-item--controllers d-block d-md-none"><div class="card-controller--dot"></div></div>
    </div>
    <div class="card-item--background" style="background-image: url(<?php echo $location_background; ?>);"></div>
</a>