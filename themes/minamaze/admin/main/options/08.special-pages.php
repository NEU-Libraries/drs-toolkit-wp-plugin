<?php
/**
 * Special pages functions.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	404 - CUSTOM CONTENT
---------------------------------------------------------------------------------- */

function thinkup_input_404content() {

	echo	'<div class="entry-content title-404">',
			'<h2>Oops!</h2>',
			'<p>' . __( 'Sorry, we could not find the page you are looking for.', 'lan-thinkupthemes' ) . '<br/>' . __( 'Please try using the search function.', 'lan-thinkupthemes' ) . '</p>',
			get_search_form(),
			'</div>';
}


?>