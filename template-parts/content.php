<?php 

global $blob, $post;

?>
<div class = 'row hunt-row <?php echo $blob;?>' style = 'margin-bottom:20px'>
	<?php pluginhunt_outputVoting($post); ?>
	<?php pluginhunt_FeaturedImage($post); ?>
	<div class='post-meta-hunt'>
		<?php pluginhunt_ExternalLink($post); ?>
		<?php pluginhunt_CollectionOutput($post->ID); ?>
		<?php pluginhunt_AuthorMeta($post); ?>
		<?php pluginhunt_commentList($post); ?>
	</div>
		<?php pluginhunt_PostContent($post); ?>
</div>
<div style="clear:both"></div>
