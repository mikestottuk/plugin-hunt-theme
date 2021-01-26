<?php
$mobile_force = 0;

if(wp_is_mobile() || $mobile_force == 1){
	require_once dirname( __FILE__ ) . '/single-collections-mobile.php';
}else{
	if(of_get_option('ph_layout_style') == 'index-2'){
		require_once dirname( __FILE__ ) . '/single-collections-lay2.php';
	}else if(of_get_option('ph_layout_style') == 'index-3'){
		require_once dirname( __FILE__ ) . '/single-collections-lay3.php';
	}else{
		require_once dirname( __FILE__ ) . '/single-collections-lay1.php';
	}	
}

?>