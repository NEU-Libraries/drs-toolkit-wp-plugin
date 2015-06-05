<?php
/**
 * The template for displaying content where a specific template is not available.
 *
 * @package ThinkUpThemes
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

		<h2 class="search-title">

		<?php echo '<a href="' . get_permalink() . '" title="' . esc_attr( sprintf( __( 'Permalink to %s', 'lan-thinkupthemes' ), the_title_attribute( 'echo=0' ) ) ) . '">' . get_the_title() . '</a>'; ?>

		</h2>

	</header><!-- .entry-header -->

	<?php if ( is_search() ) : ?>

		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->

	<?php else : ?>

		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->

	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->