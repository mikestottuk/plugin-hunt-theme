<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header('blog'); ?>

	<div id="primary" class="site-content col-md-12">
				<div class='col-md-8 col-md-offset-2'>
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<h2 class="entry-title">
				<?php the_title(); ?>
			</h2>
			<div class='author-tool author' data-toggle="tooltip" data-placement="left" title="<?php echo get_the_author_meta('user_nicename'); ?>">  <?php echo get_avatar( get_the_author_meta( 'ID' ), 40 );  _e('by ','pluginhunt'); echo the_author_posts_link();  ?> </div>

		<header class="entry-header">
			<div class='ph-thumbnail'>
			<?php if ( ! post_password_required() && ! is_attachment() ) :
				the_post_thumbnail(); ?>
			</div>

			<?php endif; // is_single() ?>
			<?php if ( comments_open() ) : ?>
			<?php endif; // comments_open() ?>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content-ph">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'pluginhunt' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'pluginhunt' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>


	</article><!-- #post -->


			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
</div>

<?php get_footer('blog'); ?>