<?php
/*****************************************************************************************
* Theme Options Functions
* 
*  - Define Default Theme Options
*  - Register/Initialize Theme Options
*  - Define Admin Settings Page
*  - Register Contextual Help
*******************************************************************************************/

global $wpnj_distinction_options;
global $wpnj_distinction_admin_options_hook;

/* Helper Functions
*******************************************************************************************/

function wpnj_distinction_get_default_options() {

    $options = array(
        'header_menu_position' => 'below',
		'header_menu_depth' => 3,
		'display_header_flames' => true,
		'display_site_description' => true,
		'display_menu_search_form' => true,
		'analytics_code' => '',
        'display_footer_credit' => true,
		'sidebar_position' => 'right',
		'webfonts' => 'architects_daughter',
        'theme_version' => '1.1'
    );
    return $options;
}

function wpnj_distinction_get_valid_webfonts() {
	
	$webfonts = array( 
        	'architects_daughter' => array(
				'slug' => 'architects_daughter',
				'name' => 'Architects Daughter',
				'nameplus' => 'Architects+Daughter'
			),
			'megrim' => array(
				'slug' => 'megrim',
				'name' => 'Megrim',
				'nameplus' => 'Megrim'
			),
			'medievalsharp' => array(
				'slug' => 'medievalsharp',
				'name' => 'MedievalSharp',
				'nameplus' => 'MedievalSharp'
			),
			'specialelite' => array(
				'slug' => 'specialelite',
				'name' => 'Special Elite',
				'nameplus' => 'Special+Elite'
			),
			'quattrocentosans' => array(
				'slug' => 'quattrocentosans',
				'name' => 'Quattrocento Sans',
				'nameplus' => 'Quattrocento+Sans'
			),
			'kristi' => array(
				'slug' => 'kristi',
				'name' => 'Kristi',
				'nameplus' => 'Kristi'
			),
			'dawningofanewday' => array(
				'slug' => 'dawningofanewday',
				'name' => 'Dawning of a New Day',
				'nameplus' => 'Dawning+of+a+New+Day'
			),
			'allertastencil' => array(
				'slug' => 'allertastencil',
				'name' => 'Allerta Stencil',
				'nameplus' => 'Allerta+Stencil'
			),
			'calligraffitti' => array(
				'slug' => 'calligraffitti',
				'name' => 'Calligraffitti',
				'nameplus' => 'Calligraffitti'
			),
			'luckiestguy' => array(
				'slug' => 'luckiestguy',
				'name' => 'Luckiest Guy',
				'nameplus' => 'Luckiest+Guy'
			),
			'fontdinerswanky' => array(
				'slug' => 'fontdinerswanky',
				'name' => 'Fontdiner Swanky',
				'nameplus' => 'Fontdiner+Swanky'
			),
			'bigshotone' => array(
				'slug' => 'bigshotone',
				'name' => 'Bigshot One',
				'nameplus' => 'Bigshot+One'
			),
	);
	return $webfonts;
}

function wpnj_distinction_get_settings_page_tabs() {
	
	$tabs = array( 
        'general' => 'General',
        'layout' => 'Layout',
		'style' => 'Style'
    );
	return $tabs;
}

/* Setup initial Theme options
*******************************************************************************************/

function wpnj_distinction_options_init() {

	// set options equal to defaults
	global $wpnj_distinction_options;
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' );
	
	if ( false === $wpnj_distinction_options ) {
		$wpnj_distinction_options = wpnj_distinction_get_default_options();
	}
	update_option( 'theme_wpnj_distinction_options', $wpnj_distinction_options );
	
	// Update New Options (Version 1.2)
	if ( '1.2' > $wpnj_distinction_options['theme_version'] ) {
		$default_options = wpnj_distinction_get_default_options();
		$wpnj_distinction_options['sidebar_position'] = $default_options['sidebar_position'];
		$wpnj_distinction_options['webfonts'] = $default_options['architects_daughter'];
		$wpnj_distinction_options['theme_version'] = '1.2';
		update_option( 'theme_wpnj_distinction_options', $wpnj_distinction_options );
	}
	
}
// Initialize Theme options
add_action('after_setup_theme', 'wpnj_distinction_options_init', 9 );


/* Setup the Theme Admin Settings Page
*******************************************************************************************/

// Add "Distinction Options" link to the "Appearance" menu
function wpnj_distinction_menu_options() {
	add_theme_page('Distinction Options', 'Distinction Options', 'edit_theme_options', 'wpnj_distinction-settings', 'wpnj_distinction_admin_options_page');
}
// Load the Admin Options page
add_action('admin_menu', 'wpnj_distinction_menu_options');


// Define Settings Page Tabs
function wpnj_distinction_admin_options_page_tabs( $current = 'general' ) {

    if ( isset ( $_GET['tab'] ) ) :
        $current = $_GET['tab'];
    else:
        $current = 'general';
    endif;
    
    $tabs = wpnj_distinction_get_settings_page_tabs();
    
    $links = array();
    
    foreach( $tabs as $tab => $name ) :
        if ( $tab == $current ) :
            $links[] = "<a class='nav-tab nav-tab-active' href='?page=wpnj_distinction-settings&tab=$tab'>$name</a>";
        else :
            $links[] = "<a class='nav-tab' href='?page=wpnj_distinction-settings&tab=$tab'>$name</a>";
        endif;
    endforeach;
    
    echo '<div id="icon-themes" class="icon32"><br /></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ( $links as $link )
        echo $link;
    echo '</h2>';
    
}

// Admin settings page markup 
function wpnj_distinction_admin_options_page() { ?>

	<div class="wrap">
		<?php wpnj_distinction_admin_options_page_tabs(); ?>
		<?php if ( isset( $_GET['settings-updated'] ) ) {
    			echo "<div class='updated'><p>Theme settings updated successfully.</p></div>";
		} ?>
		<form action="options.php" method="post">
		<?php 
			settings_fields('theme_wpnj_distinction_options');
			do_settings_sections('wpnj_distinction');
			
			$tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'general' );
		?>
			<input name="theme_wpnj_distinction_options[submit-<?php echo $tab; ?>]" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'wpnj_distinction'); ?>" />
			<input name="theme_wpnj_distinction_options[reset-<?php echo $tab; ?>]" type="submit" class="button-secondary" value="<?php esc_attr_e('Reset Defaults', 'wpnj_distinction'); ?>" />
		</form>
	</div>
<?php }

// Admin settings page Form Fields markup
// 
// Codex Reference: http://codex.wordpress.org/Settings_API
// Reference: http://ottopress.com/2009/wordpress-settings-api-tutorial/
// Reference: http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
function wpnj_distinction_register_options(){
	require( get_template_directory() . '/functions/options-register.php' );
}
// Settings API options initilization and validation
add_action('admin_init', 'wpnj_distinction_register_options');


/* Enqueue Custom Admin Page Stylesheet
*******************************************************************************************/

function wpnj_distinction_enqueue_admin_style() {

	// define admin stylesheet
	$admin_handle = 'wpnj_distinction_admin_stylesheet';
	$admin_stylesheet = get_template_directory_uri() . '/functions/wpnj_distinction-admin.css';
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' );
	$webfonts = wpnj_distinction_get_valid_webfonts();
	if ($webfonts) {
		foreach ( $webfonts as $fonts ) {
			wp_enqueue_style( $fonts['slug'], 'http://fonts.googleapis.com/css?family='.$fonts['nameplus'].'', '', false );
		}
	}
	wp_enqueue_style( $admin_handle, $admin_stylesheet, '', false );
}
// Enqueue Admin Stylesheet at admin_print_styles()
add_action('admin_print_styles-appearance_page_wpnj_distinction-settings', 'wpnj_distinction_enqueue_admin_style', 1 );
add_action('admin_print_styles-appearance_page_wpnj_distinction-reference', 'wpnj_distinction_enqueue_admin_style', 11 );

function wpnj_distinction_enqueue_admin_font_family() {

	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' );
	$webfonts = wpnj_distinction_get_valid_webfonts();
	if ($webfonts) {
		echo '<style type="text/css">';
		foreach ( $webfonts as $fonts ) {
			echo '.appearance_page_wpnj_distinction-settings .'.$fonts['slug'].'{font-family:"'.$fonts['name'].'", arial, serif;}';
		}
		echo '</style>';
	}
}
// Enqueue Admin Stylesheet at admin_print_styles()
add_action('admin_print_styles-appearance_page_wpnj_distinction-settings', 'wpnj_distinction_enqueue_admin_font_family', 99 );


/* Setup the Theme Admin Settings Page Contextual help
*******************************************************************************************/

// Admin settings page contextual help markup
// Separate file for ease of management
function wpnj_distinction_get_contextual_help_text() {
	$tabtext = '';
	require( get_template_directory() . '/functions/options-help.php' );
	return $tabtext;
}

function wpnj_distinction_contextual_help() {
	$wpnj_distinction_contextual_help_text = wpnj_distinction_get_contextual_help_text();
	add_contextual_help( 'appearance_page_wpnj_distinction-settings', $wpnj_distinction_contextual_help_text  );
}
// Add contextual help to Admin Options page
add_action('admin_init', 'wpnj_distinction_contextual_help', 10, 3);


?>