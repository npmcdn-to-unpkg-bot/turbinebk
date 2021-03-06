<?php

//REGISTER ID
function add_query_vars_filter( $vars ){
  $vars[] = "location_id";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


function map_points_box() {
  if(basename(get_page_template()) != 'template-location.php') {
    return;
  }
	$screens = array( 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'map_points_box',
			"Map Points",
			'map_points_callback',
			$screen,
      'normal'
		);
	}
}
add_action( 'add_meta_boxes', 'map_points_box' );

//ADD IN REACT DOM
//ADD SCRIPT
function add_react_script($hook) {
  global $post;
  if(basename(get_page_template()) != 'template-location.php') {
    return;
  }
  if ( !('post.php' == $hook || 'post-new.php' == $hook) ) {
      return;
  }

  wp_enqueue_script( 'react_main', 'https://cdnjs.cloudflare.com/ajax/libs/react/0.14.8/react.js' );
  wp_enqueue_script( 'react_dom', 'https://cdnjs.cloudflare.com/ajax/libs/react/0.14.8/react-dom.js' );

  wp_enqueue_script( 'sortable_list_plugin', 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.4.2/Sortable.min.js' );
  wp_enqueue_script( 'draggable_plugin', 'https://npmcdn.com/draggabilly@2.1/dist/draggabilly.pkgd.min.js' );
  wp_enqueue_style('main_style', get_bloginfo('template_url').'/map-component/main.css');


}
add_action( 'admin_enqueue_scripts', 'add_react_script' );
add_action('admin_footer-post.php', 'location_bottom_scripts'); // Fired on post edit page
add_action('admin_footer-post-new.php', 'location_bottom_scripts'); // Fired on add new post page

function location_bottom_scripts() {
  if(basename(get_page_template()) != 'template-location.php') {
    return;
  }
  global $post;
   include 'location-bottom.php';?>
  <script src="<?php echo get_bloginfo('template_url');?>/map-component/plain.js"></script>

  <script src="<?php echo get_bloginfo('template_url');?>/map-component/build.js"></script>
  <?php
}



function map_points_callback( $post ) {
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'map_points_data', 'map_points_nonce' );
  include 'main-point-template.php';

}
include 'category-section.php';
include 'save-functions.php';

include 'map-upload.php';
?>
