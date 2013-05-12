<?php get_header(); ?>

	<h1><?php _e( '404 - Page not found', LANG_DOMAIN ); ?></h1>
	<p><?php _e( 'The page you requested could not be found. You can navigate back to the homepage or use the form below to find what you\'re looking for.', LANG_DOMAIN ); ?></p>

	<form action="<?php bloginfo( 'url' ); ?>" method="get">
		<input type="search" name="s" placeholder="Search the site...">
		<button type="submit">Search</button>
	</form>
	
<?php get_footer(); ?>