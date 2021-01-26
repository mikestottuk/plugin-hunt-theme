<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<style>
@media only screen and (max-width: 480px) {
	.sign-in{
		font-size: 25px;
	    margin-bottom: 0px;
	    margin-top: 75px;
	}
}
</style>
<div class='sign-in'>
	<?php _e('Sign into your account','pluginhunt'); ?>
</div>
<div class='clear'></div>
<div class='loginlogo'>
		<a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'><img src='<?php echo esc_url( of_get_option('main_logo') ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>
</div>
<div class="tml tml-login" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php $template->the_action_template_message( 'login' ); ?>
	<?php $template->the_errors(); ?>
	<div class='ph_socials ph_reg'>
	<form name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login' ); ?>" method="post">
		<p class="tml-user-login-wrap">
			<label for="user_login<?php $template->the_instance(); ?>"><?php
				if ( 'email' == $theme_my_login->get_option( 'login_type' ) )
					_e( 'E-mail', 'pluginhunt' );
				elseif ( 'both' == $theme_my_login->get_option( 'login_type' ) )
					_e( 'Username or E-mail', 'pluginhunt' );
				else
					_e( 'Username', 'pluginhunt' );
			?></label>
			<input type="text" name="log" id="user_login<?php $template->the_instance(); ?>" autocomplete="off" class="input" placeholder="Email or username" value="<?php $template->the_posted_value( 'log' ); ?>" size="20" />
		</p>

		<p class="tml-user-pass-wrap">
			<label for="user_pass<?php $template->the_instance(); ?>"><?php _e( 'Password', 'pluginhunt' ); ?></label>
			<input type="password" name="pwd" id="user_pass<?php $template->the_instance(); ?>" autocomplete="off" class="input" placeholder="Password" value="" size="20" autocomplete="off" />
		</p>

		<?php do_action( 'login_form' ); ?>

		<div class="tml-rememberme-submit-wrap">
			<p class="tml-rememberme-wrap">
				<input name="rememberme" type="checkbox" id="rememberme<?php $template->the_instance(); ?>" value="forever" />
				<label for="rememberme<?php $template->the_instance(); ?>"><?php esc_attr_e( 'Remember Me', 'pluginhunt' ); ?></label>
			</p>

			<p class="tml-submit-wrap">
				<input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php esc_attr_e( 'Log In', 'pluginhunt' ); ?>" />
				<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'login' ); ?>" />
				<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
				<input type="hidden" name="action" value="login" />
			</p>
		</div>
	</form>
</div>
	<?php $template->the_action_links( array( 'login' => false ) ); ?>
</div>
