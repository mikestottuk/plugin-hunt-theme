<?php
get_header(); ?>

<div class='ph-layout-2 ph-list ph-layout-2-profile'>
<?php 
	pluginhunt_OutputSlider('post'); 
	pluginhunt_Globals();
?>

<div class='container'>
	<div class='post-wrapper'>
	<div class='col-md-2 discuss-sidebar hide'>

		<?php dynamic_sidebar('ph-home-sidebar');  ?>

	</div>

<div class='col-md-12 page-2'>
	
	<?php
		// Start the Loop.
		while ( have_posts() ) : the_post();
			// Include the page content template.
			get_template_part( 'content', 'page' );
		endwhile;
	?>
	<div class='disclaimer'><?php echo of_get_option('ph_hunt_disclaimer'); ?></div>
</div>

<div class='col-md-3 discussion-collections hide' style="margin-top:52px">



			 	<?php   if(!is_user_logged_in()){ ?>
			 		<div class='sign-up-cta'>
			 			<h3 class='section--heading'><?php echo of_get_option('ph_logged_out_tit'); ?></h3>
			 			<h4><?php echo of_get_option('ph_logged_out_sub'); ?></h4>
			 		  <div class='ph_socials'>
			 		  	<div class='ph-join'>
                           <div class='ph-soc-block'>
                           	<ul class='ps-main'>
                            	<li class='tw ph-sm'><a href="<?php echo wp_login_url(); ?>?loginTwitter=1&redirect=<?php echo $surl;?>" onclick="window.location = '<?php echo wp_login_url(); ?>?loginTwitter=1&redirect='+window.location.href; return false;">
                            	<i class="fa fa-twitter"></i><?php _e('Log in to vote','pluginhunt'); ?></a></li>
                            	<br/><li class='fb ph-sm'><a href="<?php echo wp_login_url(); ?>?loginFacebook=1&redirect=<?php echo $surl;?>" onclick="window.location = '<?php echo wp_login_url(); ?>?loginFacebook=1&redirect='+window.location.href; return false;">
                            	<i class="fa fa-facebook"></i><?php _e('Log in to vote','pluginhunt'); ?></a></li>
                        	</ul>
                           </div>
                       </div>
                      </div>
                      <div style="clear:both"></div>
					</div>  <!-- end sign up CTA -->
					<?php  } ?>

					<?php get_sidebar('right') ?>

</div>


</div>

<?php
get_footer();
