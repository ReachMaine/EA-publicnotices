<?php

  require_once(get_stylesheet_directory().'/custom/branding.php');
  require_once(get_stylesheet_directory().'/custom/language.php');
  require_once(get_stylesheet_directory().'/custom/reach_CTAs.php');
  require_once(get_stylesheet_directory().'/custom/oshine.php');
  require_once(get_stylesheet_directory().'/custom/tribe_events.php');

  //enqueue the init script for broadstreet ads
	wp_enqueue_script( 'broadstreet', '//cdn.broadstreetads.com/init.js');
?>
