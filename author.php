<?php

if(of_get_option('ph_layout_style') == 'index-2'){
  require_once dirname( __FILE__ ) . '/author-layout-2.php';
}else{
  require_once dirname( __FILE__ ) . '/author-layout-1.php';
}


?>