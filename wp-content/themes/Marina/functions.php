<?php
include 'theme_options.php';


if ( function_exists('register_sidebar') )
	register_sidebar(array(
	'name' => 'Sidebar',
    'before_widget' => '<div class="sidebox ">',
    'after_widget' => '</div>',
	'before_title' => '<h3 class="sidetitl">',
    'after_title' => '</h3>',
	
    ));


register_sidebar(array(
	'name' => 'Footer',
    'before_widget' => '<div class="botwid">',
    'after_widget' => '</div>',
	'before_title' => '<h3 class="bothead">',
    'after_title' => '</h3>',
    ));		
	
	
register_nav_menus( array(
		'primary' => __( 'Primary Navigation', '' ),
		'secondary' => __( 'Secondary Navigation', '' ),
		
	) );	
	
function new_excerpt_length($length) {
	return 50;
}
add_filter('excerpt_length', 'new_excerpt_length');

function new_excerpt_more($more) {
return '<a href="'. get_permalink($post->ID) . '">' . '&nbsp;&nbsp;[ Read More ]' . '</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'marina_slider', 350, 200, true );
	add_image_size( 'marina_post', 150, 150, true );
}


function marina_slide_image(){
if ( has_post_thumbnail() ) {
	 the_post_thumbnail( 'marina_slider', array('class' => 'slidim') );
} else {
?>
<img class="slidim" src="<?php bloginfo('template_directory'); ?>/images/dummy.jpg"  />
<?php
};
}

function marina_post_image(){
if ( has_post_thumbnail() ) {
	 the_post_thumbnail( 'marina_post', array('class' => 'postimg') );
} else {
?>
<img class="postimg" src="<?php bloginfo('template_directory'); ?>/images/dummy2.jpg"  />
<?php
};
}


	
?>