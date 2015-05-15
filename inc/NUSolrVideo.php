<?php
/**
 * NUSolrVideo extends NUSolrDoc
 *
 * It's specific to the NIEC's Solr implementation and limits the queries
 * to its 'colid'.
 *
 * Also includes a helper function for getting a video document or returning
 * a 404 message
 */

// limits all NUSolrVideo documents to come from the following colid
$QUERY_BASE_FOR_VIDEO_OBJECTS = 'colid:"neu:191160"';

/**
 * Will either get or create and then get the matching post for a given
 * solrdoc based on its ID.  If the post doesn't exist it will be created.
 */
function get_or_create_solr_doc( $wp_query, $solr_doc_id ){
    echo "WE MADE IT TO GET OR CREATE";
    global $solrdoc;    // $solrdoc will be available to our theme templates

    $solrpid = 'neu:' . $solr_doc_id;

    // try to retrieve a solr document based on the pid
    // if we can't get one, then return a 404 and let's get out of here
    try {
        $solrdoc = new NUSolrVideo( $solrpid );
    } catch (Exception $e) {
        if( $e instanceof NoResponseFromServer ) {
            echo $e->getMessage();
        }
        $wp_query->is_404 = true;
        return;
    }

    // now with our $solrdoc, we need to related it to a post in WordPress
    $query_posts_args = array(
        'meta_query' => array(
            array( 'key' => 'solr_doc_id', 'value' => $solr_doc_id),
            ),
        'post_type' => 'solrdoc',
        'posts_per_page' => -1,
    );

    $the_posts = get_posts( $query_posts_args );
    $post_id = 0;

    if ( $the_posts ) {

        if ( count($the_posts) == 1 ) {

            $post_id = $the_posts[0]->ID;

            $post_modified = new DateTime($the_posts[0]->post_modified);
            $diff = $post_modified->diff( new DateTime() );

            // update post information once per 24 hours
            if ( $diff->h > 24 ) {
                insert_solr_post( $solr_doc_id, $post_id );
            }

        } else {
            foreach ($the_posts as $post) {
                wp_delete_post( $post->ID );
            }
            $post_id = insert_solr_post( $solr_doc_id );
        }

    } else {

        $post_id = insert_solr_post( $solr_doc_id );

    }

    // reset the $wp_query here so that it returns a single page
    query_posts( array( 'p' => $post_id, 'post_type' => 'solrdoc' ) );

    // check if it's featured or not
    $is_featured = get_post_meta( $post_id, 'solr_doc_featured', true );
    $solrdoc->is_featured = ($is_featured != '' ? true : false );

}

/**
 * Inserts or updates a post based on the $solr_doc_id
 */
function insert_solr_post( $solr_doc_id, $post_id = 0) {
    global $solrdoc;

    $update_post = ( $post_id ? true : false );

    $args = array (
        'post_title' => $solrdoc->str('title_display'),
        'post_type' => 'solrdoc',
        'post_author' => 1,
        'post_status' => 'publish',
        'comment_status' => 'open',
    );

    if ( $update_post ) {
      $args['ID'] = $post_id;
    }

    $post_id = wp_insert_post( $args );

    if ( $post_id != 0) {
        if ( !$update_post ) {
            update_post_meta ( $post_id, 'solr_doc_id', $solr_doc_id );
        }
        update_post_meta ( $post_id, 'solr_doc_poster', $solrdoc->str('poster') );
        update_post_meta ( $post_id,
            'solr_doc_learning_object',
            $solrdoc->has_learning_objects() );
    }

    return $post_id;
}
