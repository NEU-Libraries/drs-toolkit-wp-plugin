<?php
/**
 * The template for displaying search forms.
 *
 * @package ThinkUpThemes
 */
/*THIS OVERRIDES /minamaze/searchform.php*/
?>

	<div id="drs-search">
		<form method="get" action="<?php echo esc_url( home_url( '/search/' ) ); ?>" class="searchform">
			<label for="drs-input" class="sr-only">Search:</label>
			<input id="drs-input" name="q" class="search" autocomplete="off" type="text" placeholder="Enter search term" value="<?php echo esc_attr( get_search_query() ); ?>">
			<input type="submit" class="searchsubmit" value="Search">
		</form>
	</div>
