<?php
    $event_expert = get_field('event_expert');
?>
<a class="card-item event-item" href="<?php the_permalink(); ?>">
    <?php if (has_post_thumbnail()) : ?>
        <div class="card-item--thumbnail">
            <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title(); ?>">
        </div>
    <?php endif; ?>
    <div class="card-item--content">
        <?php if ($event_expert) : ?>
            <div class="card-item--author">
                <?php if (has_post_thumbnail($event_expert)) : ?>
                    <div class="card-author--photo" style="background-image: url(<?php echo get_the_post_thumbnail_url($event_expert, 'full'); ?>);"></div>
                <?php endif; ?>
                <div class="card-author--content">
                    <div class="card-author--name"><?php echo get_the_title($event_expert); ?></div>
                    <div class="card-author--date"><?php echo get_the_date('d F Y', $post->ID); ?></div>
                </div>
            </div>
        <?php endif; ?>
        <div class="card-item--title"><?php the_title(); ?></div>
        <div class="card-item--meta">
            <?php $branches = bre_get_post_child_terms($post->ID, 'industries'); ?>
            <?php if ($branches) : ?>
                <?php foreach ($branches as $branch) : ?>
                    <span class="card-link--blue"><?php echo $branch->name ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</a>