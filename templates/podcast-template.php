<?php 

wp_register_script('drstk_cdn_jwplayer', 'https://content.jwplatform.com/libraries/dTFl0VEe.js');
wp_enqueue_script('drstk_cdn_jwplayer');

get_header();

$page_id = get_option('drstk_podcast_page');
$page_object = get_page( $page_id );

$queryOptions = array(
  'action' => 'search',
  'sub_action' => 'av',
);

$queryParams = array(
  'sort' => 'date_ssi+desc',
  'per_page' => '20',
);

$resourceId = drstk_get_pid();

$fetcher = new Ceres_Drs_Fetcher($queryOptions, $queryParams);
$renderer = new Ceres_Podcast_Renderer($fetcher, $resourceId);
echo $fetcher->buildQueryString();
$fetcher->fetchData();
$fetcher->parseItemsData();
$itemsData = $fetcher->getItemsData();


?>


<div id="content">
	<div class="quest-row site-content">
		<div class="<?php echo apply_filters( 'quest_content_container_cls', 'container' ); ?>">
			<div id="drs-loading"></div>
			<div class="row">
				<div id="primary" class="content-area col-md-9">
				
					<main id="main" class="site-main" role="main">
					
						<h2><?php echo apply_filters('the_title', $page_object->post_title);?></h2>
						<div id="drs-content" class="container">
						  <div class="row">
						    <?php echo apply_filters('the_content', $page_object->post_content);?>
						  </div>
								<?php echo $renderer->render(); ?>  																
  							<div id="drs-facets" class="one_fourth col-md-3 hidden-phone hidden-xs hidden-sm"></div>
  					  	<div id="drs-docs" class="three_fourth col-md-9 last"></div>
						</div>

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