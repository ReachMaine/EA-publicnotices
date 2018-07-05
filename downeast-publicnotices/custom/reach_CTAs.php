<?php /* Call to actions  top & bottom... */
/*  add widget areas */
function reach_widgets_init() {
  register_sidebar(
    array(
     'name' => __( 'In header', 'be-themes' ),
     'id'   => 'reach-header-widgetarea',
     'description'   => __( 'Widget area (in the header)', 'be-themes' ),
     'before_widget' => '<div class="%2$s widget">',
     'after_widget'  => '</div>',
     'before_title'  => '<h6>',
     'after_title'   => '</h6>',
    )
  );
/* not using these yet.
  // if using, added in header.php
  register_sidebar(
    array(
     'name' => __( 'Above Content', 'be-themes' ),
     'id'   => 'reach-above-content',
     'description'   => __( 'Widget area (above content)', 'be-themes' ),
     'before_widget' => '<div class="%2$s widget">',
     'after_widget'  => '</div>',
     'before_title'  => '<h6>',
     'after_title'   => '</h6>',
    )
  );
  // if using, added in footer.php
  register_sidebar(
      array(
           'name' => __( 'Under Content ', 'be-themes' ),
           'id'   => 'reach-under-content',
           'description'   => __( 'Widget area under content area', 'be-themes' ),
           'before_widget' => '<div class="%2$s widget">',
           'after_widget'  => '</div>',
           'before_title'  => '<h6>',
           'after_title'   => '</h6>',
      )
    );
      */
  register_sidebar(
        array(
         'name' => __( 'Bottom Call to Action ', 'be-themes' ),
         'id'   => 'reach-bottom-cta',
         'description'   => __( 'Widget area (above footer)', 'be-themes' ),
         'before_widget' => '<div class="%2$s widget">',
         'after_widget'  => '</div>',
         'before_title'  => '<h6>',
         'after_title'   => '</h6>',
      )
  );

}
add_action( 'widgets_init', 'reach_widgets_init' );
