<?php /* Template name: Page Builder Template */ 

get_header(); ?>

<div style="margin-top: 60px;">
	<div>

	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>


<?php get_footer(); ?>