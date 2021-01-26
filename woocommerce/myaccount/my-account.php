<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices(); ?>

<script>
jQuery(document).ready(function($){
    jQuery(".page-header--navigation--tab").bind("click",function(e){
        e.preventDefault();
        jQuery(".page-header--navigation--tab").removeClass('m-active');
        jQuery(this).addClass('m-active');
        var id = jQuery(this).attr('id');
        jQuery('.woo-tabbed').hide();
        jQuery('#' + id + '-tab').fadeIn(3000);
    });

    jQuery("#header-down, .woo-down").bind("click",function(e){
        e.preventDefault();
        jQuery(".page-header--navigation--tab").removeClass('m-active');
        jQuery("#down").addClass('m-active');
        var id = 'down';
        jQuery('.woo-tabbed').hide();
        jQuery('#' + id + '-tab').fadeIn(3000);
    });

    jQuery(".woo-cp").bind("click",function(e){
        e.preventDefault();
        jQuery(".page-header--navigation--tab").removeClass('m-active');
        var id = 'change';
        jQuery('.woo-tabbed').hide();
        jQuery('#' + id + '-tab').fadeIn(3000);
    });

     jQuery(".woo-keys").bind("click",function(e){
        e.preventDefault();
        jQuery(".page-header--navigation--tab").removeClass('m-active');
        var id = 'license';
        jQuery('.woo-tabbed').hide();
        jQuery('#' + id + '-tab').fadeIn(3000);
    });
});
</script>
<?php
$uid = get_current_user_id();
global $current_user;
wp_get_current_user();
?>
<div class='account-header'>

<div id='wrapper-account'>
	<div id='header-right'>
		<div class='account-user-info user-header'>
			<div class='epic-100'>
				<?php echo get_avatar( $uid , 62 ); ?>
                <div class='hello'>
                    <?php
                        if($current_user->user_firstname != ''){
                           echo $current_user->user_firstname . ' ' . $current_user->user_lastname;
                        }
                    ?>
                </div>
			</div>
		</div>
	</div>

</div>

  <nav class="page-header--navigation">
  <ul>
    <li class="page-header--navigation--tab m-active" id = 'dash'>
      <a href="#"><strong><?php echo $cu; ?></strong> Dashboard</a>
    </li>
      <li class="page-header--navigation--tab" id = 'down'>
        <a href="#"><strong><?php echo $cs; ?></strong> Sales</a>
      </li>
      <li class="page-header--navigation--tab" id = 'orders'>
        <a href="#"><strong><?php echo $cl; ?></strong> Orders</a>
      </li>
     <li class="page-header--navigation--tab" id = 'edit'>
      <a href="#"><strong><?php echo $cfd; ?></strong> Edit Details</a>
    </li>
  </ul>
</nav>



</div>

<div class='container'>


<div class='woo-tabbed' id ='dash-tab' style='display:block'>
    <div id="welcome-message">
    		<h3>Welcome <?php echo $current_user->user_firstname; ?></h3>
    		<p>Hi <?php echo $current_user->user_firstname; ?>, this is your dashboard. From here you can access all your purchased files as well as view order information and update your details.</p>
    </div>
</div>
<?php remove_action('woocommerce_before_my_account','get_my_api_manger_account_template'); 
	$api_plugin_path = plugin_dir_path( __FILE__ );

?>

<?php 

// do_action( 'woocommerce_before_my_account' ); ?>

<div class='woo-tabbed' id ='down-tab'>
<?php 
//wc_get_template( 'myaccount/my-downloads.php' ); 
		echo do_shortcode('[vendor_sales_log]'); 
?>
</div>

<div class='woo-tabbed' id='license-tab'>
<?php 
	wc_get_template( 'my-api-keys.php', array( 'user_id' => $uid ), '', $api_plugin_path);
?>	
</div>

<div class='woo-tabbed' id ='edit-tab'>
<?php wc_get_template( 'form-edit-account.php', array( 'user_id' => $uid ), '', $api_plugin_path); ?>
</div>


<div class='woo-tabbed' id ='change-tab'>
<?php wc_get_template( 'form-lost-password.php', array( 'user_id' => $uid ), '', $api_plugin_path); ?>
</div>

<div class='woo-tabbed' id ='orders-tab'>
<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>
</div>



</div>

<?php // do_action( 'woocommerce_after_my_account' ); ?>


