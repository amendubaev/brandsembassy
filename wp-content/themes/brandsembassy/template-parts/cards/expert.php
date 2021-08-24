<a class="card-item expert-item" href="<?php the_permalink(); ?>">
    <div class="card-item--content">
        <div class="card-item--icon" style="background-image: url(<?php the_post_thumbnail_url('full'); ?>)"></div>
        <div class="card-item--title"><?php the_title(); ?></div>
        <div class="card-item--subtitle"><?php echo get_field('expert_skills'); ?></div>
        <div class="card-item--meta">
            <?php bre_get_the_names_with_count(get_the_terms($post->ID, 'industries'), '', $link = false); ?>
        </div>
    </div>
</a>