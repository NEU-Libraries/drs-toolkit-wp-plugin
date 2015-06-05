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
	<div id="drs-loading"></div>
	<div id="drs-selection" class="hide">You've selected: </div>
  <div id="drs-browse-header-row">
		<div id="drs-item-count" class="one_fourth"></div>
		<div id="drs-per-page-div" class="one_fourth"></div>
		<div id="drs-sort" class="hide one_fourth"></div>
  	<div id="drs-pagination" class="one_fourth last"><ul class="pag"></ul></div>
  </div><!-- #drs-browse-header-row -->
	<div id="drs-facets" class="one_fourth"></div>
  <div id="drs-docs" class="three_fourth last">
  </div>
</div><!-- #drs-content -->

<?php get_footer(); ?>
