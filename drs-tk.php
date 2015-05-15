<?php
/**
 * Plugin Name: DRS Toolkit Plugin
 * Plugin URI:
 * Version: 0.1
 * Author: Eli Zoller
 */

// Rewrite rules are not being reset when the plugin is deactivated
// consider changing so as not to use init but instead something else

require_once( plugin_dir_path( __FILE__ ) . 'inc/NUSolrDoc.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/item.php' );

$VERSION = '0.1.0';

// Set template names here so we don't have to go into the code.
$SOLR_TEMPLATE = array(
    //'custom_template' => 'nusolr-template.php', //this attaches the plugin to the separate theme template
    'browse_template' => dirname(__FILE__) . '/templates/browse.php',
    'item_template' => dirname(__FILE__) . '/templates/item.php',
);

 function drstk_install() {
     // Clear the permalinks after the post type has been registered
     drstk_rewrite_rule();
     flush_rewrite_rules();
 }

 function drstk_deactivation() {
     // Clear the permalinks to remove our post type's rules
     flush_rewrite_rules();
 }
 register_activation_hook( __FILE__, 'drstk_install' );
 register_deactivation_hook( __FILE__, 'drstk_deactivation' );

 /**
  * Rewrite rules for the plugin.
  */
 add_action('init', 'drstk_rewrite_rule');
 function drstk_rewrite_rule() {

     add_rewrite_rule('^browse/?$',
         'index.php?post_type=drs&drstk_template_type=browse',
         'top');
     add_rewrite_rule('^item/?$', 'index.php?post_type=drs&drstk_template_type=item', 'top');

 }

 /**
  * Register an additional query variable so we can differentiate between
  * the types of custom queries that are generated
  */
 add_filter('query_vars', 'drstk_add_query_var');
 function drstk_add_query_var($public_query_vars){
     $public_query_vars[] = 'drstk_template_type';
     return $public_query_vars;
 }

/**
 * This is the hook that will filter our template calls; it searches for the
 * drstk_template_type variable (which we set above) and then makes a
 * decision accordingly.
 */
add_filter('template_include', 'drstk_content_template', 1, 1);
function drstk_content_template( $template ) {
    global $wp_query;
    global $SOLR_TEMPLATE;

    if ( isset($wp_query->query_vars['drstk_template_type']) ) {

        $template_type = $wp_query->query_vars['drstk_template_type'];

        if ($template_type == 'browse') {
            add_action('wp_enqueue_scripts', 'drstk_browse_script');
            echo "template is browse";
            #return locate_template( array( 'view.php' ) );
            return $SOLR_TEMPLATE['browse_template'];

        }

        if ($template_type == 'item') {
            add_action('wp_enqueue_scripts', 'drstk_item_script');
            echo "template is item";
            #return locate_template( array( 'view.php' ) );
            $pid = get_query_var( 'pid' );
            echo "we are abotu to call get or create";
            get_or_create_solr_doc( $wp_query, $pid );
            return $SOLR_TEMPLATE['item_template'];
        }

    } else {
        return $template;
    }
} // end drstk_content_template

/**
 * Load scripts for the browse/search page
 *
 */
function drstk_browse_script() {
    global $VERSION;
    wp_register_script('ajax_solr_browse', plugins_url('/assets/js/browse.js', __FILE__), array(), $VERSION, true );
    wp_enqueue_script('ajax_solr_browse');
    //wp_localize_script('ajax_solr_browse', 'ajax_solr_vars', array('proxy_url' => plugins_url('/inc/NUSolrProxy.php', __FILE__) ) );
}

/**
 * Load scripts for the doc/page views
 */
function drstk_item_script() {
    global $VERSION;
    //wp_register_script('ajax_solr_jwplayer',plugins_url('/assets/js/jwplayer/jwplayer.js', __FILE__), array(), $VERSION, false );
    //wp_enqueue_script('ajax_solr_jwplayer');
}
