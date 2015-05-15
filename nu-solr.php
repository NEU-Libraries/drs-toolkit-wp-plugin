<?php
/**
 * Plugin Name: NU Solr Plugin ADAPT
 * Plugin URI:
 * Description: After you activate the plugin, go to your permalink settings and click "Save" to refresh the tables.
 * Version: 0.1
 * Author: Eli Zoller
 */

// Rewrite rules are not being reset when the plugin is deactivated
// consider changing so as not to use init but instead something else

require_once( plugin_dir_path( __FILE__ ) . 'inc/NUSolrDoc.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/NUSolrVideo.php' );

$VERSION = '0.3.0';

// Set template names here so we don't have to go into the code.
$SOLR_TEMPLATE = array(
    //'custom_template' => 'nusolr-template.php', //this attaches the plugin to the separate theme template
    'default_template' => dirname(__FILE__) . '/templates/view.php',
);

/**
 * Rewrite rules for the plugin.
 */
add_action('init', 'nu_solr_rewrite_rule');
function nu_solr_rewrite_rule() {

    add_rewrite_rule('^search/?$',
        'index.php?post_type=solrdoc&nu_solr_template_type=browse',
        'top');
    add_rewrite_rule('^item/?$', 'index.php?post_type=solrdoc&nu_solr_template_type=item', 'top');

    #add_permastruct('solrdoc', '/content/%solr_doc_id%/', false );

    #add_rewrite_tag('%solr_doc_id%','([^&]+)');
}

/**
 * Register an additional query variable so we can differentiate between
 * the types of custom queries that are generated
 */
add_filter('query_vars', 'nu_solr_add_query_var');
function nu_solr_add_query_var($public_query_vars){
    $public_query_vars[] = 'nu_solr_template_type';
    return $public_query_vars;
}

/**
 * Creates a permalink for a solrdoc post based on its solr_doc_id
 */
// add_filter('post_type_link', 'nu_solr_add_permalink', 10, 2 );
// function nu_solr_add_permalink( $url, $post ) {
//     $post_id = $post->ID;
//
//     if ( $post->post_type == 'solrdoc') {
//         $solr_doc_id = get_post_meta( $post_id, 'solr_doc_id', true);
//         if ( $solr_doc_id && is_numeric( $solr_doc_id ) ) {
//             $url = str_replace('%solr_doc_id%', $solr_doc_id, $url);
//         } else {
//             $url = '';
//         }
//     }
//     return $url;
// }

/**
 * Catch any views from the solrdoc post type and run our function
 * to either get or create the post based on the solr_doc_id
 */
add_action('pre_get_posts', 'nu_solr_process_solrdoc');
function nu_solr_process_solrdoc( $wp_query ) {
    $solr_doc_id = get_query_var( 'solr_doc_id' );
    if ( !is_admin() && $wp_query->is_main_query() && $solr_doc_id ) {
        //$wp_query->set('nu_solr_template_type', 'doc');
        echo "we are abotu to call get or create";
        get_or_create_solr_doc( $wp_query, $solr_doc_id );
    }
}

/**
 * This doesn't work yet but I think it should.  Once rewrite rules are created,
 * that seems to be it.  Need to go
 *
 * // https://codex.wordpress.org/Function_Reference/flush_rewrite_rules#Examples
 */
register_activation_hook( __FILE__, 'nu_solr_activate' );
function nu_solr_activate() {
    flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'nu_solr_deactivate' );
function nu_solr_deactivate() {
    flush_rewrite_rules();
}

/**
 * This is the hook that will filter our template calls; it searches for the
 * nu_solr_template_type variable (which we set above) and then makes a
 * decision accordingly.
 */
add_filter('template_include', 'nu_solr_content_template', 1, 1);
function nu_solr_content_template( $template ) {
    global $wp_query;
    global $SOLR_TEMPLATE;

    if ( isset($wp_query->query_vars['nu_solr_template_type']) ) {

        $template_type = $wp_query->query_vars['nu_solr_template_type'];

        if ($template_type == 'browse') {
            add_action('wp_enqueue_scripts', 'nu_solr_browse_script');
            echo "template is browse";
            #return locate_template( array( 'view.php' ) );
            return $SOLR_TEMPLATE['default_template'];

        }

    } elseif ( get_query_var('post_type') == 'solrdoc' && is_single() ) {
        add_action('wp_enqueue_scripts', 'nu_solr_doc_script');
        $new_template = locate_template( array( $SOLR_TEMPLATE['custom_template'] ) );

        if ( '' != $new_template ) {
            return $new_template;
        } else {
            return $SOLR_TEMPLATE['default_template'];
        }

    } else {
        return $template;
    }
} // end nu_solr_content_template

/**
 * Load scripts for the browse/search page
 *
 */
function nu_solr_browse_script() {
    global $VERSION;

    //wp_register_script('ajax_solr_browse', plugins_url('/assets/js/ajax-solr-scripts.min.js', __FILE__), array(), $VERSION, true );

    //wp_enqueue_script('ajax_solr_browse');

    //wp_localize_script('ajax_solr_browse', 'ajax_solr_vars', array('proxy_url' => plugins_url('/inc/NUSolrProxy.php', __FILE__) ) );
}

/**
 * Load scripts for the doc/page views
 */
function nu_solr_doc_script() {
    global $VERSION;

    //wp_register_script('ajax_solr_jwplayer',plugins_url('/assets/js/jwplayer/jwplayer.js', __FILE__), array(), $VERSION, false );

    //wp_enqueue_script('ajax_solr_jwplayer');

}

/**
 * Register our custom post type for the Solr Documents
 */
// add_action( 'init', 'nu_solr_post_type' );
// function nu_solr_post_type() {
//     $labels = array(
//         'name'               => _x( 'Docs', 'post type general name' ),
//         'singular_name'      => _x( 'Doc', 'post type singular name' ),
//         'add_new'            => _x( 'Add New', 'book' ),
//         'add_new_item'       => __( 'Add New Doc' ),
//         'edit_item'          => __( 'Edit Solr Document' ),
//         'new_item'           => __( 'New Solr Document' ),
//         'all_items'          => __( 'All Solr Documents' ),
//         'view_item'          => __( 'View Solr Document' ),
//         'search_items'       => __( 'Search Docs' ),
//         'not_found'          => __( 'No products found' ),
//         'not_found_in_trash' => __( 'No products found in the Trash' ),
//         'parent_item_colon'  => '',
//         'menu_name'          => 'NUSolr'
//     );
//     $args = array(
//         'labels'        => $labels,
//         'description'   => 'NU Solr Document library',
//         'public'        => true,
//         'menu_position' => 5,
//         'supports'      => array( 'title', 'editor', 'comments', 'custom-fields' ),
//         'has_archive'   => false,
//         //'capabilities'  => array( 'create_posts' => false,),
//         'rewrite'       => false,
//         'publicly_queryable' => true,
//         'query_var' => true,
//     );
//     register_post_type( 'solrdoc', $args );
// }

/**
 * Adds a box to the main column on the solrdoc edit screens.
 */
// add_action( 'add_meta_boxes', 'nu_solr_add_custom_box' );
// function nu_solr_add_custom_box() {
//     add_meta_box(
//         'nu_solr_sectionid',
//         __( 'Featured Video', 'nu_solr_textdomain' ),
//         'nu_solr_inner_custom_box',
//         'solrdoc',
//         'side'
//     );
// }

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
// function nu_solr_inner_custom_box( $post ) {
//
//   // Add an nonce field so we can check for it later.
//   wp_nonce_field( 'nu_solr_inner_custom_box', 'nu_solr_inner_custom_box_nonce' );
//
//   $value = get_post_meta( $post->ID, 'solr_doc_featured', true );
//   $checked = ( $value == 1 ? 'checked' : '');
//
//   echo '<label for="nu_solr_new_field">';
//   echo '<input type="checkbox" id="nu_solr_new_field" name="nu_solr_new_field" value="1" '.$checked.'>';
//   _e( "Select to feature this video", 'nu_solr_textdomain' );
//   echo '</label> ';
//   echo '<p><small>Update the post after making changes</small></p>';
// }

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
// add_action( 'save_post', 'nu_solr_save_postdata' );
// function nu_solr_save_postdata( $post_id ) {
//
//     // Check if our nonce is set.
//     if ( ! isset( $_POST['nu_solr_inner_custom_box_nonce'] ) )
//         return $post_id;
//
//     $nonce = $_POST['nu_solr_inner_custom_box_nonce'];
//
//       // Verify that the nonce is valid.
//     if ( ! wp_verify_nonce( $nonce, 'nu_solr_inner_custom_box' ) )
//       return $post_id;
//
//       // If this is an autosave, our form has not been submitted, so we don't want to do anything.
//     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
//       return $post_id;
//
//       // Check the user's permissions.
//     if ( ! current_user_can( 'edit_post', $post_id ) )
//         return $post_id;
//
//     /* OK, its safe for us to save the data now. */
//     $is_featured = (isset( $_POST['nu_solr_new_field'] ) ? true : false );
//     update_post_meta( $post_id, 'solr_doc_featured', $is_featured );
// }
