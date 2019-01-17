<?php 

wp_register_script('drstk_cdn_jwplayer', 'https://content.jwplatform.com/libraries/dTFl0VEe.js');
wp_enqueue_script('drstk_cdn_jwplayer');

wp_register_style('drstk_podcasts_style', DRS_PLUGIN_URL . "assets/css/podcasts.css");
wp_enqueue_style('drstk_podcasts_style');


get_header();

$page_id = get_option('drstk_podcast_page');
$page_object = get_page( $page_id );

$queryOptions = array(
  'action' => 'search',
  'sub_action' => 'av',
);

$queryParams = array(
  'sort' => 'date_ssi+desc',
  'per_page' => '1',
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
							<ul class='republisher-links'>
						  	<li class='republisher-link' id='itunes-link'>
						  		<a href=''>
						  			<img src='<?php echo DRS_PLUGIN_URL . "assets/images/subscribe-iTunes-300x91.png"; ?>' 
						  			   alt='Subscribe in iTunes' />
						  		</a>
						  	</li>
						  	
						  	<li class='republisher-link' id='stitcher-link'>
						  		<a href=''>
						  			<img src='<?php echo DRS_PLUGIN_URL . "assets/images/Stitcher-button-300x110.jpg"; ?>' 
						  			   alt='Subscribe in Stitcher' />
						  		</a>
						  	</li>
						  	
						  	<li class='republisher-link' id='overcast-link'>
						  		<a href=''>
						  			<img src='<?php echo DRS_PLUGIN_URL . "assets/images/Overcastbutton2-300x106.png"; ?>' 
						  			   alt='Subscribe in Overcast' />
						  		</a>
						  	</li>
						  	
						  	<li class='republisher-link' id='googleplay-link'>
						  		<a href=''>
						  			<img src='<?php echo DRS_PLUGIN_URL . "assets/images/PodcastAvailableonGooglePlayMusic-300x110.png"; ?>' 
						  			   alt='Subscribe in Google Play' />
						  		</a>
						  	</li>
						  	
						  	<li class='republisher-link' id='spotify-link'>
						  		<a href=''>
						  			<img src='<?php echo DRS_PLUGIN_URL . "assets/images/Spotify-images-300x110.png"; ?>' 
						  			   alt='Subscribe in Spotify' />
						  		</a>
						  	</li>						  							  							  	
						  </ul>
						
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