<?php
/**
 * template for search/browse/collections/collection
 */

get_header();
$view = 'search';
?>

<div id="content">
	<?php quest_title_bar( $view ); ?>

	<div class="quest-row site-content">
		<div class="<?php echo apply_filters( 'quest_content_container_cls', 'container' ); ?>">
			<div id="drs-loading"></div>
			<div id="drs-selection" class="row" style="display:none">
			  <div>
				  <h5>Results filtered by: </h5>
			  </div>
			<div></div>
		  </div>
			<div id="drs-browse-header-row" class="row">
				<div id="drs-item-count"></div>
				<div id="drs-per-page-div"></div>
				<div id="drs-sort" style="display:none"></div>
				<div id="drs-pagination" >
					<ul class="pag pagination"></ul>
			  </div>
			</div><!-- #drs-browse-header-row -->
			<div class="row">

				<div id="primary">
					<main id="main" role="main">

						<div id="drs-content" class="container">
							<div class="row">
								<div id="drs-facets"></div>
						  	<div id="drs-docs">
							</div>
						  </div>
						</div><!-- #drs-content -->

					</main>
					<!-- #main -->
				</div>
				<!-- #primary -->

				<div id="secondary" class="widget-area main-sidebar" role="complementary">

				</div><!-- #secondary -->

			</div>
			<!-- .row -->
		</div>
		<!-- .container -->
	</div>
	<!-- .quest-row -->
</div><!-- #content -->
<?php get_footer(); ?>
