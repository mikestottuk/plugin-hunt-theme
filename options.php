<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	return 'plugin-hunt-theme';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'pluginhunt'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';

	$options = array();

	if(function_exists('notifyme_header')){

		$options[] = array(
			'name' => __( 'Notifications', 'pluginhunt' ),
			'type' => 'heading'
		);

		$options[] = array(
			'name' => __( 'What is your notification logo', 'pluginhunt' ),
			'desc' => __( 'What logo do you want to appear on browser notifications (40px x 40px).', 'pluginhunt' ),
			'id' => 'notify_logo',
			'type' => 'upload'
		);

	}

	$options[] = array(
		'name' => __( 'Header', 'pluginhunt' ),
		'type' => 'heading'
	);

	

	$options[] = array(
		'name' => __( 'New Submit Button', 'pluginhunt' ),
		'desc' => __( 'Text for new post button (layout 3 only)', 'pluginhunt' ),
		'id' =>'ph_new_post_string',
		'type' => 'text'
	);	


	$options[] = array(
		'name' => __( 'Main logo', 'pluginhunt' ),
		'desc' => __( 'What is your main website logo (40px x 40px fits best).', 'pluginhunt' ),
		'id' => 'main_logo',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __( 'White logo', 'pluginhunt' ),
		'desc' => __( 'What is your main website logo (40px x 40px fits best) in white (for the footer and header on pages).', 'pluginhunt' ),
		'id' => 'white_logo',
		'type' => 'upload'
	);

	/*
	$options[] = array(
		'name' => __('Show Image to left of Menu', 'pluginhunt'),
		'desc' => __('Show Image to left of Menu','pluginhunt'),
		'id' => 'main_logo_position',
		'type' => 'checkbox'
	);
	*/

	/*
	$options[] = array(
		'name' => "Header Style",
		'desc' => "Choose your header style.",
		'id' => "ph_header_style",
		'std' => "header-1",
		'type' => "images",
		'options' => array(
			'header-1' => $imagepath . 'header-1.png',
			'header-2' => $imagepath . 'header-2.png',
		)
	);
	*/

	$options[] = array(
		'name' => __('Welcome Banner', 'pluginhunt'),
		'type' => 'heading'
	);
	
	$options[] = array(
		'name' => __( 'Show Image Slider.', 'pluginhunt' ),
		'desc' => __( 'Show Slider (layout 2 only).', 'pluginhunt' ),
		'id' =>'ph_show_slider',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Show Welcome Block.', 'pluginhunt' ),
		'desc' => __( 'Show Welcome Block for logged out users.', 'pluginhunt' ),
		'id' =>'ph_show_welcome',
		'type' => 'checkbox'
	);


	$options[] = array(
		'name' => __( 'Welcome title', 'pluginhunt' ),
		'desc' => __( 'Enter the welcome title here.', 'pluginhunt' ),
		'id' => 'ph_welcome_tit',
		'std' => 'Discover your next favourite thing.',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Welcome content', 'pluginhunt' ),
		'desc' => __( 'Enter the catch-line message logged out users will see here.', 'pluginhunt' ),
		'id' => 'ph_welcome_sub',
		'std' => 'Plugin Hunt surfaces the best new WordPress Plugins, every day. It is a place for plugin-loving enthusiasts to share and geek out about the latest WordPress Plugins.',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __( 'Welcome Image', 'pluginhunt' ),
		'desc' => __( 'Choose an image to show in the welcome banner.', 'pluginhunt' ),
		'id' => 'welcome_logo',
		'type' => 'upload'
	);




	$options[] = array(
		'name' => __( 'Color Scheme', 'pluginhunt' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Main Theme Colour', 'pluginhunt' ),
		'desc' => __( '#da552f by default.', 'pluginhunt' ),
		'id' => 'ph_main_color',
		'std' => '#da552f',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Top Menu Colour', 'pluginhunt' ),
		'desc' => __( '#ffffff by default.', 'pluginhunt' ),
		'id' => 'ph_menu_color',
		'std' => '#ffffff',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Secondary Colour', 'pluginhunt' ),
		'desc' => __( '#efefef selected by default.', 'pluginhunt' ),
		'id' => 'ph_secondary_color',
		'std' => '#efefef',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'New Post Header', 'pluginhunt' ),
		'desc' => __( '#5898f1 selected by default.', 'pluginhunt' ),
		'id' => 'ph_newpost_header',
		"std" => "#5898f1",
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'New Discussion Header', 'pluginhunt' ),
		'desc' => __( '#4dc667 selected by default.', 'pluginhunt' ),
		'id' => 'ph_discuss_header',
		"std" => "#4dc667",
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'New Woo Product Header', 'pluginhunt' ),
		'desc' => __( '#f5a623 selected by default.', 'pluginhunt' ),
		'id' => 'ph_woo_header',
		"std" => "#f5a623",
		'type' => 'color'
	);


	$options[] = array(
		'name' => __( 'Not Voted Colour', 'pluginhunt' ),
		'desc' => __( 'Arrow and score colour if not voted.', 'pluginhunt' ),
		'id' => 'ph_novote',
		'std' => "#000000",
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Voted Colour', 'pluginhunt' ),
		'desc' => __( 'Arrow and score colour if voted.', 'pluginhunt' ),
		'id' => 'ph_vote',
		'std' => '#5898f1',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Flyout Voted Colour', 'pluginhunt' ),
		'desc' => __( 'Arrow and score colour if voted on the flyout.', 'pluginhunt' ),
		'id' => 'ph_vote_fly',
		'std' => '#5898f1',
		'type' => 'color'
	);


	$options[] = array(
		'name' => __( 'Score Colour', 'pluginhunt' ),
		'desc' => __( 'Score colour if voted.', 'pluginhunt' ),
		'id' => 'ph_score',
		'std' => '#ffffff',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( '"GET IT" color', 'pluginhunt' ),
		'desc' => __( 'Color of the word "GET IT" on single post page.', 'pluginhunt' ),
		'id' => 'ph_get_it_color',
		'std' => '#ffffff',
		'type' => 'color'
	);



	$options[] = array(
		'name' => __( 'Layout', 'pluginhunt' ),
		'type' => 'heading'
	);


	$options[] = array(
		'name' => "Index Layout Style",
		'desc' => "Choose your layout style.",
		'id' => "ph_layout_style",
		'std' => "index-1",
		'type' => "images",
		'options' => array(
			'index-1' => $imagepath . 'plugin-hunt-theme-layout-1.png',
			'index-2' => $imagepath . 'plugin-hunt-theme-layout-2.png',
			'index-3' => $imagepath . 'plugin-hunt-theme-layout-3.png',			
			'index-4' => $imagepath . 'plugin-hunt-theme-layout-4.png',
		)
	);

	$options[] = array(
		'name' => "Page Layout Style",
		'desc' => "Choose your layout style.",
		'id' => "ph_page_layout_style",
		'std' => "page-1",
		'type' => "images",
		'options' => array(
			'page-1' => $imagepath . 'page-1.png',
			'page-2' => $imagepath . 'page-2.png',
		)
	);

	$options[] = array(
		'name' => __( 'Footer', 'pluginhunt' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Call to action pre text', 'pluginhunt' ),
		'desc' => __( 'Text to input before the CTA button.', 'pluginhunt' ),
		'id' =>'ph_footer_cta',
		'type' => 'text'
	);	

	$options[] = array(
		'name' => __( 'Call to action text', 'pluginhunt' ),
		'desc' => __( 'e.g. Buy Now.', 'pluginhunt' ),
		'id' =>'ph_footer_cta_but',
		'type' => 'text'
	);	

	$options[] = array(
		'name' => __( 'Call to action link', 'pluginhunt' ),
		'desc' => __( 'e.g. http://pluginhunt.com/pricing/.', 'pluginhunt' ),
		'id' =>'ph_footer_cta_link',
		'type' => 'text'
	);	

	$options[] = array(
		'name' => __( 'Copyright text', 'pluginhunt' ),
		'desc' => __( 'Copyright text.', 'pluginhunt' ),
		'id' =>'ph_footer_copyright',
		'type' => 'text'
	);	

	$options[] = array(
		'name' => __( 'Talk to us HTML', 'pluginhunt' ),
		'desc' => __( 'e.g. call us (0800) 123 456.', 'pluginhunt' ),
		'id' =>'ph_footer_talk',
		'type' => 'text'
	);	






	$options[] = array(
		'name' => __( 'General', 'pluginhunt' ),
		'type' => 'heading'
	);

	//Placeholder Image
	$options[] = array(
		'name' => __( 'Placeholder Image', 'pluginhunt' ),
		'desc' => __( 'This image will be displayed on every post when a featured image is not defined.', 'pluginhunt' ),
		'id' => 'placeholder_image',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => "Group By",
		'desc' => "Group posts by day or month.",
		'id' => "ph_post_group",
		'std' => "ph-group-day",
		'type' => "select",
		'options' => array(
			'ph-group-day' => 'Day',
			'ph-group-month' => 'Month',
		)
	);

	$options[] = array(
		'name' => __("Submit Content Option", "pluginhunt"),
		'desc' => __("Use the built in form or use your own page.","pluginhunt"),
		'id' => "ph_post_submit_option",
		'std' => "one",
		'type' => "select",
		'options' => array(
			'one' => 'Built in Form',
			'two' => 'Own Page'
		)
	);

	$options[] = array(
		'name' => __( 'Own Page URL', 'pluginhunt' ),
		'desc' => __( 'Enter the URL for the page you want to redirect to for content submissions', 'pluginhunt' ),
		'id' => 'ph_post_submit_page',
		'std' => '',
		'type' => 'text'
	);	

	$options[] = array(
		'name' => "Post Flyout",
		'desc' => "Which post flyout mode do you need (none = redirect to post). Video flyout only on layout 3",
		'id' => "ph_post_flyout",
		'std' => "one",
		'type' => "select",
		'options' => array(
			'one' => 'Classic Fly Out',
			'two' => 'Video Fly Out',
			'three' => 'None if WooCommerce',
			'four' => 'None'
		)
	);


	$options[] = array(
		'name' => __( 'Get it wording', 'pluginhunt' ),
		'desc' => __( 'Enter the wording for get it.', 'pluginhunt' ),
		'id' => 'ph_get_it',
		'std' => 'Get it',
		'type' => 'text'
	);	

	$options[] = array(
		'name' => __( 'Logged out users title', 'pluginhunt' ),
		'desc' => __( 'Enter the title message logged out users will see here.', 'pluginhunt' ),
		'id' => 'ph_logged_out_tit',
		'std' => 'Plugin Hunt surfaces the best new WordPress plugins, every day.',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Logged out users paragraph', 'pluginhunt' ),
		'desc' => __( 'Enter the catch-line message logged out users will see here.', 'pluginhunt' ),
		'id' => 'ph_logged_out_sub',
		'std' => 'It is a place for WordPress lovers to share great WordPress plugins for sale on CodeCanyon.',
		'type' => 'textarea'
	);



	$options[] = array(
		'name' => __( 'Load More', 'pluginhunt' ),
		'desc' => __( 'Enter the message when loading more content.', 'pluginhunt' ),
		'id' => 'ph_load_more',
		'std' => 'Hunting down older plugins...',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'From email', 'pluginhunt' ),
		'desc' => __( 'Enter the from email for emails sent from your site (default "WordPress")', 'pluginhunt' ),
		'id' => 'ph_from_email',
		'std' => 'noreply@pluginhunt.com',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Hunting (singluar)', 'pluginhunt' ),
		'desc' => __( 'Enter the singular term for your hunts', 'pluginhunt' ),
		'id' => 'ph_hunt_single',
		'std' => 'plugin',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Hunting (plural)', 'pluginhunt' ),
		'desc' => __( 'Enter the plural term for your hunts', 'pluginhunt' ),
		'id' => 'ph_hunt_plural',
		'std' => 'plugins',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Message when all content loaded', 'pluginhunt' ),
		'desc' => __( 'When all content has loaded', 'pluginhunt' ),
		'id' => 'ph_hunt_loaded',
		'std' => 'No more plugins...',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Show details of product', 'pluginhunt' ),
		'desc' => __( 'Show the details area of the product.', 'pluginhunt' ),
		'id' => 'ph_detail_on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Disclaimer', 'pluginhunt' ),
		'desc' => __( 'Puts a disclaimer on your site', 'pluginhunt' ),
		'id' => 'ph_hunt_disclaimer',
		'std' => 'Not affiliated with Product Hunt',
		'type' => 'textarea'
	);


	if(of_get_option('ph_layout_style') != 'index-3'){

	$options[] = array(
		'name' => __( 'Grid', 'pluginhunt' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Grid Layout', 'pluginhunt' ),
		'desc' => __( 'Grid Layout.', 'pluginhunt' ),
		'id' => 'ph_grid_on',
		'type' => 'checkbox'
	);

	}

/*
	$options[] = array(
		'name' => __( 'Hide Grid Toggle', 'pluginhunt' ),
		'desc' => __( 'Show the option to toggle.', 'pluginhunt' ),
		'id' =>'ph_grid_toggle',
		'type' => 'checkbox'
	);
*/


	$options[] = array(
		'name' => __( 'Collections', 'pluginhunt' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Sticky Collections', 'pluginhunt' ),
		'desc' => __( 'Show the collections slide down banner.', 'pluginhunt' ),
		'id' =>'ph_sticky_on',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Featured Collections Tagline', 'pluginhunt' ),
		'desc' => __( 'On the /collections/ url show this text.', 'pluginhunt' ),
		'id' =>'ph_collect_sub',
		'type' => 'text'
	);



	$options[] = array(
		'name' => __('User Profile','pluginhunt'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'User Profile Link', 'pluginhunt' ),
		'desc' => __( 'Slug for the edit profile link', 'pluginhunt' ),
		'id' => 'ph_edit_profile',
		'std' => 'your-profile/',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __('Discussions','pluginhunt'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Enable Discussions', 'pluginhunt' ),
		'desc' => __( 'Enable discussions section of pluginhunt.', 'pluginhunt' ),
		'id' =>'ph_enable_discussions',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __('Marketplace','pluginhunt'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Enable Marketplace', 'pluginhunt' ),
		'desc' => __( 'To do this you need a multi vendor plugin', 'pluginhunt' ),
		'id' =>'ph_enable_marketplace',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __('Lock Out','pluginhunt'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Lockout', 'pluginhunt' ),
		'desc' => __( 'Message for people who cannot submit content.', 'pluginhunt' ),
		'id' => 'ph_lockout',
		'std' => 'We are a community of product enthusiasts. To protect the integrity of our website only invited members can submit content.',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __( 'FAQ', 'pluginhunt' ),
		'desc' => __( 'Link to your faq page with information about invites etc.', 'pluginhunt' ),
		'id' => 'ph_faq',
		'std' => 'http://pluginhunt.com/faqs/',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __('New Post Form','pluginhunt'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Product Title', 'pluginhunt' ),
		'desc' => __( 'Title for new product post form.', 'pluginhunt' ),
		'id' => 'ph_newpost_title',
		'std' => 'Post a new product',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Product Sub Title', 'pluginhunt' ),
		'desc' => __( 'sub title for new product post form.', 'pluginhunt' ),
		'id' => 'ph_newpost_subtitle',
		'std' => 'Found something cool? Hunt it!',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Hunting..', 'pluginhunt' ),
		'desc' => __( 'Text for the on submit button change.', 'pluginhunt' ),
		'id' => 'ph_hunting_string',
		'std' => 'Hunting..',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Discuss Title', 'pluginhunt' ),
		'desc' => __( 'Title for new discussion form.', 'pluginhunt' ),
		'id' => 'ph_newdis_title',
		'std' => 'Submit a discussion',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Discuss Sub Title', 'pluginhunt' ),
		'desc' => __( 'sub title for new discussion form.', 'pluginhunt' ),
		'id' => 'ph_newdis_subtitle',
		'std' => 'Lets talk about this',
		'type' => 'text'
	);	

	$options[] = array(
		'name' => __( 'Woo Product Title', 'pluginhunt' ),
		'desc' => __( 'Title for new woo product form.', 'pluginhunt' ),
		'id' => 'ph_newwoo_title',
		'std' => 'Submit a discussion',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Woo Product Sub Title', 'pluginhunt' ),
		'desc' => __( 'sub title for new woo product form.', 'pluginhunt' ),
		'id' => 'ph_newwoo_subtitle',
		'std' => 'Lets talk about this',
		'type' => 'text'
	);	


	//show full content or tagline input box...
	$options[] = array(
		'name' => __( 'Show Tag Line', 'pluginhunt' ),
		'desc' => __( 'Show Tag Line or Full Content Input (On = tagline).', 'pluginhunt' ),
		'id' =>'ph_full_content',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Show Product Type Dropdown.', 'pluginhunt' ),
		'desc' => __( 'Show Product Type Dropdown.', 'pluginhunt' ),
		'id' =>'ph_type_drop',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Show Product Availibility Dropdown.', 'pluginhunt' ),
		'desc' => __( 'Show Product Availibility Dropdown.', 'pluginhunt' ),
		'id' =>'ph_type_avail',
		'type' => 'checkbox'
	);


	$options[] = array(
		'name' => __( 'Button call to action', 'pluginhunt' ),
		'desc' => __( 'Enter your button call to action here.', 'pluginhunt' ),
		'id' => 'ph_newpost_cta',
		'std' => 'Hunt it',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __('Mail Chimp','pluginhunt'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Mailchimp subscribe box **Must be on PHP 5.3 or higher to use**', 'pluginhunt' ),
		'desc' => __( 'Do you want to use the Mailchimp subscribe box.', 'pluginhunt' ),
		'id' => 'mailchimp_showhidden',
		'type' => 'checkbox'
	);
	
	// $options[] = array(
		// 'name' => __( 'Mailchimp Form Action URL', 'pluginhunt' ),
		// 'desc' => __( 'Enter your mailchimp sign up form action URL.', 'pluginhunt' ),
		// 'id' => 'mailchimp_action_hidden',
		// 'std' => '',
		// 'class' => 'hidden',
		// 'type' => 'text'
	// );


	$options[] = array(
		'name' => __( 'Sidebar title', 'pluginhunt' ),
		'desc' => __( 'Enter the sidebar title.', 'pluginhunt' ),
		'id' => 'mc_sidebar_title',
		'std' => 'Plugins direct to your inbox!',
		'type' => 'text'
	);
	

	$options[] = array(
		'name' => __( 'Email capture', 'pluginhunt' ),
		'desc' => __( 'Enter the message above the email capture.', 'pluginhunt' ),
		'id' => 'ph_email_capture',
		'std' => 'Get the best new plugin discoveries in your inbox weekly!',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => __('MailChimp API Key', 'pluginhunt'),
		'desc' => __('Enter your MailChimp API Key', 'pluginhunt'),
		'id' => 'mailchimp_apikey_hidden',
		'std' => '',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => __('MailChimp List ID', 'pluginhunt'),
		'desc' => __('Enter your MailChimp List ID', 'pluginhunt'),
		'id' => 'mailchimp_listid_hidden',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __('MailChimp Success Message', 'pluginhunt'),
		'desc' => __('Message to show on success', 'pluginhunt'),
		'id' => 'ph_mc_msg',
		'std' => '',
		'type' => 'text'
	);



	$options[] = array(
		'name' => __( 'Blog Layout', 'pluginhunt' ),
		'type' => 'heading'
	);


	$options[] = array(
		'name' => __( 'Blog Logo', 'pluginhunt' ),
		'desc' => __( 'Logo to display on the blog page.', 'pluginhunt' ),
		'id' => 'ph_blog_logo',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __( 'Blog title', 'pluginhunt' ),
		'desc' => __( 'The title for your blog.', 'pluginhunt' ),
		'id' => 'ph_blog_title',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Blog tagline', 'pluginhunt' ),
		'desc' => __( 'The tagline for your blog.', 'pluginhunt' ),
		'id' => 'ph_blog_tag',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Blog footer call to action', 'pluginhunt' ),
		'desc' => __( 'The text in the footer button.', 'pluginhunt' ),
		'id' => 'ph_blog_cta',
		'std' => '',
		'type' => 'text'
	);

/*
	$options[] = array(
		'name' => "Blog Layout",
		'desc' => "Images for layout.",
		'id' => "ph_blog_layout",
		'std' => "1col-fixed",
		'type' => "images",
		'options' => array(
			'1col-fixed' => $imagepath . '1col.png',
			'2c-l-fixed' => $imagepath . '2cl.png',
			'2c-r-fixed' => $imagepath . '2cr.png'
		)
	);
*/


	$options[] = array(
		'name' => __( 'Custom CSS', 'pluginhunt' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Custom CSS for desktop', 'pluginhunt' ),
		'desc' => __( 'Enter your custom CSS for desktop here.', 'pluginhunt' ),
		'id' => 'ph_custom_css',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __( 'Custom CSS for mobile', 'pluginhunt' ),
		'desc' => __( 'Enter your custom CSS for mobile here.', 'pluginhunt' ),
		'id' => 'ph_mobile_custom_css',
		'std' => '',
		'type' => 'textarea'
	);


	$options[] = array(
		'name' => __( 'Google Analytics', 'pluginhunt' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Tracking code', 'pluginhunt' ),
		'desc' => __( 'Enter your analytics tracking code here (without the &#x3C;script&#x3E;&#x3C;/script&#x3E; tags).', 'pluginhunt' ),
		'id' => 'ph_ga',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __('Socials','pluginhunt'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Twitter Handle', 'pluginhunt' ),
		'desc' => __( 'Enter your twitter handle here e.g. @epicplugins', 'pluginhunt' ),
		'id' => 'ph_tweet_at',
		'std' => '@epicplugins',
		'type' => 'text'
	);


	return $options;
}