<?php
get_header('page'); ?>


<div class='col-md-12 ph-layout-1-profile'>
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
					// Include the page content template.
					get_template_part( 'content', 'page' );
				endwhile;
			?>
<div class='bottom-paddy'></div>
<div class='disclaimer'><?php echo of_get_option('ph_hunt_disclaimer'); ?></div>
</div>


<?php get_footer('home'); ?>