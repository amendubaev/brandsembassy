<?php get_header(); ?>
    <div class="page-wrapper post-wrapper company">
        <?php
            $expert_socials = array_filter([
                'facebook' => get_field('facebook'),
                'linkedin' => get_field('linkedin'),
                'instagram' => get_field('instagram'),
                'twitter' => get_field('twitter'),
                'youtube' => get_field('youtube'),
                'vk' => get_field('vk')
            ]);

            $expert_descriptions = array_filter([
                __('Деятельность', 'brandsembassy') => get_field('activity'),
                __('Коллектив', 'brandsembassy') => get_field('team'),
                __('Проекты', 'brandsembassy') => get_field('projects'),
                __('Факты и события', 'brandsembassy') => get_field('facts')
            ]);
        ?>

        <div class="post-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="post-header--actions d-md-none">
							<div class="post-action">
								<a href="<?php echo get_permalink() . 'pdf'; ?>"><span class="icon icon-export"></span></a>
							</div>
							<div class="post-action"><span class="icon icon-share"></span></div>
						</div>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-header--photo" style="background-image: url(<?php the_post_thumbnail_url('full'); ?>"></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9 col-12">
						<div class="post-header--content">
                            <h1 class="post-header--name"><?php the_title(); ?></h1>
                            <h2 class="post-header--subtitle"><?php echo get_post_field('post_content', $post->ID); ?></h2>
                            <div class="post-header--categories">
                                <?php
                                    $industries = bre_get_post_child_terms(get_the_ID(), 'industries');
                                ?>
                                <?php if ($industries) : ?>
                                    <?php $industries_offset = array_slice($industries, 0, 3);
                                    foreach ($industries_offset as $industry) : ?>
                                        <a class="post-category--item" href="<?php echo get_term_link($industry->term_id, 'industries'); ?>" target="_blank">
                                            <?php echo (next($industries_offset) ) ? $industry->name . ',&nbsp;' : $industry->name; ?>
                                        </a>
                                    <?php endforeach; ?>
                                    <?php if($industry_amount = count($industries) - count($industries_offset)) :?>
                                        <span class="card-link--blue">и еще <?php echo $industry_amount; ?> отраслей</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="elements-attached">
                                <div class="row justify-content-center justify-content-md-start">
                                    <?php
                                        // Attached expert
                                        $attached_expert = get_field('attached_expert');

                                        // Array relation expert ids
                                        $expert_ids = [];
                                    ?>

                                    <?php if ($attached_expert) : $expert_ids[] = $attached_expert->ID; ?>
                                        <div class="col-md-4 col-10">
                                            <a href="<?php echo get_permalink($attached_expert->ID); ?>">
                                                <div class="card-item--author">
                                                    <?php if (has_post_thumbnail($attached_expert->ID)) : ?>
                                                        <div class="card-author--photo" style="background-image: url(<?php echo get_the_post_thumbnail_url($attached_expert->ID, 'full'); ?>);"></div>
                                                    <?php endif; ?>
                                                    <div class="card-author--content">
                                                        <div class="card-author--name"><?php echo get_the_title($attached_expert->ID); ?></div>
                                                        <div class="card-author--date"><?php echo get_field('expert_skills', $attached_expert->ID); ?></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <?php endif; ?>

                                    <?php
                                        // Another experts
                                        $another_experts = get_field('another_experts');
                                        if( $another_experts ):
                                            $expert_ids = array_merge($expert_ids, array_column($another_experts, 'ID'));
                                            $another_expert = $another_experts[0]; ?>
                                            <div class="col-md-4 col-10">
                                                <a href="<?php echo get_permalink($another_expert->ID); ?>">
                                                    <div class="card-item--author">
                                                        <?php if (has_post_thumbnail($another_expert->ID)) : ?>
                                                            <div class="card-author--photo" style="background-image: url(<?php echo get_the_post_thumbnail_url($another_expert->ID, 'full'); ?>);"></div>
                                                        <?php endif; ?>
                                                        <div class="card-author--content">
                                                            <div class="card-author--name"><?php echo get_the_title($another_expert->ID); ?></div>
                                                            <div class="card-author--date"><?php echo get_field('expert_skills', $another_expert->ID); ?></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <?php $amount_another_experts = count($another_experts); if( $amount_another_experts > 1) : ?>
                                                <div class="col-md-4 col-10">
                                                    <span class="card-link--blue">и еще <?php echo $amount_another_experts -1; ?> экспертов</span>
                                                </div>
                                            <?php endif; ?>

                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="post-header--actions d-none d-sm-none d-md-block">
                                    <div class="post-action">
                                        <a href="<?php echo get_permalink() . 'pdf'; ?>"><span class="icon icon-export"></span></a>
                                    </div>
                                    <div class="post-action">
                                        <span class="icon icon-share"></span>
									    <div class="sharing-tooltip"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-12 d-none d-sm-none d-md-block">
                        <nav class="post-content--nav">
                            <ul class="post-nav--list">
                                <?php foreach ($expert_descriptions as $expert_description_title => $expert_description_text): ?>
                                    <li class="post-list--item">
                                        <a href="#<?php echo bre_generate_id_from_cyr($expert_description_title); ?>" class="post-item--link">
                                            <?php echo $expert_description_title; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="post-content--descriptions">
                            <?php foreach ($expert_descriptions as $expert_description_title => $expert_description_text): ?>
                                <div id="<?php echo bre_generate_id_from_cyr($expert_description_title); ?>" class="post-description">
                                    <div class="post-description--title"><?php echo $expert_description_title; ?></div>
                                    <div class="post-description--text"><?php echo $expert_description_text; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="attached-posts">
            <!-- Carousels block -->
            <?php $expert_ids ? bre_the_experts_carousel($expert_ids) : ''; ?>

            <?php $events = get_field('company_events'); ?>
            <?php $events ? bre_the_events_carousel(array_column($events, 'ID')) : ''; ?>

            <?php $terms = get_the_terms($post->id, 'locations'); ?>
            <?php $terms ? bre_the_locations_carousel(array_column($terms, 'term_id')) : ''; ?>
        </div>
        <div class="company-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="post-contact">
                            <button class="post-contact--button" data-expert="<?php echo get_the_ID(); ?>"><?php echo __('Contact', 'brandsembassy'); ?></button>
                            <p class="post-contact--text"><?php echo __('If you have a question or business proposal for the company, you can contact her.', 'brandsembassy'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <div class="post-relation--title"><?php echo __('Company Links', 'brandsembassy'); ?></div>
                        <p>
                            <a href="<?php the_field('site'); ?>" class="card-link--blue"><?php the_field('site'); ?></a>
                        </p>

                        <ul class="post-social--items">
                            <?php foreach($expert_socials as $expert_social_name => $expert_social_link) : ?>
                                <li class="post-social--item">
                                    <a href="<?php echo $expert_social_link; ?>" target="_blank">
                                        <span class="icon icon-expert--<?php echo $expert_social_name; ?>"></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>