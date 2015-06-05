<?php
/**
 * Blog functions.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	HIDE POST TITLE
---------------------------------------------------------------------------------- */

function think_input_blogtitle() {

	echo	'<h2 class="blog-title">',
			'<a href="' . get_permalink() . '" title="' . esc_attr( sprintf( __( 'Permalink to %s', 'lan-thinkupthemes' ), the_title_attribute( 'echo=0' ) ) ) . '">' . get_the_title() . '</a>',
			'</h2>';
}


/* ----------------------------------------------------------------------------------
	BLOG CONTENT
---------------------------------------------------------------------------------- */

/* Input post thumbnail / featured media */
function thinkup_input_blogimage() {
global $post;

	if ( has_post_thumbnail() ) {
		echo '<div class="blog-thumb"><a href="'. get_permalink($post->ID) . '">' . get_the_post_thumbnail( $post->ID, 'column2-3/4' ) . '</a></div>';
	}
}

/* Input post excerpt / content to blog page */
function thinkup_input_blogtext() {
global $thinkup_blog_postswitch;

	/* Output post thumbnail / featured media */
	if ( $thinkup_blog_postswitch == 'option1' or empty( $thinkup_blog_postswitch ) ) {
		the_excerpt();
	} else if ( $thinkup_blog_postswitch == 'option2' ) {		
		the_content();
	}
}


/* ----------------------------------------------------------------------------------
	BLOG META CONTENT & POST META CONTENT
---------------------------------------------------------------------------------- */

// Input sticky post
function thinkup_input_sticky() {
	printf( '<span class="sticky"><i class="icon-pushpin"></i><a href="%1$s" title="%2$s">' . __( 'Sticky', 'lan-thinkupthemes' ) . '</a></span>',
		esc_url( get_permalink() ),
		esc_attr( get_the_title() )
	);
}

/* Input blog date*/
function thinkup_input_blogdate() {
	printf( __( '<span class="date"><i class="icon-calendar-empty"></i><a href="%1$s" title="%2$s"><time datetime="%3$s">%4$s</time></a></span>', 'lan-thinkupthemes' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_title() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
}

/* Input blog comments */
function thinkup_input_blogcomment() {

	if ( '0' != get_comments_number() ) {
	echo	'<span class="comment"><i class="icon-comments"></i>';
		if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) {;
			comments_popup_link( __( '0 comments', 'lan-thinkupthemes' ), __( '1 comment', 'lan-thinkupthemes' ), __( '% comments', 'lan-thinkupthemes' ) );
		};
	echo	'</span>';
	}
}

/* Input blog categories */
function thinkup_input_blogcategory() {
$categories_list = get_the_category_list( __( ', ', 'lan-thinkupthemes' ) );

	if ( $categories_list && thinkup_input_categorizedblog() ) {
		echo	'<span class="category"><i class="icon-folder-open"></i>';
		printf( __( '%1$s', 'lan-thinkupthemes' ), $categories_list );
		echo	'</span>';
	};
}

/* Input blog tags */
function thinkup_input_blogtag() {
$tags_list = get_the_tag_list( '', __( ', ', 'lan-thinkupthemes' ) );

	if ( $tags_list ) {
		echo	'<span class="tags"><i class="icon-tags"></i>';
		printf( __( '%1$s', 'lan-thinkupthemes' ), $tags_list );
		echo	'</span>';
	};
}

/* Input blog author */
function thinkup_input_blogauthor() {
	printf( __( '<span class="author"><i class="icon-pencil"></i>By <a href="%1$s" title="%2$s" rel="author">%3$s</a></span>', 'lan-thinkupthemes' ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'lan-thinkupthemes' ), get_the_author() ) ),
		get_the_author()
	);
}


/* ----------------------------------------------------------------------------------
	INPUT BLOG META CONTENT
---------------------------------------------------------------------------------- */

function thinkup_input_blogmeta() {
global $thinkup_blog_date;
global $thinkup_blog_author;
global $thinkup_blog_comment;
global $thinkup_blog_category;
global $thinkup_blog_tag;

	if ( $thinkup_blog_date !== '1' or 
		$thinkup_blog_author !== '1' or 
		$thinkup_blog_comment !== '1' or 
		$thinkup_blog_category !== '1' or 
		$thinkup_blog_tag !== '1') {

		echo '<div class="entry-meta">';
			if ( is_sticky() && is_home() && ! is_paged() ) { thinkup_input_sticky(); }

			if ($thinkup_blog_author !== '1')   { thinkup_input_blogauthor();   }
			if ($thinkup_blog_date !== '1')     { thinkup_input_blogdate();     }
			if ($thinkup_blog_comment !== '1')  { thinkup_input_blogcomment();  }	
			if ($thinkup_blog_category !== '1') { thinkup_input_blogcategory(); }
			if ($thinkup_blog_tag !== '1')      { thinkup_input_blogtag();      }
		echo '</div>';
	}
}


/* ----------------------------------------------------------------------------------
	INPUT POST META CONTENT
---------------------------------------------------------------------------------- */
function thinkup_input_postmeta() {
global $thinkup_post_date;
global $thinkup_post_author;
global $thinkup_post_comment;
global $thinkup_post_category;
global $thinkup_post_tag;

	if ( $thinkup_post_date !== '1' or 
		$thinkup_post_author !== '1' or 
		$thinkup_post_comment !== '1' or 
		$thinkup_post_category !== '1' or 
		$thinkup_post_tag !== '1') {

		echo '<header class="entry-header entry-meta">';
			if ($thinkup_post_author !== '1')   { thinkup_input_blogauthor();   }
			if ($thinkup_post_date !== '1')     { thinkup_input_blogdate();     }
			if ($thinkup_post_comment !== '1')  { thinkup_input_blogcomment();  }	
			if ($thinkup_post_category !== '1') { thinkup_input_blogcategory(); }
			if ($thinkup_post_tag !== '1')      { thinkup_input_blogtag();      }
		echo '</header><!-- .entry-header -->';
	}
}


/* ----------------------------------------------------------------------------------
	SHOW AUTHOR BIO - PREMIUM FEATURE
---------------------------------------------------------------------------------- */


/* ----------------------------------------------------------------------------------
	SHOW SOCIAL SHARING - PREMIUM FEATURE
---------------------------------------------------------------------------------- */


/* ----------------------------------------------------------------------------------
	TEMPLATE FOR COMMENTS AND PINGBACKS (PREVIOUSLY IN TEMPLATE-TAGS).
---------------------------------------------------------------------------------- */
function thinkup_input_commenttemplate( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'lan-thinkupthemes'); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'lan-thinkupthemes' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">

			<header>
				<?php echo get_avatar( $comment, 60 ); ?>

				<span class="comment-author">
					<?php printf( '%s', sprintf( '%s', get_comment_author_link() ) ); ?>
				</span>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'lan-thinkupthemes'); ?></em>
					<br />
				<?php endif; ?>

				<span class="comment-meta">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( '%1$s', get_comment_date() ); ?>
					</time></a>
					<?php edit_comment_link( __( 'Edit', 'lan-thinkupthemes' ), ' ' );
					?>
				</span>

				<span class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</span>
			</header>

			<footer>
				<div class="comment-content"><?php comment_text(); ?></div>
			</footer>
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}


/* List comments in single page */
function thinkup_input_comments() {
	$args = array( 
		'callback' => 'thinkup_input_commenttemplate', 
	);
	wp_list_comments( $args );
}


?>