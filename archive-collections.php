<?php
$mobile_force = 0;
if(wp_is_mobile() || $mobile_force == 1){
	require_once dirname( __FILE__ ) . '/archive-collections-mobile.php';
}else{
	require_once dirname( __FILE__ ) . '/archive-collections-desktop.php';
}
?>