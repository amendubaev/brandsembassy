<?php get_header(); ?>

<div class="page-wrapper branch-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php bre_the_breadcrumbs(); ?>
            </div>
        </div>

        <?php $pattern = get_queried_object(); ?>

        <div class="row justify-content-between">
            <div class="col-md-6">
                <h1 class="page-title"><?php echo $pattern->name; ?></h1>
            </div>
            <div class="col-md-3">
                <div class="sort-icons">
                    <span class="icon icon-share"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <?php $benefit_number = 0; ?>
            <?php if( have_rows('benefits') ): ?>
                <?php while ( have_rows('benefits') ) : the_row(); $benefit_number++; ?>
                    <div class="col-md-4 col-sm-6 col-12">
                        <?php include 'template-parts/cards/megatrend-benefit.php'; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
    $events = bre_get_related_post_types('events', $pattern->taxonomy, $pattern->term_id);
    $events->found_posts ? bre_the_events_carousel(array_column($events->posts, 'ID')) : '';
?>

<?php get_footer(); ?>