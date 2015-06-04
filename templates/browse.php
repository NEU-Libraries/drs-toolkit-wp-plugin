<?php
/**
 * This is just a temporary placeholder template to demonstrate that it is working.
 * You will want to setup your own template for your theme to display the information.
 */
get_header(); ?>
<noscript>
<div class="noscript_warning">PLEASE NOTE: JAVASCRIPT IS DISABLED ON YOUR BROWSER. For the best user experience, please enable javascript on your browser now.</div>
</noscript>

<div id="drs-content">
	<div id="drs-search"><!-- class="hidden" this class will depend on theme-->
		<label for="drs-input" class="sr-only">Search:</label>
		<input id="drs-input" name="drs-input" autocomplete="off" type="text" placeholder="Enter search term">
		<input type="submit" value="Search" />
	</div>
  <div id="drs-browse-header-row" class="pagination">
    <h4>
			<div id="drs-pagination-header"></div>
			<div id="drs-sort"></div>
    	<ul id="drs-pagination"></ul>
		</h4>
  </div><!-- #drs-browse-header-row -->
  <ul id="drs-selection"></ul>
	<div id="drs-facets" class="one_fourth"></div>
  <div id="drs-docs" class="three_fourth">
    <!-- <div id="drs-docs-even" class="col-sm-12 col-md-6"></div>
    <div id="drs-docs-odd" class="col-sm-12 col-md-6"></div> -->
  </div>
</div><!-- #drs-content -->

<?php get_footer(); ?>
