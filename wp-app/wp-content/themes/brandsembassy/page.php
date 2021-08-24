<?php 
get_header(); ?>
<div class="page-wrapper">
	<div class="section-container">
		<div class="container">
				<div class="content-page">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="page-title"><?php the_title(); ?></div>
							<div class="page-content-entry">
								<?php the_content(); ?>
							</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>