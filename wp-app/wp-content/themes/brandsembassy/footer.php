<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
			<img src="<?php echo get_template_directory_uri() . '/assets/img/logo-white.svg'; ?>" alt="Brands Embassy">
			</div>
			<div class="col-md-2 d-none d-md-block">
				<?php wp_nav_menu(array('container' => '', 'container_class' => '', 'menu_class' => 'footer_menu', 'theme_location' => 'footer_1')); ?>
			</div>
			<div class="col-md-2 d-none d-md-block">
				<?php wp_nav_menu(array('container' => '', 'container_class' => '', 'menu_class' => 'footer_menu', 'theme_location' => 'footer_2')); ?>
			</div>
			<div class="col-md-2 d-none d-md-block">
				<?php wp_nav_menu(array('container' => '', 'container_class' => '', 'menu_class' => 'footer_menu', 'theme_location' => 'footer_3')); ?>
			</div>
			<div class="col-md-2">
				<?php wp_nav_menu(array('container' => '', 'container_class' => '', 'menu_class' => 'footer_menu', 'theme_location' => 'footer_4')); ?>
			</div>
		</div>
		<div class="row">
			<div class="offset-md-2"></div>
			<div class="col-md-4">
				<div class="footer-social">
					<ul class="footer-social--list">
						<li class="social-list--item"><a href="<?php the_field('facebook', 'option'); ?>" target="_blank"><span class="icon icon-facebook"></span></a></li>
						<li class="social-list--item"><a href="<?php the_field('twitter', 'option'); ?>" target="_blank"><span class="icon icon-twitter"></span></a></li>
						<li class="social-list--item"><a href="#"><span class="icon icon-youtube"></span></a></li>
						<li class="social-list--item"><a href="<?php the_field('instagram', 'option'); ?>" target="_blank"><span class="icon icon-instagram"></span></a></li>
					</ul>
				</div>
			</div>
			<div class="offset-md-3"></div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="offset-md-2"></div>
			<div class="col-md-10">
				<div class="cookies-notice">
					<?php echo __('We use cookies to improve the site. A cookie contains information about past visits to the site. If you do not want this data to be processed, disable the cookie in your browser settings.', 'brandsembassy'); ?>
				</div>
			</div>
		</div>
	</div>
</footer>
<div class="popup" data-popup="search">
	<div class="popup-container">
    	<?php get_template_part('template-parts/modals/search'); ?>
	</div>
</div>
</div>
<?php wp_footer(); ?>
</body>

</html>