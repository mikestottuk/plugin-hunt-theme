<?php 

/* Template name: Page with Right Sidebar */ 

get_header(); ?>

<div class='container'>


	<div class='col-md-9 maincontent toppage'>

		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				// Include the page content template.
				get_template_part( 'content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
		?>
	</div>
	<div class="col-md-3">
		<?php get_sidebar('right') ?>
	</div>


<?php
get_footer(); ?>