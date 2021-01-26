<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); 

	global $wp_query,$post,$wpdb, $current_user,$query_string, $woocommerce;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';
	$num_posts = 0;

?>
<div class='ph-layout-2 ph-list'>

	<div class='post-wrapper'>
	<div class='col-md-1 col-md-offset-1 discuss-sidebar'>

		<?php dynamic_sidebar('ph-woo-sidebar');  ?>

	</div>


	<div class='col-md-7 white-splash'>

	<h3 class='headline'><?php _e("Auctions","pluginhunt"); ?></h3>


	<a id="ph-log-social-new" href="#animatedModal" style="display:none">.</a>
		<?php
		
		$orderby = (isset($wp_query->query_vars["orderby"])) ? $wp_query->query_vars["orderby"] :'date';

		
		
		query_posts( array( 'post_type' => 'product',  
							'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')), 
                            'meta_query' =>  array($woocommerce->query->stock_status_meta_query()),
							'orderby' => $orderby,
							'posts_per_page' =>$wp_query->query_vars["posts_per_page"],
							'paged' =>$wp_query->query_vars["paged"],
							'auction_arhive' => TRUE ) );

		if ( have_posts() ) : ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>
				<div class='woo-auction'>
					<?php wc_get_template_part( 'content', 'product' ); ?>
				</div>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
	</div>

	<div class='col-md-2 discussion-collections wp-hide'>


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

<div class='col-md-1'>

</div>

</div>

</div>
<?php get_footer( 'shop' ); ?>
