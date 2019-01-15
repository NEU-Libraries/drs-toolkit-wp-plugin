<?php 

get_header();

$page_id = get_option('drstk_podcast_page');
//$page_id = 348;
$page_object = get_page( $page_id );

$queryOptions = array(
  'action' => 'search',
    
);

$queryParams = array(
  'sort' => 'date_ssi+asc', 
);

$fetcher = new Ceres_Drs_Fetcher($queryOptions, $queryParams);
echo $fetcher->buildQueryString();

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
							<div class="row">
								<article>
									<h3>Podcast 1</h3>
								</article>
								
								<article>
									<h3>Podcast 2</h3>
								</article>								
								
								<article>
									<h3>Podcast 3</h3>
								</article>
																
								<article>
									<h3>Podcast 4</h3>
								</article>
																
								<article>
									<h3>Podcast 5</h3>
								</article>
																
								<div id="drs-facets" class="one_fourth col-md-3 hidden-phone hidden-xs hidden-sm"></div>
						  	<div id="drs-docs" class="three_fourth col-md-9 last">
						  	
							  </div>
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