<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Welcome to Plugin Hunt</title>
	<script type="text/javascript" src="./assets/jquery.js"></script>
	<script type="text/javascript" src="./assets/jquery.blockUI.min.js"></script>
	<script type="text/javascript" src="./assets/bootstrap.min.js"></script>
	<script type="text/javascript" src="./assets/wizard.js"></script>
	<style type="text/css">img.wp-smiley,img.emoji{display:inline !important;border:none !important;box-shadow:none !important;height:1em !important;width:1em !important;margin:0 .07em !important;vertical-align:-0.1em !important;background:none !important;padding:0 !important}#wc-logo img{max-width:20% !important}#feedbackPage{display:none}.wc-setup .wc-setup-actions .button-primary{background-color:#408bc9 !important;border-color:#408bc9 !important;-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 1px 0 #408bc9 !important;box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 1px 0 #408bc9 !important;text-shadow:0 -1px 1px #408bc9,1px 0 1px #408bc9,0 1px 1px #408bc9,-1px 0 1px #408bc9 !important;float:right;margin:0;opacity:1}</style>
	<link rel="stylesheet" id="bs" href="./assets/bootstrap.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="./assets/loadstyles.css" type="text/css" media="all">
	<link rel="stylesheet" id="open-sans-css" href="./assets/styles.css" type="text/css" media="all">
	<link rel="stylesheet" id="woocommerce_admin_styles-css" href="./assets/admin.css" type="text/css" media="all">
	<link rel="stylesheet" id="wc-setup-css" href="./assets/zbs-exitform.css" type="text/css" media="all">
	<link rel="stylesheet" id="woocommerce-activation-css" href="./assets/activation.css" type="text/css" media="all">
	<link rel="stylesheet" id="wizzy" href="./assets/wizard.css" type="text/css" media="all">
	<style type="text/css" media="print">#wpadminbar { display:none; }</style>

    <?php
    include( dirname(dirname(dirname(dirname( dirname ( __FILE__ ))) ))."/wp-config.php" );  
    ?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
</head>
<body class="wc-setup wp-core-ui">
			<h1 id="wc-logo"><a href="https://pluginhunt.com" target="_blank"><img src="./assets/pluginhunt.png" alt="Plugin Hunt"></a></h1>
		<div class="wc-setup-content" id="firstPage">
<div class="container">
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Choose Layout</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle">2</a>
            <p>Install Demo Content</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle">3</a>
            <p>Extend the theme</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">4</a>
            <p>Finish</p>
        </div>
    </div>
</div>
    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Choose Layout</h3>
                <p>Product Hunt has changed over time. With the plugin hunt theme we have kept some of the older designs for you to flick between if you wish to have a style slightly different than the main product hunt website. Choose your layout below. You can change this any time in <span class='italics'>Appearance -> Theme Options</span>.</p>
                <div class="row layout-choice">
                    <div class="col-md-6 l-o selected" data-lo="1">
                        <img src ="./assets/layout-1.png"/><br/>
                        <div class="caption">Classic Layout</div>
                    </div>
                    <div class="col-md-6 l-o" data-lo="2">
                        <img src="./assets/layout-2.png"/>
                        <div class="caption">Boxed Layout</div>
                    </div>
                </div>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3 class='demo-content'> Install Demo Content</h3>
                <p>Installing demo content is a good way to start. We recommend installing the content if you want to have your theme up and running <b>fast</b>.</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                    aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
                    </div>
                </div>
                <div class="alert alert-warning">Installing the demo content imports the latest posts from pluginhunt.com and creates an example <span class='italics'>featured collection</span>. You will still need to create any specific pages you like, set your menus and choose which widgets to display on the sidebars of the theme (if using layout 2).
                </div>
                <?php
                    echo '<input type="hidden" name="phdc-ajax-nonce" id="phdc-ajax-nonce" value="' . wp_create_nonce( 'phdc-ajax-nonce' ) . '" />';
                ?>
                <button class="btn btn-info demoGo btn-lg pull-left" type="button" >Install</button>
                <button class="btn btn-primary nextBtn btn-lg pull-right demoNext" type="button" >Skip</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Extend the Theme</h3>
                <p>While the theme out of the box works great, we have built a number of extensions to the theme. These will be available for free from <a href="https://epicplugins.com" style="color:black;font-weight:900;text-decoration:underline;">Epic Plugins</a>. Check back later or <a href="https://twitter.com/mikestottwp" style="color:black;font-weight:900;text-decoration:underline;">follow me on twitter</a> for release announcements.</p>

         
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
            </div>
        </div>
    </div>


    

    <div class="row setup-content" id="step-4">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Finished</h3>
                <p>OK you're all set. Don't forget to head over to the Theme Options if you wish to change the default text strings such as 'hunt it'</p>
                <div class="alert alert-info">
                <b>Important</b> when using the Theme My Login plugin be sure to check the box to 'enable themed profiles' otherwise
                    the profile page will not display.
                </div>
                <?php
                    echo '<input type="hidden" name="phos-ajax-nonce" id="phos-ajax-nonce" value="' . wp_create_nonce( 'phos-ajax-nonce' ) . '" />';
                    echo '<input type="hidden" name="phf-finish" id="phf-finish" value="' . home_url() . '" />';  
                ?>
                <button class="btn btn-success btn-lg pull-right ph-finito" type="submit">Finish!</button>
            </div>
        </div>
    </div>

</div>
		</div>			
</body></html>