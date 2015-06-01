<?php
/**
 * This is just a temporary placeholder template to demonstrate that it is working.
 * You will want to setup your own template for your theme to display the information.
 */

get_header(); ?>
<noscript>

<div class="noscript_warning">PLEASE NOTE: JAVASCRIPT IS DISABLED ON YOUR BROWSER. For the best user experience, please enable javascript on your browser now.</div>
<?php
 drstk_no_javascript_alternative();
?>

</noscript>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">



<div id="drs-content">
  <div id="drs-browse-header-row">
    <h2 id="drs-pagination-header"></h2>
    <ul id="drs-pagination"></ul>
    <div id="drs-search"><!-- class="hidden" this class will depend on theme-->
      <label for="drs-input" class="sr-only">Search:</label>
      <input id="drs-input" name="drs-input" autocomplete="off" type="text" placeholder="Enter search term">
			<input type="submit" value="Search" />
    </div>
  </div><!-- #drs-browse-header-row -->
	<div id="drs-sort"></div>
  <ul id="drs-selection"></ul>
  <div id="drs-docs">
    <div id="drs-docs-even" class="col-sm-12 col-md-6"></div>
    <div id="drs-docs-odd" class="col-sm-12 col-md-6"></div>
  </div>
  <div id="drs-facets" class="panel panel-default"></div>
</div><!-- #drs-content -->


</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
