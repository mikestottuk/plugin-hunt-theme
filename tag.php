<?php
$mobile_force = 0;
if(wp_is_mobile() && of_get_option('ph_mobile_on')){
	require_once dirname( __FILE__ ) . '/tag-mobile.php';
}else{
	if(of_get_option('ph_layout_style') == 'index-2'){
		require_once dirname( __FILE__ ) . '/tag-layout-2.php';
	}else if(of_get_option('ph_layout_style') == 'index-3'){
		require_once dirname( __FILE__ )  . '/tag-layout-3.php';
	}else if(of_get_option('ph_layout_style') == 'index-4'){
		require_once dirname( __FILE__ )  . '/tag-layout-4.php';
	}else{
		require_once dirname( __FILE__ ) . '/tag-layout-1.php';
	}	
}
?>