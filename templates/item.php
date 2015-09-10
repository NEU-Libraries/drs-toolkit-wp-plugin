<?php
/**
 * This is just a temporary placeholder template to demonstrate that it is working.
 * You will want to setup your own template for your theme to display the information.
 */

get_header(); ?>
<noscript>
<div class="noscript_warning">PLEASE NOTE: JAVASCRIPT IS DISABLED ON YOUR BROWSER. For the best user experience, please enable javascript on your browser now.</div>
<?php
if (!isset($_GET['js'])){
$request = explode('/', $_SERVER[REQUEST_URI]);
$url = site_url() . "/" . $request[2] . "/" . $request[3] . "?js=false";
 ?>
<meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"/>
<?php
}
?>
</noscript>

	<div id="primary" class="content-area container">
		<main id="main" class="site-main row" role="main">


<div id="drs-breadcrumbs" class="breadcrumb"></div>
<div id="drs-content" class="row">
  <div id="drs-loading"></div>
  <div id="drs-item-left" class="col-sm-6 one_half">
		<img id="drs-item-img"/>
	</div>
  <div id="drs-item-right" class="col-sm-6 last">
		<div id="drs-item-details"></div>
	</div>
</div><!-- #drs-content -->


</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
