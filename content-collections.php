<?php
/**
 * The template used for displaying collections 
 */

?>


<?php 
$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>


<div class='c-card collection-card'>
<a href="<?php echo get_permalink($post->ID); ?>"><div class="collection-card--header" style="background-image:url(<?php echo $feat_image; ?>);"></div>
<span class="new-card">
	<div class="collection-card--name">
		<h3><?php echo $post->post_title; ?></h3>
	</div>
	<div class="collection-card--title">
		<?php echo $post->post_content; ?>
	</div>
	<div class="collection-card--follow-button">
	</div>
</span>
</a>
</div>
