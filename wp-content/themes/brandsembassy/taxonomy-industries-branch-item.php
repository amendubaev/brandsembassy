<?php get_header(); ?>
<div class="page-wrapper branch-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <?php bre_the_breadcrumbs(); ?>
            </div>

            <div class="col-md-3">
                <div class="sort-icons">
                    <span class="icon icon-share"></span>
                </div>
            </div>
        </div>

        <?php $industry = get_queried_object(); ?>

        <div class="row justify-content-between">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-title"><?php echo $industry->name; ?></h1>
                        <h2><?php the_field('subtitle'); ?></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p><?php the_field('lead'); ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10">
                        <?php the_field('content'); ?>
                    </div>
                </div>
            </div>

            <?php $partners = get_field('branch_partner'); ?>
            <?php if ($partners) : ?>
                <?php
                    $partner = array_shift($partners);

                    // Socials
                    $social_network = get_field_object('branch_partner_social');
                    $social = $social_network['value'];
                    $social_name = $social_network['choices'][$social];
                    $social_url = get_field('expert_' . $social, $partner->ID);
                ?>
            <div class="col-md-4">
                <h2>Отраслевой партнер</h2>

                <div class="card-item expert">
                    <div style="border: 1px solid #ddd; border-radius: 10px;">
                    <?php
                        $post = $partner;
                        if ($partner && $partner->post_type === 'companies') {
                            get_template_part('template-parts/cards/company');

                            $social_url = get_field('expert_' . $social, get_field('attached_expert'));
                        } else {
                            get_template_part('template-parts/cards/expert');
                        }
                    ?>
                    </div>
                </div>

                <?php if ($social_url): ?>
                    <p><a href="<?php echo $social_url; ?>" class="card-link--blue">
                            <i class="far fa-globe"></i>&nbsp; <?php printf( __('%s in %s', 'brandsembassy'), $partner->post_title, $social_name ); ?> <i class="far fa-arrow-right"></i>
                        </a>
                    </p>
                <?php endif; ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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

<?php get_footer(); ?>