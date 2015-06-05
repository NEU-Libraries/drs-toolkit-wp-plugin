<?php
/**
 * Setup theme functions for Minamaze.
 *
 * @package ThinkUpThemes
 */

// Setup content width 
if ( ! isset( $content_width ) )
	$content_width = 960;

// ----------------------------------------------------------------------------------
//	Add Theme Options Panel & Assign Variable Values
// ----------------------------------------------------------------------------------

	// Add Redux Framework - Credits attributable to http://reduxframework.com/
	require_once (get_template_directory() . '/admin/main/framework.php');

	// Add Theme Options Features.
	require_once( get_template_directory() . '/admin/main/options/00.theme-setup.php' ); 
	require_once( get_template_directory() . '/admin/main/options/00.variables.php' ); 
	require_once( get_template_directory() . '/admin/main/options/01.general-settings.php' ); 
	require_once( get_template_directory() . '/admin/main/options/02.homepage.php' ); 
	require_once( get_template_directory() . '/admin/main/options/03.header.php' ); 
	require_once( get_template_directory() . '/admin/main/options/04.footer.php' );
	require_once( get_template_directory() . '/admin/main/options/05.blog.php' ); 
	require_once( get_template_directory() . '/admin/main/options/08.special-pages.php' ); 

	// Add widget features.
//	include_once( get_template_directory() . '/lib/widgets/categories.php' ); 
//	include_once( get_template_directory() . '/lib/widgets/popularposts.php' ); 
//	include_once( get_template_directory() . '/lib/widgets/recentposts.php' ); 
//	include_once( get_template_directory() . '/lib/widgets/searchfield.php' ); 
//	include_once( get_template_directory() . '/lib/widgets/tagscloud.php' );

// ----------------------------------------------------------------------------------
//	Assign Theme Specific Functions
// ----------------------------------------------------------------------------------

// Setup theme features, register menus and scripts.
if ( ! function_exists( 'thinkup_themesetup' ) ) {

	function thinkup_themesetup() {

		// Load required files
		require_once ( get_template_directory() . '/lib/functions/extras.php' );
		require_once ( get_template_directory() . '/lib/functions/template-tags.php' );

		// Make theme translation ready.
		load_theme_textdomain( 'lan-thinkupthemes', get_template_directory() . '/languages' );

		// Add default theme functions.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-background' );
		add_theme_support( 'title-tag' );

		// Add support for custom header
		$args = apply_filters( 'custom-header', array( 'height' => 200, 'width'  => 1600 ) );
		add_theme_support( 'custom-header', $args );

		// Add WooCommerce functions.
		add_theme_support( 'woocommerce' );

		// Register theme menu's.
		register_nav_menus( array( 'pre_header_menu' => 'Pre Header Menu', ) );
		register_nav_menus( array( 'header_menu' => 'Primary Header Menu', ) );
		register_nav_menus( array( 'sub_footer_menu' => 'Footer Menu', ) );
	}
}
add_action( 'after_setup_theme', 'thinkup_themesetup' );


// ----------------------------------------------------------------------------------
//	Register Front-End Styles And Scripts
// ----------------------------------------------------------------------------------

function thinkup_frontscripts() {

	// Add jQuery library.
	wp_enqueue_script('jquery');

	// Register theme stylesheets.
	wp_register_style( 'style', get_stylesheet_uri(), '', '1.1.8' );
	wp_register_style( 'shortcodes', get_template_directory_uri() . '/styles/style-shortcodes.css', '', '1.1' );
	wp_register_style( 'responsive', get_template_directory_uri() . '/styles/style-responsive.css', '', '1.1' );
	wp_register_style( 'sidebarleft', get_template_directory_uri() . '/styles/layouts/thinkup-left-sidebar.css', '', '1.1' );
	wp_register_style( 'sidebarright', get_template_directory_uri() . '/styles/layouts/thinkup-right-sidebar.css', '', '1.1' );
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/lib/extentions/bootstrap/css/bootstrap.min.css', '', '2.3.2' );
	wp_register_style( 'prettyPhoto', get_template_directory_uri().'/lib/extentions/prettyPhoto/css/prettyPhoto.css', '', '3.1.5' ); 

	// Register Font Packages.
	wp_register_style( 'font-awesome-min', get_template_directory_uri() . '/lib/extentions/font-awesome/css/font-awesome.min.css', '', '3.2.1' );
	wp_register_style( 'font-awesome-cdn', get_template_directory_uri() . '/lib/extentions/font-awesome-4.2.0/css/font-awesome.min.css', '', '4.2.0' );
	wp_register_style( 'dashicons-css', get_template_directory_uri() . '/lib/extentions/dashicons/css/dashicons.css', '', '2.0' );
	
	// Register theme scripts.
	wp_register_script( 'frontend', get_template_directory_uri() . '/lib/scripts/main-frontend.js', array( 'jquery' ), '1.1', true );
	wp_register_script( 'modernizr', get_template_directory_uri() . '/lib/scripts/modernizr.js', array( 'jquery' ), '', true );
	wp_register_script( 'retina', get_template_directory_uri() . '/lib/scripts/retina.js', array( 'jquery' ), '', true );
	wp_register_script( 'bootstrap', get_template_directory_uri() . '/lib/extentions/bootstrap/js/bootstrap.js', array( 'jquery' ), '2.3.2', true );
	wp_register_script( 'prettyPhoto', ( get_template_directory_uri()."/lib/extentions/prettyPhoto/jquery.prettyPhoto.js" ), array( 'jquery' ), '3.1.5', true );

		// Add Font Packages
		wp_enqueue_style( 'font-awesome-min' );
		wp_enqueue_style( 'font-awesome-cdn' );
		wp_enqueue_style( 'dashicons-css' );

		// Add theme stylesheets
		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'prettyPhoto' );
		wp_enqueue_style( 'style' );
		wp_enqueue_style( 'shortcodes' );

		// Add theme scripts
		wp_enqueue_script( 'prettyPhoto' );
		wp_enqueue_script( 'frontend' );
		wp_enqueue_script( 'bootstrap' );
		wp_enqueue_script( 'modernizr' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Add ThinkUpSlider scripts
		if ( is_front_page() or thinkup_check_ishome() ) {
			wp_enqueue_script( 'thinkupslider', get_template_directory_uri() . '/lib/scripts/plugins/ResponsiveSlides/responsiveslides.min.js', array( 'jquery' ), '1.54' );
		wp_enqueue_script( 'thinkupslider-call', get_template_directory_uri() . '/lib/scripts/plugins/ResponsiveSlides/responsiveslides-call.js', array( 'jquery' ) );
		}
}
add_action( 'wp_enqueue_scripts', 'thinkup_frontscripts', 10 );


// ----------------------------------------------------------------------------------
//	Register Back-End Styles And Scripts
// ----------------------------------------------------------------------------------

function thinkup_adminscripts() {

	// Register theme stylesheets.
	wp_register_style( 'backend', get_template_directory_uri() . '/styles/backend/style-backend.css', '', 1.3 );

	// Register theme scripts.
	wp_register_script( 'backend', get_template_directory_uri() . '/lib/scripts/main-backend.js', array( 'jquery' ), '1.1' );

		// Add theme stylesheets
		wp_enqueue_style( 'backend' );

		// Add theme scripts
		wp_enqueue_script( 'backend' );
}
add_action( 'admin_enqueue_scripts', 'thinkup_adminscripts' );


//----------------------------------------------------------------------------------
//	Register Shortcodes Styles And Scripts
//----------------------------------------------------------------------------------

function thinkup_shortcodescripts() {

	// Register shortcode scripts
	wp_register_script( 'thinkupslider', get_template_directory_uri() . '/lib/scripts/plugins/ResponsiveSlides/responsiveslides.min.js', array( 'jquery' ), '1.54', 'true' );
	wp_register_script( 'thinkupslider-call', get_template_directory_uri() . '/lib/scripts/plugins/ResponsiveSlides/responsiveslides-call.js', array( 'jquery' ), '', 'true' );

		// Add shortcode scripts
		wp_enqueue_script( 'thinkupslider' );
		wp_enqueue_script( 'thinkupslider-call' );
}
add_action( 'wp_enqueue_scripts', 'thinkup_shortcodescripts', 10 );


// ----------------------------------------------------------------------------------
//	Register Theme Widgets
// ----------------------------------------------------------------------------------

function thinkup_widgets_init() {
	register_sidebar( array(
		'name' => 'Sidebar',
		'id' => 'sidebar-1',
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

    register_sidebar( array(
        'name' => 'Footer Widget Area 1',
        'id' => 'footer-w1',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="footer-widget-title"><span>',
        'after_title' => '</span></h3>',
    ) );
 
    register_sidebar( array(
        'name' => 'Footer Widget Area 2',
        'id' => 'footer-w2',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="footer-widget-title"><span>',
        'after_title' => '</span></h3>',
    ) );

    register_sidebar( array(
        'name' => 'Footer Widget Area 3',
        'id' => 'footer-w3',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="footer-widget-title"><span>',
        'after_title' => '</span></h3>',
    ) );

    register_sidebar( array(
        'name' => 'Footer Widget Area 4',
        'id' => 'footer-w4',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="footer-widget-title"><span>',
        'after_title' => '</span></h3>',
    ) );

    register_sidebar( array(
        'name' => 'Footer Widget Area 5',
        'id' => 'footer-w5',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="footer-widget-title"><span>',
        'after_title' => '</span></h3>',
    ) );

    register_sidebar( array(
        'name' => 'Footer Widget Area 6',
        'id' => 'footer-w6',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="footer-widget-title"><span>',
        'after_title' => '</span></h3>',
    ) );
 }
add_action( 'widgets_init', 'thinkup_widgets_init' );