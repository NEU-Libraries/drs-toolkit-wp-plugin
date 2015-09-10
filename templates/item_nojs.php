<?php
/**
 * The template for DRSTK items.
 */

get_header();
$view = quest_get_view();
?>

<div id="content">
	<div class="quest-row" id="title-container">
		<div class="<?php echo apply_filters( 'quest_content_container_cls', 'container' ); ?> title-container">
			<div class="row">
				<div class="col-md-6">
					<h3><?php get_item_title(); ?></h3>
				</div>
				<div class="col-md-6">
					<ul class="breadcrumbs">
						<?php get_item_breadcrumbs(); ?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="quest-row site-content">
		<div class="<?php echo apply_filters( 'quest_content_container_cls', 'container' ); ?>">
			<div class="row">
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

				<div id="primary" class="content-area single col-md-12">
					<main id="main" class="site-main" role="main">
            <div id="drs-content" class="row">
              <div id="drs-loading"></div>
              <div id="drs-item-left" class="col-sm-6 one_half">
            		<img id="drs-item-img" src="<?php get_item_image(); ?>"/>
            	</div>
              <div id="drs-item-right" class="col-sm-6 last">
            		<div id="drs-item-details"><?php get_item_details(); ?></div>
            	</div>
            </div><!-- #drs-content -->

					</main>
					<!-- #main -->
				</div>
				<!-- #primary -->
			</div>
			<!-- .row -->
		</div>
		<!-- .container -->
	</div>
	<!-- .quest-row -->
</div><!-- #content -->
<?php get_footer(); ?>
