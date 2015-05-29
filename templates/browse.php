<?php
/**
 * This is just a temporary placeholder template to demonstrate that it is working.
 * You will want to setup your own template for your theme to display the information.
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">



<div id="drs-content">
  <div id="drs-browse-header-row">
    <h2 id="drs-pagination-header"></h2>
    <ul id="drs-pagination"></ul>
    <div id="drs-search" class="hidden">
      <label for="drs-input" class="sr-only">Search:</label>
      <input id="drs-input" name="drs-input" autocomplete="off" type="text" placeholder="Enter search term">
    </div>
  </div><!-- .row -->

  <div class="row">
    <div class="col-sm-8 col-md-9">

      <ul id="drs-selection" class="list-inline"></ul>

      <div id="drs-docs" class="row">
        <div id="drs-docs-even" class="col-sm-12 col-md-6"></div>
        <div id="drs-docs-odd" class="col-sm-12 col-md-6"></div>
      </div>

    </div>

    <div class="col-sm-4 col-md-3">
      <div id="drs-facets" class="panel panel-default"></div>
    </div>

  </div><!-- .row -->



</div>

</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
