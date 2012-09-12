		<footer class="footer" role="contentinfo">
				
				<nav role="navigation">
  					<?php Bones::footer_links(); // Adjust using Menus in Wordpress Admin ?>
                </nav>
                		
				<p class="attribution">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>.</p>
			
		</footer> <!-- end footer -->
	
		<?php wp_footer(); // js scripts are inserted using this function ?>

	</body>

</html> <!-- end page. what a ride! -->