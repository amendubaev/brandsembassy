<?php get_header(); ?>
    <?php if (get_field('event_header_image')) : ?>
        <div class="header-post--background" style="background-image: url(<?php the_field('event_header_image'); ?>"></div>
    <?php endif; ?>

    <div class="page-wrapper post-wrapper">
        <!-- Breadcrumbs -->
        <div class="container">
            <div class="row">
                <div class="col-md-11">
                    <?php bre_the_breadcrumbs(); ?>
                </div>

                <div class="col-md-1">
                    <?php require_once 'template-parts/blocks/share.php'?>
                </div>
            </div>

            <!-- Experts -->
            <div class="elements-attached">
                <div class="row justify-content-center justify-content-md-start">
                    <?php if( $experts = get_field('event_experts') ):
                        $event_experts = bre_get_sliced_items($experts);

                        foreach ( $event_experts['sliced'] as $event_expert ) : ?>
                            <div class="col-md-3 col-sm-6 col-12">
                                <a href="<?php echo get_permalink($event_expert->ID); ?>">
                                    <div class="card-item--author">
                                        <?php if (has_post_thumbnail($event_expert->ID)) : ?>
                                            <div class="card-author--photo" style="background-image: url(<?php echo get_the_post_thumbnail_url($event_expert->ID, 'full'); ?>);"></div>
                                        <?php endif; ?>
                                        <div class="card-author--content">
                                            <div class="card-author--name"><?php echo get_the_title($event_expert->ID); ?></div>
                                            <div class="card-author--date"><?php echo get_field('expert_skills', $event_expert->ID); ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>

                        <?php if( $event_experts['main_amount'] > 3) : ?>
                            <div class="col-md-3 col-sm-6 col-12">
                                <span class="card-link--blue"><?php printf( _n('and %s more expert', 'and %s more experts', $event_experts['sliced_amount'], 'brandsembassy'), $event_experts['sliced_amount'] ); ?></span>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>

            <!-- Megatrends -->
            <div class="elements-attached">
                <?php if ($megatrends = bre_get_post_child_terms($post->ID, 'patterns')): $event_megatrends = bre_get_sliced_items($megatrends, 4); ?>
                    <?php foreach ($event_megatrends['sliced'] as $megatrend) : ?>
                        <span class="card-author--date"><?php echo $megatrend->name; ?></span>
                    <?php endforeach; ?>

                    <?php if( $event_megatrends['main_amount'] > 3) : ?>
                        <span class="card-link--blue">и еще <?php echo $event_megatrends['sliced_amount']; ?> мегатрендов</span>
                    <?php endif; ?>

                <?php endif; ?>
            </div>

            <!-- Sidebar content -->
            <div class="row">
                <div class="col-md-8 col-12">
                    <h1 class="title"><?php the_title(); ?></h1>

                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; endif; ?>
                </div>

                <div class="col-md-4 col-12">
                <?php $locations = get_the_terms($post->ID, 'locations'); ?>
                    <?php if ($locations[0]) : ?>
                        <div class="address" style="border: 1px solid #838c9b; border-radius: 10px; padding: 25px">
                            <?php
                                $location = $locations[0];
                                $location_post_id = $location->taxonomy . '_' . $location->term_id;
                            ?>

                            <h3><b>"<?php echo $location->name; ?>"</b></h3>
                            <br>
                            <p><?php echo bre_the_list_hierarchy_locations($location); ?></p>

                            <?php if($location_address = get_field('location_address', $location_post_id)) : ?>
                                <p><?php echo $location_address; ?></p>
                            <?php endif; ?>

                            <?php if($location_email = get_field('location_email', $location_post_id)) : ?>
                                <p>Тел. <?php echo $location_email; ?></p>
                            <?php endif; ?>

                            <?php if($location_site = get_field('location_site', $location_post_id)) : ?>
                                <p>Сайт: <?php echo $location_site; ?></p>
                            <?php endif; ?>

                            <?php if($location_phone = get_field('location_phone', $location_post_id)) : ?>
                                <p>E-mail: <?php echo $location_phone; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- TODO: need to clarify, set expert/companies comment -->
                    <div class="set-opinion"></div>

                    <!-- TODO: need to clarify, show experts/companies comment -->
                    <div class="experts-opinions"></div>
                </div>
            </div>
        </div>
    </div>

    <!--   Carousels block     -->
    <div class="attached-posts">
        <?php isset($event_experts) ? bre_the_experts_carousel($event_experts['ids']) : ''; ?>

        <?php
//        $events = bre_get_related_post_types('events', $industry->taxonomy, $industry->term_id);
//        $events->found_posts ? bre_the_events_carousel(array_column($events->posts, 'ID')) : '';
        ?>

        <?php
//        $related_locations = wp_get_object_terms( $expert_ids,  'locations' );
//        $locations = bre_get_related_location_regions($related_locations);
//
//        if ($locations) {
//            $filter_url = bre_generate_filter_url('locations', $industry->taxonomy, array_column($locations, 'term_id'));
//            require_once 'template-parts/carousels/locations.php';
//        }
        ?>

        <?php
//        $companies = bre_get_related_post_types('companies', $industry->taxonomy, $industry->term_id);
//        $companies-> found_posts ? bre_the_companies_carousel(array_column($companies->posts, 'ID')) : '';
        ?>

    </div>
<?php get_footer(); ?>