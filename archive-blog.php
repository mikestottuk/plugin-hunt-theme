<?php
/**
 * The blog index file.
 */

get_header('blog'); ?>

	<div id="primary" class="content-area-ph">
		<div class='col-md-8 col-md-offset-2'>
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<?php if ( is_home() && ! is_front_page() ) : ?>
					<header>
						<h1 class="page-title screen-reader-text ph-blog-list-title"><?php single_post_title(); ?></h1>
					</header>
				<?php endif; ?>

				<?php
				// Start the loop.
				while ( have_posts() ) : the_post(); 

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */

					 ?>

		
		<div style="clear:both"></div>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
			<?php
				if ( is_single() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
				endif;
			?>
		</header><!-- .entry-header -->
		<!--- blog content submitter -->
		<div class='author-tool author' data-toggle="tooltip" data-placement="left" title="<?php echo get_the_author_meta('user_nicename'); ?>">  <?php echo get_avatar( get_the_author_meta( 'ID' ), 40 );  _e('by ','pluginhunt'); echo the_author_posts_link();  ?> </div>
		<div class="entry-content-ph">
		<div class='ph-thumbnail'>
		<?php
				if ( has_post_thumbnail() ) { 
					the_post_thumbnail('large');
				}
		?>
		</div>
			<?php
				/* translators: %s: Name of current post */
				the_excerpt('');

				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'ph_theme' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'ph_theme' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );
			?>
		</div><!-- .entry-content -->


		<footer class="entry-footer">
			<hr>
			<ul class='post-share'>
				<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="facebook"><li class='fb ph-s'><i class="fa fa-facebook"></i></li></a>
				<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="twitter"><li class='tw ph-s'><i class="fa fa-twitter"></i></li></a>						
				<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="google"><li class='gp ph-s'><i class="fa fa-google-plus"></i></li></a>
				<li class='em ph-s'><i class="fa fa-envelope"></i></li>
			</ul>
			<div class='posted-on'><?php _e('Posted','pluginhunt');?> <?php the_time('F j, Y'); ?></div>
			<div class='clear'></div>
			<?php edit_post_link( __( 'Edit', 'ph_theme' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-footer -->

	</article><!-- #post-## -->

	<?php
				// End the loop.
				endwhile;

				// Previous/next page navigation.
				the_posts_pagination( array(
					'prev_text'          => __( 'Previous page', 'ph_theme' ),
					'next_text'          => __( 'Next page', 'ph_theme' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'ph_theme' ) . ' </span>',
				) );

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'content', 'none' );

			endif;
			?>
		</div> <!-- end content offset column -->
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer('blog'); ?>
