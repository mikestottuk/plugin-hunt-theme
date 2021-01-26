<?php
get_header(); 

	global $wp_query,$post,$wpdb;
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );

			?>

	<div class='row'>
			 <div class='post-header-event ama-upcoming-shadow' style="background-image: url('<?php echo $image[0]; ?>')">

			 	<div class='ama-upcoming-shadow'>
		
				 	<div class='container-title'>	
				 		<span class='post-title-single'><a class='title-link title-link-html-<?php echo $post->ID;?>' href="<?php echo esc_url($out); ?>" title="<?php the_title_attribute(); ?>" target="_blank" rel="nofollow"><?php the_title(); ?></a></span>
				 		<div class='post-description-short'><?php echo wp_trim_words( get_the_excerpt(), 40, '...' ); ?></div>
				 		<span class='post-description-single hide'><?php the_content(); ?></span>
				 	</div>
	

				 </div>


			 </div>


</div> 
			
<?php get_footer(); ?>