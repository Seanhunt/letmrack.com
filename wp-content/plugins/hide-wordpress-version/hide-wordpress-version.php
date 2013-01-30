<?php
/*
Plugin Name: Hide WordPress Version
Description: Removes your WordPress version from various places.
Author: Adam Harley
Version: 1.0.1
*/

class HideWordPressVersion {

	static function wp_version() {
		$GLOBALS['wp_version'] = rand( 99, 999 );
	}

	static function update_nag() {
		if ( ! current_user_can('update_core') )
			remove_action( 'admin_notices', 'update_nag', 3 );
	}

	static function update_right_now_message( $translation, $text ) {
		if ( 'You are using <span class="b">WordPress %s</span>.' != $text || current_user_can( 'update_core' ) )
			return $translation;
	}

	static function admin_footer() {
		if ( !current_user_can( 'update_core' ) )
			remove_filter( 'update_footer', 'core_update_footer' );
	}


	static function scripts() {
		global $wp_scripts;
		if ( !is_a( $wp_scripts, 'WP_Scripts' ) )
			return;
		foreach ( $wp_scripts->registered as $handle => $script ) {
			if ( $script->ver === false )
				$wp_scripts->registered[$handle]->ver = null;
		}
	}

	static function styles() {
		global $wp_styles;
		if ( !is_a( $wp_styles, 'WP_Styles' ) )
			return;
		foreach ( $wp_styles->registered as $handle => $style ) {
			if ( $style->ver === false )
				$wp_styles->registered[$handle]->ver = null;
		}
	}


	static function http() {
		return 'WordPress; ' . get_bloginfo( 'url' );
	}

	static function xmlrpc( $blog_options ) {
		unset( $blog_options['software_version'] );
		return $blog_options;
	}

	static function pingback( $new_useragent, $useragent ) {
		return "{$useragent} -- WordPress";
	}


	static function bloginfo( $output, $show ) {
		if ( $show != 'version' )
			return $output;
	}

}


if ( is_admin() ) {
	if ( !is_multisite() ) {
		add_action( 'admin_notices', array('HideWordPressVersion','update_nag'), 2 );
		add_action( 'update_footer', array('HideWordPressVersion','admin_footer'), 1 );
		if ( 'index.php' == $GLOBALS['pagenow'] )
			add_action( 'gettext', array('HideWordPressVersion','update_right_now_message'), 1, 2 );
	}

	add_action( 'admin_print_styles', array('HideWordPressVersion','styles'), 100 );
} else {
	add_action( 'init', array('HideWordPressVersion','wp_version'), 1 );

	add_action( 'wp_print_scripts', array('HideWordPressVersion','scripts'), 100 );
	add_action( 'wp_print_footer_scripts', array('HideWordPressVersion','scripts'), 100 );
	add_action( 'wp_print_styles', array('HideWordPressVersion','styles'), 100 );

	remove_action( 'wp_head', 'wp_generator' );
	foreach ( array( 'rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head' ) as $hwv_action )
		remove_action( $hwv_action, 'the_generator' );
	unset($hwv_action);

	add_filter( 'bloginfo', array('HideWordPressVersion','bloginfo'), 100, 2 );
}

add_filter( 'http_headers_useragent', array('HideWordPressVersion','http'), 100 );
add_filter( 'xmlrpc_blog_options', array('HideWordPressVersion','xmlrpc'), 100 );
add_filter( 'pingback_useragent', array('HideWordPressVersion','pingback'), 100, 2 );