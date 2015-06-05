<?php
/**
 * Theme setup functions.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	ADD CUSTOM HOOKS
---------------------------------------------------------------------------------- */

/* Used at top if header.php */
function thinkup_hook_header() { 
	do_action('thinkup_hook_header');
}

/* Used at top if header.php within the body tag */
function thinkup_bodystyle() { 
	do_action('thinkup_bodystyle');
}

// Activates premium features in page builder
function thinkup_check_premium($classes){

	// Add class to admin area to make page builder parallax work (if template-parallax.php is present)
	if ( '' != locate_template( 'template-parallax.php' ) ) {	
		$classes = 'thinkup_parallax_enabled';
	}
	return $classes;
}
add_action( 'admin_body_class', 'thinkup_check_premium');


/* ----------------------------------------------------------------------------------
	CORRECT Z-INDEX OF OEMBED OBJECTS
---------------------------------------------------------------------------------- */
function thinkup_fix_oembed( $embed ) {
	if ( strpos( $embed, '<param' ) !== false ) {
   		$embed = str_replace( '<embed', '<embed wmode="transparent" ', $embed );
   		$embed = preg_replace( '/param>/', 'param><param name="wmode" value="transparent" />', $embed, 1);
	}
	return $embed;
}
add_filter( 'embed_oembed_html', 'thinkup_fix_oembed', 1 );


/* ----------------------------------------------------------------------------------
	CHANGE TITLE AND DESCRIPTION OF PORTFOLIO EXTRACT BOX - PREMIUM FEATURE
---------------------------------------------------------------------------------- */


/* ----------------------------------------------------------------------------------
	ADD BREADCRUMBS FUNCTIONALITY
---------------------------------------------------------------------------------- */

function thinkup_input_breadcrumb() {
global $thinkup_general_breadcrumbdelimeter;

	if ( empty( $thinkup_general_breadcrumbdelimeter ) ) {
		$delimiter = '<span class="delimiter">/</span>';
	}
	else if ( ! empty( $thinkup_general_breadcrumbdelimeter ) ) {
		$delimiter = '<span class="delimiter"> ' . esc_html( $thinkup_general_breadcrumbdelimeter ) . ' </span>';
	}

	$delimiter_inner   =   '<span class="delimiter_core"> &bull; </span>';
	$main              =   'Home';
	$maxLength         =   30;

	/* Archive variables */
	$arc_year       =   get_the_time('Y');
	$arc_month      =   get_the_time('F');
	$arc_day        =   get_the_time('d');
	$arc_day_full   =   get_the_time('l');  

	/* URL variables */
	$url_year    =   get_year_link($arc_year);
	$url_month   =   get_month_link($arc_year,$arc_month);

	/* Display breadcumbs if NOT the home page */
	if ( !is_home() ) {
		echo '<div id="breadcrumbs"><div id="breadcrumbs-core">';
		global $post, $cat;
		$homeLink = home_url( '/' );
		echo '<a href="' . $homeLink . '">' . $main . '</a>' . $delimiter;    

		/* Display breadcrumbs for single post */
		if ( is_single() ) {
			$category = get_the_category();
			$num_cat = count($category);
			if ($num_cat <=1) {
				echo ' ' . get_the_title();
			} else {
				echo the_category( $delimiter_inner, multiple);
				if (strlen(get_the_title()) >= $maxLength) {
					echo ' ' . $delimiter . trim(substr(get_the_title(), 0, $maxLength)) . ' ...';
				} else {
					echo ' ' . $delimiter . get_the_title();
				}
			}
		} elseif (is_category()) {
			_e( 'Archive Category: ', 'lan-thinkupthemes' ) . get_category_parents($cat, true,' ' . $delimiter . ' ') ;
		} elseif ( is_tag() ) {
			_e( 'Posts Tagged: ', 'lan-thinkupthemes' ) . single_tag_title("", false) . '"';
		} elseif ( is_day()) {
			echo '<a href="' . $url_year . '">' . $arc_year . '</a> ' . $delimiter . ' ';
			echo '<a href="' . $url_month . '">' . $arc_month . '</a> ' . $delimiter . $arc_day . ' (' . $arc_day_full . ')';
		} elseif ( is_month() ) {
			echo '<a href="' . $url_year . '">' . $arc_year . '</a> ' . $delimiter . $arc_month;
		} elseif ( is_year() ) {
			echo $arc_year;
		} elseif ( is_search() ) {
			_e( 'Search Results for: ', 'lan-thinkupthemes' ) . get_search_query() . '"';
		} elseif ( is_page() && !$post->post_parent ) {
			echo get_the_title();
		} elseif ( is_page() && $post->post_parent ) {
			$post_array = get_post_ancestors( $post );
			krsort( $post_array ); 
			foreach( $post_array as $key=>$postid ){
				$post_ids = get_post( $postid );
				$title = $post_ids->post_title;
				echo '<a href="' . get_permalink($post_ids) . '">' . $title . '</a>' . $delimiter;
			}
			the_title();
		} elseif ( is_author() ) {
			global $author;
			$user_info = get_userdata($author);
			_e( 'Archived Article(s) by Author: ', 'lan-thinkupthemes' ) . $user_info->display_name ;
		} elseif ( is_404() ) {
			_e( 'Error 404 - Not Found.', 'lan-thinkupthemes' );
		}
       echo '</div></div>';
    }
}

/* ----------------------------------------------------------------------------------
	ADD PAGINATION FUNCTIONALITY
---------------------------------------------------------------------------------- */
function thinkup_input_pagination( $pages = "", $range = 2 ) {
global $paged;
global $wp_query;
		
	$showitems = ($range * 2)+1;  

	if(empty($paged)) $paged = 1;

	if($pages == "") {
		$pages = $wp_query->max_num_pages;
		if(!$pages) {
			$pages = 1;
		}
	}

	if(1 != $pages) {
		echo '<ul class="pag">';
		
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) 
				echo '<li class="pag-first"><a href="' . get_pagenum_link(1). '">&laquo;</a></li>';
			if($paged > 1 && $showitems < $pages) 
				echo '<li class="pag-previous"><a href="' . get_pagenum_link($paged - 1). '">&lsaquo; ' . __( 'Prev', 'lan-thinkupthemes' ) . '</a></li>';

			for ($i=1; $i <= $pages; $i++) {
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
					echo ($paged == $i)? '<li class="current"><span>' . $i . '</span></li>':'<li><a href="' . get_pagenum_link($i) . '">'. $i . '</a></li>';
				}
			}

			if ($paged < $pages && $showitems < $pages) 
				echo '<li class="pag-next"><a href="' . get_pagenum_link($paged + 1) . '">' . __( 'Next', 'lan-thinkupthemes' ) . ' &rsaquo;</i></a></li>';
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
				echo '<li class="pag-last" ><a href="' . get_pagenum_link($pages) . '">&raquo;</a></li>';

		echo '</ul>';
     }
}


/* ----------------------------------------------------------------------------------
	REMOVE UNNECESSARY CODE FROM WP_HEAD
---------------------------------------------------------------------------------- */
/*
remove_action( 'wp_head', 'rsd_link');
remove_action( 'wp_head', 'wlwmanifest_link');
remove_action( 'wp_head', 'start_post_rel_link');
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_generator');
*/

/* ----------------------------------------------------------------------------------
	REMOVE NON VALID REL CATEGORY TAGS
---------------------------------------------------------------------------------- */

function thinkup_add_nofollow_cat( $text ) { 
	$text = str_replace( 'rel="category"', "", $text );
	return $text; 
};
add_filter( 'the_category', 'thinkup_add_nofollow_cat' );  


/* ----------------------------------------------------------------------------------
	ADD CUSTOM FEATURED IMAGE SIZES
---------------------------------------------------------------------------------- */

if ( ! function_exists( 'thinkup_input_addimagesizes' ) ) {
	function thinkup_input_addimagesizes() {

		/* 1 Column Layout */
		add_image_size( 'column1-1/1', 960, 960, true );
		add_image_size( 'column1-1/2', 960, 480, true );
		add_image_size( 'column1-1/3', 960, 320, true );
		add_image_size( 'column1-2/3', 960, 640, true );
		add_image_size( 'column1-1/4', 960, 240, true );
		add_image_size( 'column1-3/4', 960, 720, true );
		add_image_size( 'column1-1/5', 960, 192, true );
		add_image_size( 'column1-2/5', 960, 384, true );
		add_image_size( 'column1-3/5', 960, 576, true );
		add_image_size( 'column1-4/5', 960, 768, true );

		/* 2 Column Layout */
		add_image_size( 'column2-1/1', 480, 480, true );
		add_image_size( 'column2-1/2', 480, 240, true );
		add_image_size( 'column2-1/3', 480, 160, true );
		add_image_size( 'column2-2/3', 480, 320, true );
		add_image_size( 'column2-1/4', 480, 120, true );
		add_image_size( 'column2-3/4', 480, 360, true );
		add_image_size( 'column2-1/5', 480, 96, true );
		add_image_size( 'column2-2/5', 480, 192, true );
		add_image_size( 'column2-3/5', 480, 288, true );
		add_image_size( 'column2-4/5', 480, 384, true );

		/* 3 Column Layout */
		add_image_size( 'column3-1/1', 320, 320, true );
		add_image_size( 'column3-1/2', 320, 160, true );
		add_image_size( 'column3-1/3', 320, 107, true );
		add_image_size( 'column3-2/3', 320, 213, true );
		add_image_size( 'column3-1/4', 320, 80, true );
		add_image_size( 'column3-3/4', 320, 240, true );
		add_image_size( 'column3-1/5', 320, 64, true );
		add_image_size( 'column3-2/5', 320, 128, true );
		add_image_size( 'column3-3/5', 320, 192, true );
		add_image_size( 'column3-4/5', 320, 256, true );

		/* 4 Column Layout */
		add_image_size( 'column4-1/1', 240, 240, true );
		add_image_size( 'column4-1/2', 240, 120, true );
		add_image_size( 'column4-1/3', 240, 80, true );
		add_image_size( 'column4-2/3', 240, 160, true );
		add_image_size( 'column4-1/4', 240, 60, true );
		add_image_size( 'column4-3/4', 240, 180, true );
		add_image_size( 'column4-1/5', 240, 48, true );
		add_image_size( 'column4-2/5', 240, 96, true );
		add_image_size( 'column4-3/5', 240, 144, true );
		add_image_size( 'column4-4/5', 240, 192, true );
	}
	add_action( 'init', 'thinkup_input_addimagesizes' );
}

if ( ! function_exists( 'thinkup_input_showimagesizes' ) ) {
	function thinkup_input_showimagesizes($sizes) {

		/* 1 Column Layout */
		$sizes['column1-1/1'] = 'Full - 1:1';
		$sizes['column1-1/2'] = 'Full - 1:2';
		$sizes['column1-1/3'] = 'Full - 1:3';
		$sizes['column1-2/3'] = 'Full - 2:3';
		$sizes['column1-1/4'] = 'Full - 1:4';
		$sizes['column1-3/4'] = 'Full - 3:4';
		$sizes['column1-1/5'] = 'Full - 1:5';
		$sizes['column1-2/5'] = 'Full - 2:5';
		$sizes['column1-3/5'] = 'Full - 3:5';
		$sizes['column1-4/5'] = 'Full - 4:5';

		/* 2 Column Layout */
		$sizes['column2-1/1'] = 'Half - 1:1';
		$sizes['column2-1/2'] = 'Half - 1:2';
		$sizes['column2-1/3'] = 'Half - 1:3';
		$sizes['column2-2/3'] = 'Half - 2:3';
		$sizes['column2-1/4'] = 'Half - 1:4';
		$sizes['column2-3/4'] = 'Half - 3:4';
		$sizes['column2-1/5'] = 'Half - 1:5';
		$sizes['column2-2/5'] = 'Half - 2:5';
		$sizes['column2-3/5'] = 'Half - 3:5';
		$sizes['column2-4/5'] = 'Half - 4:5';

		/* 3 Column Layout */
		$sizes['column3-1/1'] = 'Third - 1:1';
		$sizes['column3-1/2'] = 'Third - 1:2';
		$sizes['column3-1/3'] = 'Third - 1:3';
		$sizes['column3-2/3'] = 'Third - 2:3';
		$sizes['column3-1/4'] = 'Third - 1:4';
		$sizes['column3-3/4'] = 'Third - 3:4';
		$sizes['column3-1/5'] = 'Third - 1:5';
		$sizes['column3-2/5'] = 'Third - 2:5';
		$sizes['column3-3/5'] = 'Third - 3:5';
		$sizes['column3-4/5'] = 'Third - 4:5';

		/* 4 Column Layout */
		$sizes['column4-1/1'] = 'Quarter - 1:1';
		$sizes['column4-1/2'] = 'Quarter - 1:2';
		$sizes['column4-1/3'] = 'Quarter - 1:3';
		$sizes['column4-2/3'] = 'Quarter - 2:3';
		$sizes['column4-1/4'] = 'Quarter - 1:4';
		$sizes['column4-3/4'] = 'Quarter - 3:4';
		$sizes['column4-1/5'] = 'Quarter - 1:5';
		$sizes['column4-2/5'] = 'Quarter - 2:5';
		$sizes['column4-3/5'] = 'Quarter - 3:5';
		$sizes['column4-4/5'] = 'Quarter - 4:5';

		return $sizes;
	}
	add_filter('image_size_names_choose', 'thinkup_input_showimagesizes');
}

/* ----------------------------------------------------------------------------------
	ADD HOME: HOME TO CUSTOM MENU PAGE LIST
---------------------------------------------------------------------------------- */

function thinkup_menu_homelink( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'thinkup_menu_homelink' );


//----------------------------------------------------------------------------------
//	ADD FUNCTION TO GET CURRENT PAGE URL
//----------------------------------------------------------------------------------

function thinkup_check_ishome() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) ) {
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	$pageURL = rtrim($pageURL, '/') . '/';

	$pageURL = str_replace( "www.", "", $pageURL );
	$siteURL = str_replace( "www.", "", site_url( '/' ) );

	if ( $pageURL == $siteURL ) {
		return true;
	} else {
		return false;
	}
}


//----------------------------------------------------------------------------------
//	ADD CUSTOM 'thinkup_get_comments_popup_link' FUNCTION - Credit to http://www.thescubageek.com/code/wordpress-code/add-get_comments_popup_link-to-wordpress/
//----------------------------------------------------------------------------------

// Modifies WordPress's built-in comments_popup_link() function to return a string instead of echo comment results
function thinkup_get_comments_popup_link( $zero = false, $one = false, $more = false, $css_class = '', $none = false ) {
    global $wpcommentspopupfile, $wpcommentsjavascript;
 
    $id = get_the_ID();
 
    if ( false === $zero ) $zero = __( 'No Comments','lan-thinkupthemes' );
    if ( false === $one ) $one = __( '1 Comment','lan-thinkupthemes' );
    if ( false === $more ) $more = __( '% Comments','lan-thinkupthemes' );
    if ( false === $none ) $none = __( 'Comments Off','lan-thinkupthemes' );
 
    $number = get_comments_number( $id );
 
    $str = '';
 
    if ( 0 == $number && !comments_open() && !pings_open() ) {
        $str = '<span' . ((!empty($css_class)) ? ' class="' . esc_attr( $css_class ) . '"' : '') . '>' . $none . '</span>';
        return $str;
    }
 
    if ( post_password_required() ) {
        $str = __('Enter your password to view comments.','lan-thinkupthemes');
        return $str;
    }
 
    $str = '<a href="';
    if ( $wpcommentsjavascript ) {
        if ( empty( $wpcommentspopupfile ) )
            $home = home_url();
        else
            $home = get_option('siteurl');
        $str .= $home . '/' . $wpcommentspopupfile . '?comments_popup=' . $id;
        $str .= '" onclick="wpopen(this.href); return false"';
    } else { // if comments_popup_script() is not in the template, display simple comment link
        if ( 0 == $number )
            $str .= get_permalink() . '#respond';
        else
            $str .= get_comments_link();
        $str .= '"';
    }
 
    if ( !empty( $css_class ) ) {
        $str .= ' class="'.$css_class.'" ';
    }
    $title = the_title_attribute( array('echo' => 0 ) );
 
    $str .= apply_filters( 'comments_popup_link_attributes', '' );
 
    $str .= ' title="' . esc_attr( sprintf( __('Comment on %s','lan-thinkupthemes'), $title ) ) . '">';
    $str .= thinkup_get_comments_number_str( $zero, $one, $more );
    $str .= '</a>';
     
    return $str;
}
 
// Modifies WordPress's built-in comments_number() function to return string instead of echo
function thinkup_get_comments_number_str( $zero = false, $one = false, $more = false, $deprecated = '' ) {
    if ( !empty( $deprecated ) )
        _deprecated_argument( __FUNCTION__, '1.3' );
 
    $number = get_comments_number();
 
    if ( $number > 1 )
        $output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __( '% Comments', 'lan-thinkupthemes' ) : $more);
    elseif ( $number == 0 )
        $output = ( false === $zero ) ? __( 'No Comments', 'lan-thinkupthemes' ) : $zero;
    else // must be one
        $output = ( false === $one ) ? __( '1 Comment', 'lan-thinkupthemes' ) : $one;
 
    return apply_filters('comments_number', $output, $number);
}


//----------------------------------------------------------------------------------
//	CHANGE FALLBACK WP_PAGE_MENU CLASSES TO MATCH WP_NAV_MENU CLASSES
//----------------------------------------------------------------------------------

function thinkup_add_menuclass( $ulclass ) {

	$ulclass = preg_replace( '/<ul>/', '<ul class="menu">', $ulclass, 1 );
	$ulclass = str_replace( 'children', 'sub-menu', $ulclass );

	return preg_replace('/<div (.*)>(.*)<\/div>/iU', '$2', $ulclass );
}
add_filter( 'wp_page_menu', 'thinkup_add_menuclass' );


//----------------------------------------------------------------------------------
//	DETERMINE IF THE PAGE IS A BLOG - USEFUL FOR HOMEPAGE BLOG.
//----------------------------------------------------------------------------------

// Credit to: http://www.poseidonwebstudios.com/web-development/wordpress-is_blog-function/
function thinkup_is_blog() {
 
    global $post;
 
    //Post type must be 'post'.
    $post_type = get_post_type($post);
 
    //Check all blog-related conditional tags, as well as the current post type,
    //to determine if we're viewing a blog page.
    return (
        ( is_home() || is_archive() || is_single() )
        && ($post_type == 'post')
    ) ? true : false ;
 
}


//----------------------------------------------------------------------------------
//	ADD FEATURED IMAGE THUMBNAIL.
//----------------------------------------------------------------------------------

// Add featured images to posts
add_filter('manage_pages_columns', 'thinkup_posts_columns', 5);
add_filter('manage_posts_columns', 'thinkup_posts_columns', 5);
add_action('manage_posts_custom_column', 'thinkup_posts_custom_columns', 5, 2);
add_action('manage_pages_custom_column', 'thinkup_posts_custom_columns', 5, 2);
function thinkup_posts_columns($defaults){
    $defaults['riv_post_thumbs'] = __( 'Thumbs', 'lan-thinkupthemes' );
    return $defaults;
}
function thinkup_posts_custom_columns($column_name, $id){
        if($column_name === 'riv_post_thumbs'){
        echo the_post_thumbnail( 'thumbnail' );
    }
}


//----------------------------------------------------------------------------------
//	GET EXCERPT BY ID.
//----------------------------------------------------------------------------------

function thinkup_input_excerptbyid($post_id){

	$the_post = get_post($post_id); //Gets post ID
	$the_excerpt = $the_post->post_excerpt; //Gets post_content to be used as a basis for the excerpt
	$excerpt_length = 35; //Sets excerpt length by word count
	$the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
	$words = explode(' ', $the_excerpt, $excerpt_length + 1);

	if(count($words) > $excerpt_length) {
		array_pop($words);
		array_push($words, '…');
		$the_excerpt = implode(' ', $words);
	}

	$the_excerpt = '<p>' . $the_excerpt . '</p>';
	return $the_excerpt;
}


//----------------------------------------------------------------------------------
//	CUSTOM READ MORE FOR the_content() AND the_excerpt().
//----------------------------------------------------------------------------------

function thinkup_modify_read_more_link() {
	return '<p><a href="'. get_permalink( get_the_ID() ) . '" class="more-link themebutton">' . __( 'Read More', 'lan-thinkupthemes') . '</a></p>';
}
add_filter( 'excerpt_more', 'thinkup_modify_read_more_link' );
add_filter( 'the_content_more_link', 'thinkup_modify_read_more_link' );


//----------------------------------------------------------------------------------
//	ADD GOOGLE FONT - OPEN SANS.
//----------------------------------------------------------------------------------

function thinkup_googlefonts_url() {
    $fonts_url = '';

    // Translators: Translate thsi to 'off' if there are characters in your language that are not supported by Open Sans
    $open_sans = _x( 'on', 'Open Sans font: on or off', 'lan-thinkupthemes' );
 
    if ( 'off' !== $open_sans ) {
        $font_families = array();
  
        if ( 'off' !== $open_sans ) {
            $font_families[] = 'Open Sans:300,400,600,700';
        }
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
    }
 
    return $fonts_url;
}

function thinkup_googlefonts_scripts() {
   wp_enqueue_style( 'thinkup-google-fonts', thinkup_googlefonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'thinkup_googlefonts_scripts' );


?>