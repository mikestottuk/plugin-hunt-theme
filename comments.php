<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'walker' => new ph_comment_walker,
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 30,
				) );
			?>
		</ol><!-- .comment-list -->



	<?php 
	else: ?>
	
	<div class='section'>
		<?php // if cannot comment - using WordPress user roles // ?>
		<div id="comments">
			<p class="post-detail placeholder comment-placeholder"><span><?php _e('No comments yet.','pluginhunt'); ?></span></p>
		</div>
		<?php ?>
	</div>
	<?php	endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'pluginhunt' ); ?></p>
	<?php endif; ?>


<?php if(current_user_can( 'edit_posts' ) && is_user_logged_in()){ ?>
	<?php comment_form(); ?>
	<?php } ?>

</div><!-- .comments-area -->
