<!doctype html>  

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
		<meta charset="utf-8">
		
		<title><?php wp_title(''); ?></title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
  	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->		
	</head>
	
	<body <?php body_class(); ?>>
	
		<header class="header" role="banner">
		
			<?php if(is_home()): ?>
			<h1><?php bloginfo('name'); ?></h1>
			<?php else: ?>
			<div class="logo"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></div>
			<?php endif; ?>
			
			<!-- if you'd like to use the site description you can un-comment it below -->
			<?php // bloginfo('description'); ?>
			
			
			<nav role="navigation">
				<?php Bones::main_nav(); // Adjust using Menus in Wordpress Admin ?>
			</nav>
		
		</header>
