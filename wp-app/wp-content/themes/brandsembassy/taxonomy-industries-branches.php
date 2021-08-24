<?php get_header(); ?>

<div class="page-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php bre_the_breadcrumbs(); ?>
            </div>
        </div>
        <?php $industry = get_queried_object();
            $branches = get_terms([
                'taxonomy' => $industry->taxonomy,
                'parent' => $industry->term_id,
                'hide_empty' => false
            ]);
        ?>
        <div class="row justify-content-between">
            <div class="col-md-6 col-8">
                <h1 class="page-title"><?php echo $industry->name; ?> <sup><small><?php echo count($branches); ?></small></sup></h1>
            </div>
            <div class="col-4">
                <div class="sort-icons">
                    <span class="icon icon-grid sort-active" data-view="grid"></span>
                    <span class="icon icon-list" data-view="list"></span>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="taxonomy-description">
                    <?php echo $industry->description; ?>
                </div>
            </div>
        </div>
    </div>

    <section class="section-branches section-sortable--view grid-list d-block" data-view="grid">
        <div class="container">
            <div class="row">
                <?php $branch_number = 1; ?>
                <?php foreach (array_chunk($branches, 3) as $chunk_branches): ?>
                    <?php foreach ($chunk_branches as $branch) : ?>
                        <div class="col-md-4">
                            <?php include 'template-parts/cards/branch.php'; ?>
                        </div>
                        <?php $branch_number++; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section class="section-branches section-sortable--view grid-list d-none" data-view="list">
        <div class="container">
            <div class="row">
                <?php $branches_rows = ceil(count($branches) / 3);
                $branches_lists  = array_chunk($branches, $branches_rows);
                foreach ( $branches_lists as $branches_column) : ?>
                    <div class="col-md-4">
                        <ul>
                            <?php foreach ($branches_column as $branch_item) : ?>
                                <li class="branch-list--item">
                                    <a href="<?php echo $branch_item->slug; ?>"><?php echo $branch_item->name; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php
        $experts = bre_get_related_post_types('speakers', $industry->taxonomy, $industry->term_id);
        $expert_ids = array_column($experts->posts, 'ID');

        $expert_ids ? bre_the_experts_carousel($expert_ids) : '';
    ?>

    <?php
        $events = bre_get_related_post_types('events', $industry->taxonomy, $industry->term_id);
        $events->found_posts ? bre_the_events_carousel(array_column($events->posts, 'ID')) : '';
    ?>

    <?php
        $related_locations = wp_get_object_terms( $expert_ids,  'locations' );
        $locations = bre_get_related_location_regions($related_locations);

        if ($locations) {
            $filter_url = bre_generate_filter_url('locations', $industry->taxonomy, array_column($locations, 'term_id'));
            require_once 'template-parts/carousels/locations.php';
        }
    ?>

    <?php
        $companies = bre_get_related_post_types('companies', $industry->taxonomy, $industry->term_id);

        $companies-> found_posts ? bre_the_companies_carousel(array_column($companies->posts, 'ID')) : '';
    ?>
</div>

<?php get_footer(); ?>