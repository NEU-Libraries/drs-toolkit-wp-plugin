<?php

/**
	ReduxFramework Config File
	For full documentation, please visit http://reduxframework.com/docs/
**/


/*
 *
 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for URLs, and directories will NOT be available at this point in a child theme,
 * so you must use get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function redux_add_another_section($sections){
    //$sections = array();
    $sections[] = array(
        'title' => __('A Section added by hook', 'redux-framework'),
        'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework'),
		'icon' => 'paper-clip',
		'icon_class' => 'icon-large',
        // Leave this as a blank section, no options just some intro text set above.
        'fields' => array()
    );

    return $sections;
}
add_filter('redux-opts-sections-redux-sample', 'redux_add_another_section');


/*
 * 
 * Custom function for filtering the args array given by a theme, good for child themes to override or add to the args array.
 *
 */
function redux_change_framework_args($args){
    //$args['dev_mode'] = false;
    
    return $args;
}
//add_filter('redux-opts-args-redux-sample-file', 'redux_change_framework_args');


/*
 *
 * Most of your editing will be done in this section.
 *
 * Here you can override default values, uncomment args and change their values.
 * No $args are required, but they can be over ridden if needed.
 *
 */
function redux_setup_framework_options(){

    $args = array();


    // For use with a tab below
		$tabs = array();

		ob_start();

		$ct = wp_get_theme();
        $theme_data = $ct;
        $item_name = $theme_data->get('Name'); 
		$tags = $ct->Tags;
		$screenshot = $ct->get_screenshot();
		$class = $screenshot ? 'has-screenshot' : '';

		$customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'redux-framework' ), $ct->display('Name') );

		?>
		<div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
			<?php if ( $screenshot ) : ?>
				<?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
				<a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr( $customize_title ); ?>">
					<img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
				</a>
				<?php endif; ?>
				<img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
			<?php endif; ?>

			<h4>
				<?php echo $ct->display('Name'); ?>
			</h4>

			<div>
				<ul class="theme-info">
					<li><?php printf( __('By %s', 'redux-framework'), $ct->display('Author') ); ?></li>
					<li><?php printf( __('Version %s', 'redux-framework'), $ct->display('Version') ); ?></li>
					<li><?php echo '<strong>'.__('Tags', 'redux-framework').':</strong> '; ?><?php printf( $ct->display('Tags') ); ?></li>
				</ul>
				<p class="theme-description"><?php echo $ct->display('Description'); ?></p>
				<?php if ( $ct->parent() ) {
					printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'redux-framework' ) . '</p>',
						__( 'http://codex.wordpress.org/Child_Themes', 'redux-framework' ),
						$ct->parent()->display( 'Name' ) );
				} ?>
				
			</div>

		</div>

		<?php
		$item_info = ob_get_contents();
		    
		ob_end_clean();

    // Setting dev mode to true allows you to view the class settings/info in the panel.
    // Default: true
    // $args['dev_mode'] = true;

	// Set the icon for the dev mode tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// If $args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: info-sign
	//$args['dev_mode_icon'] = 'info-sign';

	// Set the class for the dev mode tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
    $args['dev_mode_icon_class'] = 'icon-large';

    // Set a custom option name. Don't forget to replace spaces with underscores!
    $args['opt_name'] = 'redux';

    // Setting system info to true allows you to view info useful for debugging.
    // Default: true
     $args['system_info'] = false;

    
	// Set the icon for the system info tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// If $args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: info-sign
	//$args['system_info_icon'] = 'info-sign';

	// Set the class for the system info tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
	$args['system_info_icon_class'] = 'icon-large';

	$theme = wp_get_theme();

	$args['display_name'] = $theme->get('Name');
	//$args['database'] = "theme_mods_expanded";
	$args['display_version'] = $theme->get('Version');

    // If you want to use Google Webfonts, you MUST define the api key.
    $args['google_api_key'] = 'AIzaSyAX_2L_UzCDPEnAHTG7zhESRVpMPS4ssII';

    // Define the starting tab for the option panel.
    // Default: '0';
    //$args['last_tab'] = '0';

    // Define the option panel stylesheet. Options are 'standard', 'custom', and 'none'
    // If only minor tweaks are needed, set to 'custom' and override the necessary styles through the included custom.css stylesheet.
    // If replacing the stylesheet, set to 'none' and don't forget to enqueue another stylesheet!
    // Default: 'standard'
    //$args['admin_stylesheet'] = 'standard';

    // Setup custom links in the footer for share icons
    $args['share_icons']['twitter'] = array(
        'link' => 'http://twitter.com/thinkupthemes',
        'title' => 'Follow on Twitter', 
        'img' => REDUX_URL . 'assets/img/social/Twitter.png'
    );
    $args['share_icons']['facebook'] = array(
        'link' => 'http://www.facebook.com/thinkupthemes',
        'title' => 'Join on Facebook', 
        'img' => REDUX_URL . 'assets/img/social/Facebook.png'
    );

    // Enable the import/export feature.
    // Default: true
    // $args['show_import_export'] = false;

	// Set the icon for the import/export tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// If $args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: refresh
	//$args['import_icon'] = 'refresh';

	// Set the class for the import/export tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
	$args['import_icon_class'] = 'icon-large';

    // Set a custom menu icon.
    //$args['menu_icon'] = '';

    // Set a custom title for the options page.
    // Default: Options
    $args['menu_title'] = __('Theme Options', 'redux-framework');

    // Set a custom page title for the options page.
    // Default: Options
    $args['page_title'] = __('Options', 'redux-framework');

    // Set a custom page slug for options page (wp-admin/themes.php?page=***).
    // Default: redux_options
    $args['page_slug'] = 'redux_options';

    $args['default_show'] = true;
    $args['default_mark'] = '*';

    // Set a custom page capability.
    // Default: manage_options
    //$args['page_cap'] = 'manage_options';

    // Set the menu type. Set to "menu" for a top level menu, or "submenu" to add below an existing item.
    // Default: menu
    // $args['page_type'] = 'submenu';

    // Set the parent menu.
    // Default: themes.php
    // A list of available parent menus is available at http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    //$args['page_parent'] = 'options_general.php';

    // Set a custom page location. This allows you to place your menu where you want in the menu order.
    // Must be unique or it will override other items!
    // Default: null
    //$args['page_position'] = null;

    // Set a custom page icon class (used to override the page icon next to heading)
    //$args['page_icon'] = 'icon-themes';

	// Set the icon type. Set to "iconfont" for Font Awesome, or "image" for traditional.
	// Redux no longer ships with standard icons!
	// Default: iconfont
	//$args['icon_type'] = 'image';

    // Disable the panel sections showing as submenu items.
    // Default: true
    $args['allow_sub_menu'] = false;
        
    // Set ANY custom page help tabs, displayed using the new help tab API. Tabs are shown in order of definition.
/*    $args['help_tabs'][] = array(
        'id' => 'redux-opts-1',
        'title' => __('Theme Information 1', 'redux-framework'),
        'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework')
    );
    $args['help_tabs'][] = array(
        'id' => 'redux-opts-2',
        'title' => __('Theme Information 2', 'redux-framework'),
        'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework')
    );

    // Set the help sidebar for the options page.                                        
    $args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework');
*/

    // Add HTML before the form.
/*    if (!isset($args['global_variable']) || $args['global_variable'] !== false ) {
    	if (!empty($args['global_variable'])) {
    		$v = $args['global_variable'];
    	} else {
    		$v = str_replace("-", "_", $args['opt_name']);
    	}
    	$args['intro_text'] = __('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$'.$v.'</strong></p>', 'redux-framework');
    } else {
    	$args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework');
    }
*/
    // Add content after the form.
/*    $args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework');
*/
    // Set footer/credit line.
    $args['footer_credit'] = __('<p>Thank you for creating with <a href="http://wordpress.org/">WordPress</a></p>', 'redux-framework');

    $sections = array();              

    //Background Patterns Reader
    $sample_patterns_path = REDUX_DIR . 'sample/patterns/';
    $sample_patterns_url  = REDUX_URL . 'sample/patterns/';
    $sample_patterns      = array();

    if ( is_dir( $sample_patterns_path ) ) :
    	
      if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
      	$sample_patterns = array();

        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

          if( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
          	$name = explode(".", $sample_patterns_file);
          	$name = str_replace('.'.end($name), '', $sample_patterns_file);
          	$sample_patterns[] = array( 'alt'=>$name,'img' => $sample_patterns_url . $sample_patterns_file );
          }
        }
      endif;
    endif;



	/*-----------------------------------------------------------------------------------
	 	1.	General Settings
	-----------------------------------------------------------------------------------*/	

	$sections[] = array(
		'title' => __('General Settings', 'redux-framework'),
		'header' => __('Welcome to the Simple Options Framework Demo', 'redux-framework'),
		'desc' => __('<span class="redux-title">Logo & Favicon Settings</span>', 'redux-framework'),
		'icon_class' => 'icon-large',
		'icon' => 'wrench',
		'fields' => array(

			array(
				'title' => __('Logo Settings', 'redux-framework'), 
				'subtitle' => __('If you have an image logo you can upload it, otherwise you can display a text site title', 'redux-framework'),
				'id'=>'thinkup_general_logoswitch',
				'type' => 'radio',
				'options' => array( 
					'option1' => 'Custom Image Logo', 
					'option2' => 'Display Site Title',
					),
				),

			array(
				'title' => __('Custom Image Logo', 'redux-framework'),
				'subtitle'=> __('Upload image logo or specify the image url.<br />Name the logo image logo.png.', 'redux-framework'),
				'id'=>'thinkup_general_logolink',
				'type' => 'media',
				'url'=> true,
				),

			array(
				'title' => __('Custom Image Logo (Retina display)', 'redux-framework'),
				'subtitle'=> __('Upload a logo image twice the size of logo.png. Name the logo image logo@2x.png.', 'redux-framework'),
				'id'=>'thinkup_general_logolinkretina',
				'type' => 'media',
				'url'=> true,
				),

			array(
				'title' => __('Site Title', 'redux-framework'),
				'subtitle' => __('Input a message to display as your site title. Leave blank to display your default site title.', 'redux-framework'),
				'id'=>'thinkup_general_sitetitle',
				'type' => 'text',
				'validate' => 'html',
				),

			array(
				'title' => __('Site Description', 'redux-framework'),
				'subtitle' => __('Input a message to display as site description. Leave blank to display default site description.', 'redux-framework'),
				'id'=>'thinkup_general_sitedescription',
				'type' => 'text',
				'validate' => 'html',
				),

			array(
				'title' => __('Custom Favicon', 'redux-framework'),
				'subtitle'=> __('Uploads favicon or specify the favicon url.', 'redux-framework'),
				'id'=>'thinkup_general_faviconlink',
				'type' => 'media',
				'url'=> true,
				),

			array(
				'id'=>'info_page_structure',
				'type'=>'info',
				'style'=>'help',
				'desc' => __( '<span class="redux-title">Page Structure</span>', 'redux-framework')
				),

			array(
				'title' => __('Page Layout', 'redux-framework'), 
				'subtitle' => __('Select page layout. This will only be applied to published Pages (I.e. Not posts, blog or home).', 'redux-framework'),
				'id'=>'thinkup_general_layout',
				'type' => 'image_select',
				'compiler'=>true,
				'default' => '0',
				'options' => array(
						'option1' => array('alt' => '1 Column', 'img' => REDUX_URL.'assets/img/1col.png'),
						'option2' => array('alt' => '2 Column Left', 'img' => REDUX_URL.'assets/img/2cl.png'),
						'option3' => array('alt' => '2 Column Right', 'img' => REDUX_URL.'assets/img/2cr.png'),
					),
				),

			array(
				'title' => __('Select a Sidebar', 'redux-framework'), 
				'subtitle' => __('Choose a sidebar to use with the page layout.', 'redux-framework'),
				'id'=>'thinkup_general_sidebars',
				'type' => 'select',
				'data' => 'sidebars',
				),

			array(
				'title' => __('Enable Fixed Layout', 'redux-framework'), 
				'subtitle' => __('Check to enable fixed layout.<br />(i.e. Disable responsive layout)', 'redux-framework'),
				'id'=>'thinkup_general_fixedlayoutswitch',
				'type' => 'switch',
				),

			array(
				'title' => __('Enable Breadcrumbs', 'redux-framework'), 
				'subtitle' => __('Switch on to enable breadcrumbs.', 'redux-framework'),
				'id'=>'thinkup_general_breadcrumbswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),

			array(
				'title' => __('Breadcrumb Delimiter', 'redux-framework'),
				'subtitle' => __('Specify a custom delimiter to use instead of the default &#40; / &#41; when displaying breadcrumbs.', 'redux-framework'),
				'default' => '/',
				'id'=>'thinkup_general_breadcrumbdelimeter',
				'type' => 'text',
				'validate' => 'html',
				'fold' => array('thinkup_general_breadcrumbswitch'=>1),
				),


			array(
				'id'=>'info_page_structure',
				'type'=>'info',
				'style'=>'help',
				'desc' => __( '<span class="redux-title">Custom Code</span>', 'redux-framework')
				),

			array(
				'title' => __('Custom CSS', 'redux-framework'), 
				'subtitle' => __('Developers can use this to apply custom css. Use this to control, by styling of any element on the webpage by targeting id&#39;s and classes.', 'redux-framework'),
				'id'=>'thinkup_general_customcss',
				'type' => 'textarea',
				'validate' => 'css',
				),

			array(
				'title' => __('Custom jQuery - Front End', 'redux-framework'),
				'subtitle' => __('Developers can use this to apply custom jQuery which will only affect the front end of the website.<br /><br />Use this to control your site by adding great jQuery features.', 'redux-framework'),
				'id'=>'thinkup_general_customjavafront',
				'type' => 'textarea',
				'validate' => 'html',
				),

		)
	);

	$sections[] = array(
		'type' => 'divide',
	);


	/*-----------------------------------------------------------------------------------
	 	2.1.	Home Settings				
	-----------------------------------------------------------------------------------*/

	$sections[] = array(
		'title' => __('Homepage', 'redux-framework'),
		'desc' => __('<span class="redux-title">Control Homepage Layout</span>', 'redux-framework'),
		'icon_class' => 'icon-large',
		'icon' => 'home',
		'fields' => array(

			array(
				'title' => __('Homepage Layout', 'redux-framework'), 
				'subtitle' => __('Select page layout. This will only be applied to the home page.', 'redux-framework'),
				'id'=>'thinkup_homepage_layout',
				'type' => 'image_select',
				'compiler'=>true,
				'default' => '0',
				'options' => array(
						'option1' => array('alt' => '1 Column', 'img' => REDUX_URL.'assets/img/1col.png'),
						'option2' => array('alt' => '2 Column Left', 'img' => REDUX_URL.'assets/img/2cl.png'),
						'option3' => array('alt' => '2 Column Right', 'img' => REDUX_URL.'assets/img/2cr.png'),
					),
				),

			array(
				'title' => __('Select a Sidebar', 'redux-framework'), 
				'subtitle' => __('Choose a sidebar to use with the layout.', 'redux-framework'),
				'id'=>'thinkup_homepage_sidebars',
				'type' => 'select',
				'data' => 'sidebars',
				),

			array(
				'id'=>'info_page_structure',
				'type'=>'info',
				'style'=>'help',
				'desc' => __( '<span class="redux-title">Homepage Slider</span>', 'redux-framework')
				),

			array(
				'title' => __('Enable Homepage Slider', 'redux-framework'), 
				'subtitle' => __('Switch on to enable home page slider.', 'redux-framework'),
				'id'=>'thinkup_homepage_sliderswitch',
				'type' => 'button_set',
				'options' => array(
					'option1' => 'ThinkUpSlider',
					'option2' => 'Custom Slider',
					'option3' => 'Disable'
					),//Must provide key => value pairs for radio options
				'default' => 'option1'
				),

			array(
				'title' => __('Homepage Slider Shortcode', 'redux-framework'), 
				'subtitle' => __('Input the shortcode of the slider you want to display. I.e. [shortcode_name].', 'redux-framework'),
				'id'=>'thinkup_homepage_slidername',
				'type' => 'text',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				),

			array(
				'title' => __('Built-In Slider', 'redux-framework'),
				'subtitle'=> __('Unlimited slides with drag and drop sortings.', 'redux-framework'),
				'id'=>'thinkup_homepage_sliderpreset',
				'type' => 'slides',
				),

			array(
                'title' => __('Enable Full-Width Slider', 'redux-framework'),
				'subtitle' => __('Switch on to enable full-width slider.', 'redux-framework'),
				'id'=>'thinkup_homepage_sliderpresetwidth',
				'type' => 'switch',
				'default' => '1'// 1 = on | 0 = off
				),

			array(
				'id'=>'thinkup_homepage_sliderpresetheight',
				'type' => 'slider', 
				'title' => __('Slider Height (Max)', 'redux-framework-demo'),
				'subtitle'=> __('Specify the maximum slider height (px).', 'redux-framework'),
				"default" => "350",
				"min"     => "200",
				"step"    => "5",
				"max"     => "500",
				),

			array(
				'id'=>'info_page_structure',
				'type'=>'info',
				'style'=>'help',
				'desc' => __( '<span class="redux-title">Call To Action - Intro</span>', 'redux-framework')
				),				

			array(
				'title' => __('Message', 'redux-framework'), 
				'desc' => __('Check to enable intro on home page.', 'redux-framework'),
				'id'=>'thinkup_homepage_introswitch',
				'type' => 'checkbox',
				'default' => '0'// 1 = on | 0 = off
				),				

			array(
				'subtitle' => __('Enter a <strong>main</strong> message.<br /><br />This will be one of the first messages your visitors see. Use this to get their attention.', 'redux-framework'),
				'id'=>'thinkup_homepage_introaction',
				'type' => 'textarea',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				),

			array(
				'subtitle' => __('Enter a <strong>teaser</strong> message. <br /><br />Use this to provide more details about what you offer.', 'redux-framework'),
				'id'=>'thinkup_homepage_introactionteaser',
				'type' => 'textarea',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				),

			array(
				'title' => __('Button Text', 'redux-framework'), 
				'subtitle' => __('Input text to display on the action button.', 'redux-framework'),
				'id'=>'thinkup_homepage_introactionbutton',
				'type' => 'text',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				),				

			array(
				'title' => __('Link', 'redux-framework'), 
				'subtitle' => __('Specify whether the action button should link to a page on your site, out to external webpage or disable the link altogether.', 'redux-framework'),
				'id'=>'thinkup_homepage_introactionlink',
				'type' => 'radio',
				'options' => array( 
					'option1' => 'Link to a Page',
					'option2' => 'Specify Custom link',
					'option3' => 'Disable Link'
					),
				),

			array(
				'title' => __('Link to a page', 'redux-framework'), 
				'subtitle' => __('Select a target page for action button link.', 'redux-framework'),
				'id'=>'thinkup_homepage_introactionpage',
				'type' => 'select',
				'data' => 'pages',
				),

			array(
				'title' => __('Custom link', 'redux-framework'),
				'subtitle' => __('Input a custom url for the action button link.<br>Add http:// if linking to an external webpage.', 'redux-framework'),
				'id'=>'thinkup_homepage_introactioncustom',
				'type' => 'text',
				'validate' => 'url',
				),
		)
	);


	/*-----------------------------------------------------------------------------------
	 	2.2.	Home Content				
	-----------------------------------------------------------------------------------*/

	$sections[] = array(
		'title' => __('Homepage (Content)', 'redux-framework'),
		'desc' => __('<span class="redux-title">Display Pre-Designed Homepage Layout</span>', 'redux-framework'),
		'icon_class' => 'icon-large',
		'icon' => 'pencil',
		'fields' => array(

			array(
				'title' => __('Enable Pre-Made Homepage ', 'redux-framework'), 
				'subtitle' => __('switch on to enable pre-designed homepage layout.', 'redux-framework'),
				'id'=>'thinkup_homepage_sectionswitch',
				'type' => 'switch',
				'default' => '1',// 1 = on | 0 = off
			),

			array(
				'title'=> __('Content Area 1', 'redux-framework'),
				'desc'=> __('Add an image for the section background.', 'redux-framework'),
				'id'=>'thinkup_homepage_section1_image',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section1_title',
				'desc' => __('Add a title to the section.', 'redux-framework'),
				'type' => 'text',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section1_desc',
				'desc' => __('Add some text to featured section 1.', 'redux-framework'),
				'type' => 'textarea',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section1_link',
				'desc' => __('Link to a page', 'redux-framework'), 
				'type' => 'select',
				'data' => 'pages',
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'title'=> __('Content Area 2', 'redux-framework'),
				'desc'=> __('Add an image for the section background.', 'redux-framework'),
				'id'=>'thinkup_homepage_section2_image',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section2_title',
				'desc' => __('Add a title to the section.', 'redux-framework'),
				'type' => 'text',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section2_desc',
				'desc' => __('Add some text to featured section 2.', 'redux-framework'),
				'type' => 'textarea',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section2_link',
				'desc' => __('Link to a page', 'redux-framework'), 
				'type' => 'select',
				'data' => 'pages',
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'title'=> __('Content Area 3', 'redux-framework'),
				'desc'=> __('Add an image for the section background.', 'redux-framework'),
				'id'=>'thinkup_homepage_section3_image',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section3_title',
				'desc' => __('Add a title to the section.', 'redux-framework'),
				'type' => 'text',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section3_desc',
				'desc' => __('Add some text to featured section 3.', 'redux-framework'),
				'type' => 'textarea',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),

			array(
				'id'=>'thinkup_homepage_section3_link',
				'desc' => __('Link to a page', 'redux-framework'), 
				'type' => 'select',
				'data' => 'pages',
				'fold' => array('thinkup_homepage_sectionswitch'=>1),
			),
		)
	);


	/*-----------------------------------------------------------------------------------
	 	3.	Header
	-----------------------------------------------------------------------------------*/

		$sections[] = array(
		'title' => __('Header', 'redux-framework'),
		'desc' => __('<span class="redux-title">Control Header Content</span>', 'redux-framework'),
		'icon' => 'chevron-up',
		'icon_class' => 'icon-large',
		'fields' => array(

			array(
				'title' => __('Enable Search', 'redux-framework'), 
				'subtitle' => __('Switch on to enable header search.', 'redux-framework'),
				'id'=>'thinkup_header_searchswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),
				
			array(
				'title' => __('Enable Social Media Links', 'redux-framework'), 
				'subtitle' => __('Switch on to enable links to social media pages.', 'redux-framework'),
				'id'=>'thinkup_header_socialswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),

			array(
				'id'=>'info_page_structure',
				'type'=>'info',
				'style'=>'help',
				'desc' => __( '<span class="redux-title">Manage Social Media Content</span>', 'redux-framework')
				),			
				
			array(
				'title' => __('Display Message', 'redux-framework'), 
				'subtitle' => __('Add a message here. E.g. &#34;Follow Us&#34;.', 'redux-framework'),
				'id'=>'thinkup_header_socialmessage',
				'type' => 'text',
				'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
				),					

			/* Facebook social settings */
			array(
				'title' => __('Facebook', 'redux-framework'), 
				'subtitle' => __('Enable link to Facebook profile.', 'redux-framework'),
				'id'=>'thinkup_header_facebookswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),
				
			array(
				'desc' => __('Input the url to your Facebook page. <strong>Note:</strong> Add http:// as the url is an external link.', 'redux-framework'),
				'id'=>'thinkup_header_facebooklink',
				'type' => 'text',
				'validate' => 'url',
				'fold' => array('thinkup_header_facebookswitch'=>1),
				),				

			array(
				'desc' => __('Use Custom Facebook Icon', 'redux-framework'),
				'id'=>'thinkup_header_facebookiconswitch',
				'type' => 'checkbox',
				'default' => '0',// 1 = on | 0 = off
				'fold' => array('thinkup_header_facebookswitch'=>1),
				),

			array(
				'desc'=> __('Add a link to the image or upload one from your desktop. The image will be resized.', 'redux-framework'),
				'id'=>'thinkup_header_facebookcustomicon',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_header_facebookswitch'=>1),
				),

			/* Twitter social settings */
			array(
				'title' => __('Twitter', 'redux-framework'), 
				'subtitle' => __('Enable link to Twitter profile.', 'redux-framework'),
				'id'=>'thinkup_header_twitterswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),
				
			array(
				'desc' => __('Input the url to your Twitter page. <strong>Note:</strong> Add http:// as the url is an external link.', 'redux-framework'),
				'id'=>'thinkup_header_twitterlink',
				'type' => 'text',
				'validate' => 'url',
				'fold' => array('thinkup_header_twitterswitch'=>1),
				),				

			array(
				'desc' => __('Use Custom Twitter Icon', 'redux-framework'),
				'id'=>'thinkup_header_twittericonswitch',
				'type' => 'checkbox',
				'default' => '0',// 1 = on | 0 = off
				'fold' => array('thinkup_header_twitterswitch'=>1),
				),

			array(
				'desc'=> __('Add a link to the image or upload one from your desktop. The image will be resized.', 'redux-framework'),
				'id'=>'thinkup_header_twittercustomicon',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_header_twitterswitch'=>1),
				),

			/* Google+ social settings */
			array(
				'title' => __('Google+', 'redux-framework'), 
				'subtitle' => __('Enable link to Google+ profile.', 'redux-framework'),
				'id'=>'thinkup_header_googleswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),
				
			array(
				'desc' => __('Input the url to your Google+ page. <strong>Note:</strong> Add http:// as the url is an external link.', 'redux-framework'),
				'id'=>'thinkup_header_googlelink',
				'type' => 'text',
				'validate' => 'url',
				'fold' => array('thinkup_header_googleswitch'=>1),
				),				

			array(
				'desc' => __('Use Custom Google+ Icon', 'redux-framework'),
				'id'=>'thinkup_header_googleiconswitch',
				'type' => 'checkbox',
				'default' => '0',// 1 = on | 0 = off
				'fold' => array('thinkup_header_googleswitch'=>1),
				),

			array(
				'desc'=> __('Add a link to the image or upload one from your desktop. The image will be resized.', 'redux-framework'),
				'id'=>'thinkup_header_googlecustomicon',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_header_googleswitch'=>1),
				),

			/* LinkedIn social settings */
			array(
				'title' => __('LinkedIn', 'redux-framework'), 
				'subtitle' => __('Enable link to LinkedIn profile.', 'redux-framework'),
				'id'=>'thinkup_header_linkedinswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),
				
			array(
				'desc' => __('Input the url to your LinkedIn page. <strong>Note:</strong> Add http:// as the url is an external link.', 'redux-framework'),
				'id'=>'thinkup_header_linkedinlink',
				'type' => 'text',
				'validate' => 'url',
				'fold' => array('thinkup_header_linkedinswitch'=>1),
				),				

			array(
				'desc' => __('Use Custom LinkedIn Icon', 'redux-framework'),
				'id'=>'thinkup_header_linkediniconswitch',
				'type' => 'checkbox',
				'default' => '0',// 1 = on | 0 = off
				'fold' => array('thinkup_header_linkedinswitch'=>1),
				),

			array(
				'desc'=> __('Add a link to the image or upload one from your desktop. The image will be resized.', 'redux-framework'),
				'id'=>'thinkup_header_linkedincustomicon',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_header_linkedinswitch'=>1),
				),				

			/* Flickr social settings */
			array(
				'title' => __('Flickr', 'redux-framework'), 
				'subtitle' => __('Enable link to Flickr profile.', 'redux-framework'),
				'id'=>'thinkup_header_flickrswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),
				
			array(
				'desc' => __('Input the url to your Flickr page. <strong>Note:</strong> Add http:// as the url is an external link.', 'redux-framework'),
				'id'=>'thinkup_header_flickrlink',
				'type' => 'text',
				'validate' => 'url',
				'fold' => array('thinkup_header_flickrswitch'=>1),
				),				

			array(
				'desc' => __('Use Custom Flickr Icon', 'redux-framework'),
				'id'=>'thinkup_header_flickriconswitch',
				'type' => 'checkbox',
				'default' => '0',// 1 = on | 0 = off
				'fold' => array('thinkup_header_flickrswitch'=>1),
				),

			array(
				'desc'=> __('Add a link to the image or upload one from your desktop. The image will be resized.', 'redux-framework'),
				'id'=>'thinkup_header_flickrcustomicon',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_header_flickrswitch'=>1),
				),

			/* YouTube social settings */	
			array(
				'title' => __('YouTube', 'redux-framework'), 
				'subtitle' => __('Enable link to YouTube profile.', 'redux-framework'),
				'id'=>'thinkup_header_youtubeswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),
				
			array(
				'desc' => __('Input the url to your YouTube page. <strong>Note:</strong> Add http:// as the url is an external link.', 'redux-framework'),
				'id'=>'thinkup_header_youtubelink',
				'type' => 'text',
				'validate' => 'url',
				'fold' => array('thinkup_header_youtubeswitch'=>1),
				),				

			array(
				'desc' => __('Use Custom YouTube Icon', 'redux-framework'),
				'id'=>'thinkup_header_youtubeiconswitch',
				'type' => 'checkbox',
				'default' => '0',// 1 = on | 0 = off
				'fold' => array('thinkup_header_youtubeswitch'=>1),
				),

			array(
				'desc'=> __('Add a link to the image or upload one from your desktop. The image will be resized.', 'redux-framework'),
				'id'=>'thinkup_header_youtubecustomicon',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_header_youtubeswitch'=>1),
				),

			/* RSS social settings */
			array(
				'title' => __('RSS', 'redux-framework'), 
				'subtitle' => __('Enable link to RSS profile.', 'redux-framework'),
				'id'=>'thinkup_header_rssswitch',
				'type' => 'switch',
				'default' => '0'// 1 = on | 0 = off
				),
				
			array(
				'desc' => __('Input the url to your RSS page. <strong>Note:</strong> Add http:// as the url is an external link.', 'redux-framework'),
				'id'=>'thinkup_header_rsslink',
				'type' => 'text',
				'validate' => 'url',
				'fold' => array('thinkup_header_rssswitch'=>1),
				),				

			array(
				'desc' => __('Use Custom RSS Icon', 'redux-framework'),
				'id'=>'thinkup_header_rssiconswitch',
				'type' => 'checkbox',
				'default' => '0',// 1 = on | 0 = off
				'fold' => array('thinkup_header_rssswitch'=>1),
				),

			array(
				'desc'=> __('Add a link to the image or upload one from your desktop. The image will be resized.', 'redux-framework'),
				'id'=>'thinkup_header_rsscustomicon',
				'type' => 'media',
				'url'=> true,
				'fold' => array('thinkup_header_rssswitch'=>1),
				),					
		)
	);
	
	
	/*-----------------------------------------------------------------------------------
	 	4.	Footer
	-----------------------------------------------------------------------------------*/					

		$sections[] = array(
		'title' => __('Footer', 'redux-framework'),
		'desc' => __('<span class="redux-title">Control Footer Content</span>', 'redux-framework'),
		'icon' => 'chevron-down',
		'icon_class' => 'icon-large',
		'fields' => array(

			array(
				'title' => __('Footer Widgets Layout', 'redux-framework'), 
				'subtitle' => __('Select footer layout. Take complete control of the footer content by adding widgets.', 'redux-framework'),
				'id'=>'thinkup_footer_layout',
				'type' => 'image_select',
				'compiler'=>true,
				'default' => '0',
				'options' => array(
					'option1' => REDUX_URL.'assets/img/layout/footer/option01.png',
					'option2' => REDUX_URL.'assets/img/layout/footer/option02.png',
					'option3' => REDUX_URL.'assets/img/layout/footer/option03.png',
					'option4' => REDUX_URL.'assets/img/layout/footer/option04.png',
					'option5' => REDUX_URL.'assets/img/layout/footer/option05.png',
					'option6' => REDUX_URL.'assets/img/layout/footer/option06.png',
					'option7' => REDUX_URL.'assets/img/layout/footer/option07.png',
					'option8' => REDUX_URL.'assets/img/layout/footer/option08.png',
					'option9' => REDUX_URL.'assets/img/layout/footer/option09.png',
					'option10' => REDUX_URL.'assets/img/layout/footer/option10.png',
					'option11' => REDUX_URL.'assets/img/layout/footer/option11.png',
					'option12' => REDUX_URL.'assets/img/layout/footer/option12.png',
					'option13' => REDUX_URL.'assets/img/layout/footer/option13.png',
					'option14' => REDUX_URL.'assets/img/layout/footer/option14.png',
					'option15' => REDUX_URL.'assets/img/layout/footer/option15.png',
					'option16' => REDUX_URL.'assets/img/layout/footer/option16.png',
					'option17' => REDUX_URL.'assets/img/layout/footer/option17.png',
					'option18' => REDUX_URL.'assets/img/layout/footer/option18.png',
					),
				),

			array(
				'title' => __('Disable Footer Widgets', 'redux-framework'), 
				'desc' => __('Check to disable footer widgets.', 'redux-framework'),
				'id'=>'thinkup_footer_widgetswitch',
				'type' => 'checkbox',
				'default' => '0'// 1 = on | 0 = off
				),
		)
	);


	/*-----------------------------------------------------------------------------------
	 	5.	Blog
	-----------------------------------------------------------------------------------*/					

		$sections[] = array(
		'title' => __('Blog', 'redux-framework'),
		'desc' => __('<span class="redux-title">Control Blog Pages</span>', 'redux-framework'),
		'icon' => 'comment',
		'icon_class' => 'icon-large',
		'fields' => array(

			array(
				'title' => __('Blog Layout', 'redux-framework'), 
				'subtitle' => __('Select blog page layout. Only applied to the main blog page and not individual posts.', 'redux-framework'),
				'id'=>'thinkup_blog_layout',
				'type' => 'image_select',
				'compiler'=>true,
				'options' => array(
					'option1' => REDUX_URL.'assets/img/layout/blog/option01.png',
					'option2' => REDUX_URL.'assets/img/layout/blog/option02.png',
					'option3' => REDUX_URL.'assets/img/layout/blog/option03.png',
					),
				),

			array(
				'title' => __('Select a Sidebar', 'redux-framework'), 
				'subtitle' => __('<strong>Note:</strong> Sidebars will not be applied to homepage Blog. Control sidebars on the homepage from the &#39;Home Settings&#39; option.', 'redux-framework'),
				'id'=>'thinkup_blog_sidebars',
				'type' => 'select',
				'data' => 'sidebars',
				),

			array(
				'title' => __('Post Content', 'redux-framework'), 
				'subtitle' => __('Control how much content you want to show from each post on the main blog page. Remember to control the full article content by using the Wordpress <a href="http://en.support.wordpress.com/splitting-content/more-tag/">more</a> tag in your post.', 'redux-framework'),
				'id'=>'thinkup_blog_postswitch',
				'type' => 'radio',
				'options' => array( 
					'option1' => 'Show excerpt',
					'option2' => 'Show full article',
					'option3' => 'Hide article',
					),
				),

			array(
				'id'=>'info_page_structure',
				'type'=>'info',
				'style'=>'help',
				'desc' => __( '<span class="redux-title">Control Single Post Page</span>', 'redux-framework')
				),

			array(
				'title' => __('Post Layout', 'redux-framework'), 
				'subtitle' => __('Select blog page layout. This will only be applied to individual posts and not the main blog page.', 'redux-framework'),
				'id'=>'thinkup_post_layout',
				'type' => 'image_select',
				'compiler'=>true,
				'default' => 'option1',
				'options' => array(
						'option1' => array('alt' => '1 Column', 'img' => REDUX_URL.'assets/img/1col.png'),
						'option2' => array('alt' => '2 Column Left', 'img' => REDUX_URL.'assets/img/2cl.png'),
						'option3' => array('alt' => '2 Column Right', 'img' => REDUX_URL.'assets/img/2cr.png'),
					),
				),

			array(
				'title' => __('Select a Sidebar', 'redux-framework'), 
				'subtitle' => __('Choose a sidebar to use with the layout.', 'redux-framework'),
				'id'=>'thinkup_post_sidebars',
				'type' => 'select',
				'data' => 'sidebars',
				),
		)
	);


	/*-----------------------------------------------------------------------------------
		6.	Portfolio - PREMIUM FEATURE
	-----------------------------------------------------------------------------------*/					


	/*-----------------------------------------------------------------------------------
	 	7.	Contact Page - PREMIUM FEATURE
	-----------------------------------------------------------------------------------*/					


	/*-----------------------------------------------------------------------------------
		8.	Special Page - PREMIUM FEATURE
	-----------------------------------------------------------------------------------*/


	/*-----------------------------------------------------------------------------------
	 	9.	Notification Bar - PREMIUM FEATURE
	-----------------------------------------------------------------------------------*/					


	/*-----------------------------------------------------------------------------------
	 	11.	Search Engine Optimisation - PREMIUM FEATURE
	-----------------------------------------------------------------------------------*/					


	/*-----------------------------------------------------------------------------------
	 	12.	Typography - PREMIUM FEATURE
	-----------------------------------------------------------------------------------*/


	/*-----------------------------------------------------------------------------------
	 	13.	Custom Styling - PREMIUM FEATURE
	-----------------------------------------------------------------------------------*/


	/*-----------------------------------------------------------------------------------
	 	14.	Support
	-----------------------------------------------------------------------------------*/					

		$sections[] = array(
		'title' => __('Support', 'redux-framework'),
		'desc' => __('<span class="redux-title">Documentation</span><p>Please refer to the "ThinkUpThemes - Lite Documentation" file included with this theme for information on how to use the theme options. For premium support direct from the theme developers, or advice on customizations please <a href="http://www.thinkupthemes.com/themes/minamaze/" target="_blank">upgrade</a> to the Premium Theme Membership.</p>', 'redux-framework'),
		'icon' => 'user',
		'icon_class' => 'icon-large',
		'fields' => array(

		)
	);

	/*-----------------------------------------------------------------------------------
	 	15.	Upgrade
	-----------------------------------------------------------------------------------*/	

		$sections[] = array(
			'type' => 'divide',
		);

		$sections[] = array(
		'title' => __('Upgrade (10% off)', 'redux-framework'),
		'icon' => 'arrow-up',
		'icon_class' => 'icon-large',
		'fields' => array(

			array(
				'section'=>'header',
				'id'=>'promotion-header',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/1_trusted_team.png',
				'name' => 'Alante',
				'desc' => 'Alante is one of our best themes. Use this for any business or blog and give a professional image. Descriptions can even be added to the header menu making it easier for your customers to navigate through the site. With a drop shadow on the call to action and page titles this theme is a must have for any business.',
				'demo' => 'http://demo.thinkupthemes.com/?theme=Alante',
				'feat' => 'http://www.thinkupthemes.com/themes/alante/',
				'join' => '',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/1_trusted_team.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/2_page_builder.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/3_shortcodes.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/4_typography.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/5_premium_support.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/6_parallax_pages.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/7_site_layout.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/8_backgrounds.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/9_responsive.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/10_retina_ready.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/11_unlimited_colors.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/12_translation_ready.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/13_rtl_support.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/14_portfolios.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/15_infinite_sidebars.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/16_seo_optimized.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),

			array(
				'section'=>'main',
				'id'=>'theme-alante',
				'type'=>'promotion',
				'image' => get_template_directory_uri()  . '/admin/main/assets/img/promotion/17_demo_content.png',
				'feat' => '//www.thinkupthemes.com/themes/minamaze/',
				),
		)
	);

//	$tabs['item_info'] = array(
//		'icon' => 'info-sign',
//		'icon_class' => 'icon-large',
//    	'title' => __('Theme Information', 'redux-framework'),
//    	'content' => $item_info
//	);

    global $ReduxFramework;
    $ReduxFramework = new ReduxFramework($sections, $args, $tabs);

}
add_action('init', 'redux_setup_framework_options', 0);


/*
 * 
 * Custom function for the callback referenced above
 *
 */
function redux_my_custom_field($field, $value) {
    print_r($field);
    print_r($value);
}

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function redux_validate_callback_function($field, $value, $existing_value) {
    $error = false;
    $value =  'just testing';
    /*
    do your validation
    
    if(something) {
        $value = $value;
    } elseif(somthing else) {
        $error = true;
        $value = $existing_value;
        $field['msg'] = 'your custom error message';
    }
    */
    
    $return['value'] = $value;
    if($error == true) {
        $return['error'] = $field;
    }
    return $return;
}

/*
	This is a test function that will let you see when the compiler hook occurs. 
	It only runs if a field	set with compiler=>true is changed.
*/
function redux_testCompiler() {
	//echo "Compiler hook!";
}
add_action('redux-compiler-redux-sample-file', 'redux_testCompiler');



/**
	Use this function to hide the activation notice telling users about a sample panel.
**/
function redux_removeReduxAdminNotice() {
	delete_option('REDUX_FRAMEWORK_PLUGIN_ACTIVATED_NOTICES');
}
add_action('redux_framework_plugin_admin_notice', 'redux_removeReduxAdminNotice');