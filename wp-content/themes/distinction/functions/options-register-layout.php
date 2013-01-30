<?php 
/* Add Theme Settings Form Sections
*******************************************************************************************/
add_settings_section('wpnj_distinction_settings_header_options', 'Header Options', 'wpnj_distinction_settings_header_options_section_text', 'wpnj_distinction');
add_settings_section('wpnj_distinction_settings_sidebar_options', 'Sidebar Options', 'wpnj_distinction_settings_sidebar_options_section_text', 'wpnj_distinction');
	
	
/* Add Form Fields to Header Options Section
*******************************************************************************************/
add_settings_field('wpnj_distinction_setting_header_menu_position', 'Header Menu Position:', 'wpnj_distinction_setting_header_menu_position', 'wpnj_distinction', 'wpnj_distinction_settings_header_options');	
add_settings_field('wpnj_distinction_setting_header_menu_depth', 'Header Menu Depth:', 'wpnj_distinction_setting_header_menu_depth', 'wpnj_distinction', 'wpnj_distinction_settings_header_options');
add_settings_field('wpnj_distinction_setting_display_site_description', 'Site Description:', 'wpnj_distinction_setting_display_site_description', 'wpnj_distinction', 'wpnj_distinction_settings_header_options');
add_settings_field('wpnj_distinction_setting_display_menu_search_form', 'Search Form:', 'wpnj_distinction_setting_display_menu_search_form', 'wpnj_distinction', 'wpnj_distinction_settings_header_options');

/* Add Form Fields to Sidebar Options Section
*******************************************************************************************/
add_settings_field('wpnj_distinction_setting_sidebar_position', 'Header Menu Position:', 'wpnj_distinction_setting_sidebar_position', 'wpnj_distinction', 'wpnj_distinction_settings_sidebar_options');	


/*****************************************************************************************
* Add Section Text for Each Form Section
*******************************************************************************************/

// Header Settings Section
function wpnj_distinction_settings_header_options_section_text() { ?>
	<p><?php _e( 'Manage Header options for the Distinction Theme. Refer to the contextual help screen for descriptions and help regarding each theme option.', 'wpnj_distinction' ); ?></p>
<?php }

function wpnj_distinction_settings_sidebar_options_section_text() { ?>
	<p><?php _e( 'Manage Sidebar options for the Distinction Theme. Refer to the contextual help screen for descriptions and help regarding each theme option.', 'wpnj_distinction' ); ?></p>
<?php }

/*****************************************************************************************
* Add Form Field Markup for Each Theme Option
*******************************************************************************************/

// Navigation Menu Position Setting
function wpnj_distinction_setting_header_menu_position() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' ); ?>
	<select name="theme_wpnj_distinction_options[header_menu_position]">
		<option <?php selected( 'above' == $wpnj_distinction_options['header_menu_position'] ); ?> value="above">Above</option>
		<option <?php selected( 'below' == $wpnj_distinction_options['header_menu_position'] ); ?> value="below">Below</option>
	</select>
	<span class="description">Display header navigation menu above or below the site title/description?</span>
<?php }

// Navigation Menu Depth Setting
function wpnj_distinction_setting_header_menu_depth() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' ); ?>	
	<select name="theme_wpnj_distinction_options[header_menu_depth]">
		<option <?php selected( 1 == $wpnj_distinction_options['header_menu_depth'] ); ?> value="1">One</option>
		<option <?php selected( 2 == $wpnj_distinction_options['header_menu_depth'] ); ?> value="2">Two</option>
		<option <?php selected( 3 == $wpnj_distinction_options['header_menu_depth'] ); ?> value="3">Three</option>
	</select>
	<span class="description">How many levels of Page hierarchy should the Header Navigation Menu display?</span>
<?php }

// Display Site Description Setting
function wpnj_distinction_setting_display_site_description() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' ); ?>
	<select name="theme_wpnj_distinction_options[display_site_description]">
		<option <?php selected( true == $wpnj_distinction_options['display_site_description'] ); ?> value="true">Display</option>
        <option <?php selected( false == $wpnj_distinction_options['display_site_description'] ); ?> value="false">Do Not Display</option>
	</select>
	<span class="description">Display your site description in the header? This option is enabled by default.</span>
<?php }

// Display Search Form Setting
function wpnj_distinction_setting_display_menu_search_form() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' ); ?>
	<select name="theme_wpnj_distinction_options[display_menu_search_form]">
		<option <?php selected( true == $wpnj_distinction_options['display_menu_search_form'] ); ?> value="true">Display</option>
        <option <?php selected( false == $wpnj_distinction_options['display_menu_search_form'] ); ?> value="false">Do Not Display</option>
	</select>
	<span class="description">Display the search form with your main header menu? This option is enabled by default.</span>
<?php }

// Sidebar Position Setting
function wpnj_distinction_setting_sidebar_position() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' ); ?>
	<select name="theme_wpnj_distinction_options[sidebar_position]">
		<option <?php selected( 'right' == $wpnj_distinction_options['sidebar_position'] ); ?> value="right">Right Sidebar</option>
		<option <?php selected( 'left' == $wpnj_distinction_options['sidebar_position'] ); ?> value="left">Left Sidebar</option>
	</select>
	<span class="description">Display sidebar on the right or left side of the page?</span>
    <br /><br />
<?php }
?>