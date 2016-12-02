<?php
/**
 * template for mirador viewer
 */

get_header();
$view = quest_get_view();

?>

<div id="content">
	<?php quest_title_bar($view); ?>

	<div class="quest-row site-content">
		<div class="<?php echo apply_filters( 'quest_content_container_cls', 'container' ); ?>">
			<div class="row">

				<div id="primary" class="content-area col-md-12">
					<main id="main" class="site-main" role="main">

						<div id="mirador_viewer"></div>

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
