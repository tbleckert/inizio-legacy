<form action="<?php bloginfo( 'url' ); ?>" method="get">
	<input type="search" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e( 'Search the site...', LANG_DOMAIN ); ?>">
	<button type="submit"><?php _e( 'Search', LANG_DOMAIN ); ?></button>
</form>