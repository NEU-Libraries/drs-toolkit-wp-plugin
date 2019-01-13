<?php 

get_header();

$page_id = 348;
$page_object = get_page( $page_id );


?>


<div id="content">

	<div class="quest-row site-content">
		<div class="<?php echo apply_filters( 'quest_content_container_cls', 'container' ); ?>">
			<div id="drs-loading"></div>
			<div class="row">
				<div id="primary" class="content-area col-md-9">
				
					<main id="main" class="site-main" role="main">
					  <?php echo $pageID = get_option('page_on_front'); ?>
					  <?php wp_dropdown_pages( array('selected' => 0) ); ?>
					
						<h1><?php echo $page_object->post_title; ?></h1>
						<div id="drs-content" class="container">
						  <div class="row">
						    <?php echo $page_object->post_content; ?>
						  </div>
							<div class="row">
								<article>
								<h2>Podcast 1</h2>
								</article>
								
								<article>
								<h2>Podcast 2</h2>
								</article>								
								
								<article>
								<h2>Podcast 3</h2>
								</article>
																
								<article>
								<h2>Podcast 4</h2>
								</article>
																
								<article>
								<h2>Podcast 5</h2>
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