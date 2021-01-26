<?php
$mobile_force = 0;
if(wp_is_mobile() && of_get_option('ph_mobile_on')){
	require_once dirname( __FILE__ ) . '/page-mobile.php';
}else{
	if(of_get_option('ph_page_layout_style') == 'page-2'){
		require_once dirname( __FILE__ ) . '/page-2.php';
	}else{
		require_once dirname( __FILE__ ) . '/page-1.php';
	}	
}
?>