<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#">
  <head>
    <meta charset="utf-8">
    <title><?php wp_title(''); ?></title>  
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css">
    <meta name="twitter:widgets:csp" content="on">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));</script>


  	<?php	wp_enqueue_script("jquery");  
      wp_enqueue_style('wptheme', get_template_directory_uri() . '/style.css' ); 
      if(wp_is_mobile()){
      wp_enqueue_style('wpthememob', get_template_directory_uri() . '/mobile-style.css' ); 
      }
      ?>
  		
    <meta property="og:title" content="<?php wp_title(); ?>"/>
    <meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ); ?>"/>

<?php wp_head(); ?>
</head>

<nav class="navbar navbar-inverse transparent ph-main-nav">
  <div class="container-fluid navbar-inner">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
  <a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'>
<div class='pluginwrapper'>
  <div class='phhomelogom'><img src='<?php echo of_get_option('white_logo'); ?>'/></div>
    <div class='plugin'><?php echo get_bloginfo( 'name' ); ?><span class='hide'><?php echo get_bloginfo( 'description' ); ?></span></div>
</div>
</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

    <?php wp_nav_menu( array( 'container' => '','container_class' => '','theme_location' => 'home','menu_class' => 'nav navbar-nav navbar-right','walker' => new Bootstrap_Walker()) ); ?>

     <!-- <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>

    -->

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<body <?php body_class(); ?>>

<div class='ph-hero'>

<div class='container'>
  <div class='col-md-5'>
      <div class='intro'><h1>Use WordPress to create your own</h1></div>
      <div class='for'>Product Hunt</div>
      <div class='below'>
          <p>
              <b>Create</b> your own Product Hunt Style website in minutes. With our amazing <b>WordPress Theme</b>.
          </p>
          <ul class='benefits'>
            <li>Posts ranked by <b>popularity</b>, grouped by day.</li>
            <li><b>Your users</b> can submit content. You keep <b>control.</b></li>
            <li><b>Easy</b> to manage community led website.</li>
          </ul>
          <div class='cta'>
            <a href='http://pluginhunt.com/pricing/'>
              <p class='today'> Purchase Today <i class='fa fa-chevron-right'></i></p>
              <p class='small-p'>Get instant access to the Theme today. Prices from $79.</p>
            </a>
          </div>
      </div>


  </div>
  <div class='col-md-6'>
    <div class='phlander'>
      <?php if(wp_is_mobile()){ ?>
      <iframe  id="video" width="338" height="170" src="https://www.youtube.com/embed/C0VdzaL2Gps?rel=0" frameborder="0" allowfullscreen></iframe>
      <?php }else{ ?>
      <iframe  id="video" width="676" height="380" src="https://www.youtube.com/embed/C0VdzaL2Gps?rel=0" frameborder="0" allowfullscreen></iframe>
      <?php } ?>
    </div>
  </div>
</div>

</div>


<div class='container'>

<div class='woo-wrap'>
  <div class='wooninja'></div>
  <div class='woo'>..announcing <b>WooCommerce</b> support. You can now sell directly from your site. <b><a href='http://pluginhunt.com/woo/'>Make money hunting!</a></b></div>
</div>






