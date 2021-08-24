<?php get_header(); ?>
<div class="page-wrapper">
		<?php
            $expert_quote = get_field('expert_quote');
            $expert_events = get_field('expert_events');

            $expert_socials = array_filter([
                'facebook' => get_field('expert_facebook'),
                'linkedin' => get_field('expert_linkedin'),
                'instagram' => get_field('expert_instagram'),
                'twitter' => get_field('expert_twitter'),
                'youtube' => get_field('expert_youtube'),
                'vk' => get_field('expert_vk')
            ]);

            $expert_descriptions = array_filter([
                'Личность' => get_field('expert_person'),
                'Деятельность' => get_field('expert_activity'),
                'Личная жизнь' => get_field('expert_life'),
                'Интересные факты и события' => get_field('expert_facts')
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
							<h2 class="post-header--middlename"><?php the_field('expert_middlename'); ?></h2>
							<div class="post-header--position"><?php the_field('expert_skills'); ?></div>
							<div class="post-header--categories">
                            <?php if ($industries = bre_get_post_child_terms(get_the_ID(), 'industries')) : ?>
								<?php foreach ($industries as $industry) : ?>
                                    <a class="post-category--item" href="<?php echo get_term_link($industry->term_id, 'industries'); ?>" target="_blank">
                                        <?php echo (next($industries) ) ? $industry->name . ',&nbsp;' : $industry->name; ?>
                                    </a>
								<?php endforeach; ?>
                            <?php endif; ?>
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
					<?php if ($expert_quote) : ?>
						<div class="offset-md-3 col-md-9 col-12">
							<div class="post-header--quote">
								<span class="icon icon-quote"></span>
								<?php echo strip_tags($expert_quote, '<p><a><b><strong><br/><br></br>'); ?>
							</div>
						</div>
					<?php endif; ?>
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
                                    <div class="post-description--text"><?php echo strip_tags($expert_description_text, '<p><a><b><strong><br/><br></br>'); ?></div>
                                </div>
                            <?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			$attached_companies = get_field('expert_companies');
			if($attached_companies) {
				$attached_companies = array_slice($attached_companies, 0, 3);
		}
			$attached_locations = get_the_terms($post->ID, 'locations');
			if($attached_locations) {
				$attached_locations = array_slice($attached_locations, 0, 3);
			}
			$attached_events = get_field('expert_events');
			if($attached_events) {
				$attached_events = array_slice($attached_events, 0, 2);
			}
			?>
		<div class="post-relations">
			<div class="container">
				<div class="row">
					<?php if(is_array($attached_locations)) : ?>
						<div class="col-md-4 col-12">
							<div class="post-relation--item">
								<div class="post-relation--title"><?php echo __('Related locations', 'brandsembassy'); ?></div>
								<div class="relation-items">
									<?php foreach($attached_locations as $attached_location) : ?>
										<div class="relation-item--title"><?php echo $attached_location->name; ?></div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php if(is_array($attached_events)) : ?>
						<div class="col-md-4 col-12">
							<div class="post-relation--item">
								<div class="post-relation--title"><?php echo __('Related events', 'brandsembassy'); ?></div>
								<div class="relation-items">
									<?php foreach($attached_events as $attached_event) : ?>
										<div class="relation-item--title"><?php echo get_the_title($attached_event); ?></div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php if(is_array($attached_companies)) : ?>
						<div class="col-md-4 col-12">
							<div class="post-relation--item">
								<div class="post-relation--title"><?php echo __('Related companies', 'brandsembassy'); ?></div>
								<div class="relation-items">
									<?php foreach($attached_companies as $attached_company) : ?>
										<div class="relation-item--title"><?php echo $attached_company->post_title; ?></div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="post-footer">
			<div class="container">
				<div class="row">
					<div class="col-md-4 col-12">
						<div class="post-contact">
							<button class="post-contact--button" data-expert="<?php echo get_the_ID(); ?>"><?php echo __('Contact', 'brandsembassy'); ?></button>
							<p class="post-contact--text"><?php echo __('If you have a question or business proposal for the expert, you can contact her.', 'brandsembassy'); ?></p>
						</div>
					</div>
					<div class="col-md-8 col-12">
						<div class="post-relation--title"><?php echo __('Expert Links', 'brandsembassy'); ?></div>
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