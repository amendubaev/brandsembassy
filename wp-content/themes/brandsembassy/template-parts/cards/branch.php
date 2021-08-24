<a class="card-item branch-item" href="<?php echo get_term_link(pll_get_term($branch->term_id)); ?>">
    <div class="card-item--content">
        <div class="card-item--counter"><?php echo str_pad($branch_number, 2, '0', STR_PAD_LEFT); ?></div>
        <div class="card-item--title"><?php echo $branch->name; ?></div>
        <div class="card-item--meta">
            <span class="card-items--count">
                <?php printf( _n('%s expert', '%s experts', bre_get_related_post_types('speakers', $branch->taxonomy, $branch->term_id)->found_posts, 'brandsembassy') , bre_get_related_post_types('speakers', $branch->taxonomy, $branch->term_id)->found_posts); ?>
            </span>
            <span class="card-items--count">
                <?php printf( _n('%s company', '%s companies', bre_get_related_post_types('companies', $branch->taxonomy, $branch->term_id)->found_posts, 'brandsembassy') , bre_get_related_post_types('companies', $branch->taxonomy, $branch->term_id)->found_posts); ?>
            </span>
        </div>
    </div>
</a>