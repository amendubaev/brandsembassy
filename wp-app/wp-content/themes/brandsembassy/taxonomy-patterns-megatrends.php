<?php get_header(); ?>
<div class="page-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php bre_the_breadcrumbs(); ?>
            </div>
        </div>
        <?php
            $pattern = get_queried_object();
            $megatrends = get_terms([
                'taxonomy' => $pattern->taxonomy,
                'parent' => $pattern->term_id,
                'hide_empty' => false
            ]);
        ?>
        <div class="row justify-content-between">
            <div class="col-md-6">
                <h1 class="page-title"><?php echo $pattern->name; ?> <sup><small><?php echo count($megatrends); ?></small></sup></h1>
                <div class="page-description"><?php echo get_field('description', $pattern->taxonomy . '_' . $pattern->term_id); ?></div>
            </div>
        </div>
    </div>

    <section class="section_megatrends">
        <div class="container">
            <div class="row">
                <?php $megatrend_number = 1; ?>
                <?php foreach (array_chunk($megatrends, 3) as $chunk_megatrends): ?>
                    <?php foreach ($chunk_megatrends as $megatrend) : ?>
                        <div class="col-md-4">
                            <?php include 'template-parts/cards/megatrend.php'; ?>
                        </div>
                        <?php $megatrend_number++; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</div>
<?php get_footer(); ?>