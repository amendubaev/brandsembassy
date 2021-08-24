<?php
/**
 * Template Name: Паттерны
 */
get_header(); ?>
<div class="page-wrapper">
    <?php
        global $post;

        $patterns = get_terms([
            'taxonomy' => 'patterns',
            'hide_empty' => false,
            'show_count' => true,
            'parent' => 0
        ]);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php bre_the_breadcrumbs(); ?>
            </div>
        </div>
        <div class="row justify-content-between">
            <div class="col-5">
                <h1 class="page-title"><?php the_title(); ?> <sup><small><?php echo count($patterns);?></small></sup></h1>
                <div class="page-description"><?php echo $post->post_content; ?></div>
            </div>
            <div class="col-7">
                <div class="sort-icons">
                    <span class="icon icon-grid sort-active" data-view="grid"></span>
                    <span class="icon icon-list" data-view="list"></span>
                </div>
            </div>
        </div>
    </div>
    <section class="section-sortable--view grid-list d-block" data-view="grid">
        <div class="container">
            <?php $pattern_number = 1; ?>
            <?php foreach (array_chunk($patterns, 3) as $chunk_patterns): ?>
                <div class="row">
                    <?php foreach ($chunk_patterns as $pattern) : ?>
                        <div class="col-md-4">
                            <?php include get_stylesheet_directory() . '/template-parts/cards/pattern.php'; ?>
                        </div>
                        <?php $pattern_number++; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="section-sortable--view grid-list d-none" data-view="list">
        <div class="container">
            <div class="row">
                <?php foreach (array_chunk($patterns, 3) as $chunk_patterns): ?>
                    <div class="col-md-4">
                        <ul>
                            <?php foreach ($chunk_patterns as $pattern) : ?>
                                <li class="branch-list--item">
                                    <a href="<?php echo $pattern->slug; ?>"><?php echo $pattern->name; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>
</div>
<?php get_footer(); ?>