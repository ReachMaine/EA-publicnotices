<?php
	$cwk_thumbimg = array(200, 999); // size of featured image in archive/category blog
	$cwk_postimg = array(200, 999); // size of featured image on single post.
	add_image_size( 'cwk-slider', 1420, 447, true ); // Slider

	add_action('after_setup_theme', ea_setup);

	 register_sidebar(array(
			'name' => 'Top Banner Ad',
			'id' => 'topbanner',
			'description' => 'Widget for a top banner ad.',
			'before_widget' => '<div class="topad"><div id="%1$s" class=" %2$s ad-container">',
			'after_widget'  => '</div></div>'

	));

	/**  ea_setup
	*  init stuff that we have to init after the main theme is setup.
	*
	*/
	function ea_setup() {

	 	/* add favicons for admin */
		add_action('login_head', 'add_favicon');
		add_action('admin_head', 'add_favicon');
	}
	function add_favicon() {
	  	$favicon_url = get_stylesheet_directory_uri() . '/images/admin-favicon.ico';
		echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
	}

	//enqueue the init script for broadstreet.
	// zig 1Feb18
	wp_enqueue_script( 'broadstreet', '//cdn.broadstreetads.com/init.js');

?>
