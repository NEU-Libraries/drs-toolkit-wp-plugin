<?php
/**
 * Template Name: Sitemap
 *
 * @package ThinkUpThemes
 */

get_header(); ?>

			<div class="one_half">
				<h3 class="page-title"><?php _e( 'Pages', 'lan-thinkupthemes'); ?>:</h3>
				<ul class="sitemap-pages">
					<?php wp_list_pages('title_li='); ?>
				</ul> 

				<h3 class="page-title"><?php _e( 'Author(s)', 'lan-thinkupthemes'); ?>:</h3>
				<ul class="sitemap-authors">
					<?php wp_list_authors( 'optioncount=1' ); ?>
				</ul>
			</div> 
		 
			<div class="one_half last">
				<h3 class="page-title"><?php _e( 'Posts', 'lan-thinkupthemes'); ?>:</h3>
				<ul class="sitemap-posts">
					<?php $args=array(
					           'orderby' => 'name',
					           'pad_counts' => '1'
						  );

					$cats = get_categories( $args );
					foreach ( $cats as $cat ) {
					  echo '<li class="category"><a href="' . get_category_link($cat->term_id) . '">' . __( 'Category:', 'lan-thinkupthemes' ) . ' ' . $cat->cat_name . ' (' . $cat->category_count . ')' . "\n";
					  echo '<ul class="children">'."\n";
					  query_posts('posts_per_page=-1&cat='.$cat->cat_ID);
					  while(have_posts()): the_post();
						 $category = get_the_category();
					?>
							<li><a href="<?php the_permalink() ?>"  title="<?php _e( 'Permanent Link to', 'lan-thinkupthemes'); ?>: <?php the_title(); ?>">
							<?php the_title(); ?></a></li>
					   <?php endwhile; wp_reset_query(); ?>
					  </ul>
					  </li>
					<?php } ?>
				</ul>
				<?php
				wp_reset_query();
				?>

				<h3 class="page-title"><?php _e( 'Archives', 'lan-thinkupthemes'); ?>:</h3>
				<ul class="sitemap-archives">
					<?php wp_get_archives('type=monthly&show_post_count=true'); ?>
				</ul>
			</div>

<?php get_footer(); ?>