<?php
    $branches = get_terms([
        'taxonomy' => $industry->taxonomy,
        'parent' => $industry->term_id,
        'hide_empty' => false
    ]);

    $branches_amount = count($branches);
    $branches_offset = 4;
?>

<a class="card-item card-hover industry-item" href="<?php echo get_term_link(pll_get_term($industry->term_id)); ?>">
    <div class="card-item--content">
        <div class="card-item--icon" style="background-image: url(<?php echo get_field('category_image', $industry); ?>);"></div>
        <div class="card-item--title"><?php echo $industry->name; ?></div>
        <?php if ($branches) : ?>
            <div class="card-item--tags">
                <span class="card-tags--count"><?php printf( _n('%s branch', '%s branches', $branches_amount, 'brandsembassy'), $branches_amount); ?></span>
                <ul class="tag-items">
                    <?php foreach (array_slice($branches, 0, $branches_offset) as $branch) : ?>
                        <li class="tag-item"><?php echo $branch->name; ?></li>
                    <?php endforeach; ?>
                    <li class="tag-item tag-item--count"><?php printf( _n('and %s more branch', 'and %s more branches', $branches_amount - $branches_offset, 'brandsembassy'), $branches_amount - $branches_offset); ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card-item--meta">
            <span class="card-items--count">
                <?php printf( _n('%s expert', '%s experts', bre_get_related_post_types('speakers', $industry->taxonomy, $industry->term_id)->found_posts, 'brandsembassy') , bre_get_related_post_types('speakers', $industry->taxonomy, $industry->term_id)->found_posts); ?>
            </span>
            <span class="card-items--count">
                <?php printf( _n('%s company', '%s companies', bre_get_related_post_types('companies', $industry->taxonomy, $industry->term_id)->found_posts, 'brandsembassy') , bre_get_related_post_types('companies', $industry->taxonomy, $industry->term_id)->found_posts); ?>
            </span>
        </div>
        <div class="card-item--controllers d-block d-md-none"><div class="card-controller--dot"></div></div>
    </div>
</a>