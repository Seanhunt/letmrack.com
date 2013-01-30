<?php 
/* Add Theme Settings Form Sections
*******************************************************************************************/
add_settings_section('wpnj_distinction_settings_header_graphics', 'Header Graphics', 'wpnj_distinction_settings_header_graphics_section_text', 'wpnj_distinction');
add_settings_section('wpnj_distinction_settings_site_fonts', 'Custom Fonts', 'wpnj_distinction_settings_site_fonts_section_text', 'wpnj_distinction');
	
/* Add Form Fields to Header Graphics Section
*******************************************************************************************/	
add_settings_field('wpnj_distinction_setting_display_header_flames', 'Header Graphics:', 'wpnj_distinction_setting_display_header_flames', 'wpnj_distinction', 'wpnj_distinction_settings_header_graphics');

/* Add Form Fields to Custom Fonts Section
*******************************************************************************************/	
add_settings_field('wpnj_distinction_setting_webfonts', 'Webfonts:', 'wpnj_distinction_setting_webfonts', 'wpnj_distinction', 'wpnj_distinction_settings_site_fonts');


/*****************************************************************************************
* Add Section Text for Each Form Section
*******************************************************************************************/

// Header Settings Section
function wpnj_distinction_settings_header_graphics_section_text() { ?>
	<?php _e( '', 'wpnj_distinction' ); ?>
<?php }

function wpnj_distinction_settings_site_fonts_section_text() { ?>
	<p><?php _e( 'Select the perfect font for your Distinction Theme. This will change the font for all the headers within your site. Refer to the contextual help screen for descriptions and help regarding each theme option.', 'wpnj_distinction' ); ?></p>
<?php }

/*****************************************************************************************
* Add Form Field Markup for Each Theme Option
*******************************************************************************************/

// Display Header Graphics Setting
function wpnj_distinction_setting_display_header_flames() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' ); ?>
	<select name="theme_wpnj_distinction_options[display_header_flames]">
		<option <?php selected( true == $wpnj_distinction_options['display_header_flames'] ); ?> value="true">Display</option>
        <option <?php selected( false == $wpnj_distinction_options['display_header_flames'] ); ?> value="false">Do Not Display</option>
	</select>
	<span class="description">Display flame images in the header? This option is enabled by default.</span>
<?php }

function wpnj_distinction_setting_webfonts() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' );
    $wpni_distinction_webfonts = wpnj_distinction_get_valid_webfonts(); ?>
    <?php foreach ( $wpni_distinction_webfonts as $webfonts ) { ?>
            <div class="font-container"><input class="<?php echo $webfonts['slug']; ?>" type="radio" name="theme_wpnj_distinction_options[webfonts]" <?php checked( $webfonts['slug'] == $wpnj_distinction_options['webfonts'] ); ?> value="<?php echo $webfonts['slug']; ?>" /><span class="fonts-n-such description <?php echo $webfonts['slug']; ?>"><?php echo $webfonts['name']; ?></span></div>
	<?php } ?><br /><br />
<?php }