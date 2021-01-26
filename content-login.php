<?php 


 ?>

<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<div class="container">
  

    <div class="omb_login">

    	<h3 class="omb_authTitle">Login or <a href="<?php echo wp_registration_url(); ?>">Sign up</a></h3>


                    <?php $surl = get_site_url(); ?>
                    <br/>
                    <div class='ph_socials'>
                    <a href="<?php echo $surl;?>/login/?loginSocial=twitter" onclick="window.location = '<?php echo $surl;?>/login/?loginTwitter=1&redirect='+window.location.href; return false;">
                    <span class='ph-tw'><i class="fa fa-twitter"></i><?php _e('Sign in with Twitter','pluginhunt'); ?></span></a>


                    <a href="<?php echo $surl;?>/login/?loginSocial=facebook" onclick="window.location = '<?php echo $surl;?>/login/?loginFacebook=1&redirect='+window.location.href; return false;">
                    <span class='ph-fb'><i class="fa fa-facebook"></i><?php _e('Sign in with Facebook','pluginhunt'); ?></span></a>
                    </div>

		<div class="row omb_row-sm-offset-3 omb_loginOr">
			<div class="col-xs-12 col-sm-6">
				<hr class="omb_hrOr">
				<span class="omb_spanOr"><?php _e('or','pluginhunt'); ?></span>
			</div>
		</div>

		<div class="row omb_row-sm-offset-3">
			<div class="col-xs-12 col-sm-6">	

<?php 
if(isset($_GET['login']) && $_GET['login'] == 'failed')
{
	?>
<div class="alert alert-danger" role="alert"><?php _e("Login failed: You have entered an incorrect Username or password, pease try again.", "pluginhunt"); ?></div>

	<?php
} ?>

			    <form class="omb_loginForm" name="loginform" id="loginform" action="<?php echo $surl; ?>/wp-login.php?action=login" autocomplete="off" method="POST">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input type="text" class="form-control" name="log" placeholder="Username">
					</div>
					<span class="help-block"></span>
										
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-lock"></i></span>
						<input  type="password" class="form-control" name="pwd" placeholder="Password">
					</div>
                    <span class="help-block"></span>

                    <?php do_action( 'login_form' ); ?>

					<input type="hidden" name="redirect_to" value="<?php echo $surl; ?>/login" />


					<button class="btn btn-lg btn-danger btn-block" type="submit"><?php _e('Login','pluginhunt'); ?></button>

				</form>
			</div>
    	</div>
		<div class="row omb_row-sm-offset-3">
			<div class="col-xs-12 col-sm-3">
				<label class="checkbox">
					<input type="checkbox" value="remember-me">Remember Me
				</label>
			</div>
			<div class="col-xs-12 col-sm-3">
				<p class="omb_forgotPwd">
					<a href="#">Forgot password?</a>
				</p>
			</div>
		</div>	   


		<div class = 'ph_bt'><?php _e("Back to ", "pluginhunt"); ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo('name'); ?></a></div> 	
	</div>



        </div>
