<?php get_header(); ?>

<div class="section-container">
	<div class="container">
		<div class="search" style="margin-bottom: 50px;">
			<?php echo __('Search for experts:', 'brandsembassy'); ?> <?php echo get_search_form(); ?>
					</div>
			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'brandsembassy' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
				</header>

				<?php
				while ( have_posts() ) : the_post(); ?>
					<div class="search-result">
						<a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
					</div>
				<?php endwhile;
			else :
				echo __('Nothing found.', 'brandsembassy');
			endif;
			?>
	</div>
</div>
<?php get_footer(); ?>
