<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-57x57.png'; ?>">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-60x60.png'; ?>">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-72x72.png'; ?>">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-76x76.png'; ?>">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-114x114.png'; ?>">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-120x120.png'; ?>">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-144x144.png'; ?>">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-152x152.png'; ?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/apple-icon-180x180.png'; ?>">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_template_directory_uri() . '/assets/img/favicons/android-icon-192x192.png'; ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/favicon-32x32.png'; ?>">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/favicon-96x96.png'; ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/favicon-16x16.png'; ?>">
	<link rel="manifest" href="<?php echo get_template_directory_uri() . '/assets/img/favicons/manifest.json'; ?>">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri() . '/assets/img/favicons/ms-icon-144x144.png'; ?>">
	<meta name="theme-color" content="#ffffff">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php wp_head(); ?>
	<script charset="UTF-8" src="//cdn.sendpulse.com/js/push/88d95a2a0aa239c1523fa3ac020f8813_0.js" async></script>
</head>

<body <?php body_class(); ?> id="body">
	<?php global $lang, $current_user; ?>
	<div class="wrapper">
		<header class="header">
			<div class="container">
				<div class="row d-none d-md-flex">
					<div class="col-2">
						<div class="logo">
							<a class="logo-title" href="<?php echo home_url(); ?>">Brands Embassy</a>
						</div>
					</div>
					<div class="col-8">
						<?php wp_nav_menu(array('container' => '', 'container_class' => '', 'menu_class' => 'nav_menu', 'theme_location' => 'primary')); ?>
					</div>
					<div class="col-1">
						<div class="button-popup button-search" data-popup="search">
							<span class="icon icon-search"></span>
						</div>
					</div>
					<div class="col-1">
						<div class="languages-switcher">
							<?php $currentLanguage = PLL()->curlang; ?>
							<span class="language-current"><?php echo $currentLanguage->name; ?><?php echo $currentLanguage->flag; ?></span>
							<ul class="languages-list">
								<?php $langArgs = array(
									'dropdown' => 1,
									'show_names' => 1,
									'display_names_as' => 'slug',
									'show_flags' => 1,
									'dropdown' => 1,
									'raw' => 1,
									'hide_current' => 1
								);
								$languages = pll_the_languages($langArgs);
								foreach ($languages as $language) : ?>
									<li class="language-item"><a href="<?php echo $language['url']; ?>"><?php echo $language['name']; ?><?php echo $language['flag']; ?></a></li>
								<?php endforeach; ?>
							</ul>
							<span class="icon icon-arrow--down"></span>
						</div>
					</div>
				</div>
				<div class="row d-flex d-md-none">
					<div class="col-2">
						<div class="button-popup button-search" data-popup="search">
							<div class="search"><span class="icon icon-search--white"></span></div>
						</div>
					</div>
					<div class="col-8">
						<a class="logo-title" href="<?php echo home_url(); ?>">Brands Embassy</a>
					</div>
					<div class="col-2">
						<div class="menu-nav">
							<div class="menu-nav--burger"></div>
							<div class="menu-nav--box">
								<div class="menu-box--languages">
									<?php $currentLanguage = PLL()->curlang; ?>
									<ul class="languages">
										<li class="language-item language-item--current"><?php echo $currentLanguage->name; ?></li>
										<?php $langArgs = array(
											'dropdown' => 0,
											'show_names' => 1,
											'display_names_as' => 'slug',
											'show_flags' => 0,
											'dropdown' => 1,
											'raw' => 1,
											'hide_current' => 1
										);
										$languages = pll_the_languages($langArgs);
										foreach ($languages as $language) : ?>
											<li class="language-item"><a href="<?php echo $language['url']; ?>" class="language-item--link"><?php echo $language['name']; ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
								<?php wp_nav_menu(array('container' => '', 'container_class' => '', 'menu_class' => 'menu-nav--mobile', 'theme_location' => 'primary')); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>