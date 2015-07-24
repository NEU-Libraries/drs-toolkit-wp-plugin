<?php
function drstk_add_gallery(){
  echo '<a href="#" id="drstk_insert_gallery" class="button" title="Insert shortcode">Insert shortcode</a>';
}

/* adds shortcode */
add_shortcode( 'drstk_gallery', 'drstk_gallery' );
function drstk_gallery( $atts ){
  echo '<div id="slider"><div id="slider-core">',
           '<div class="rslides-container"><div class="rslides-inner"><ul class="slides">';
  echo '<li><img src="' . get_template_directory_uri() . '/images/transparent.png" style="background: url(' . get_template_directory_uri() . '/images/slideshow/slide_demo1.png) no-repeat center; background-size: cover;" alt="Demo Image" /></li>';
  echo '<li><img src="' . get_template_directory_uri() . '/images/transparent.png" style="background: url(' . get_template_directory_uri() . '/images/slideshow/slide_demo2.png) no-repeat center; background-size: cover;" alt="Demo Image" /></li>';
  echo '<li><img src="' . get_template_directory_uri() . '/images/transparent.png" style="background: url(' . get_template_directory_uri() . '/images/slideshow/slide_demo3.png) no-repeat center; background-size: cover;" alt="Demo Image" /></li>';
  echo '</ul></div></div>',
           '</div></div><div class="clearboth"></div>';
}
