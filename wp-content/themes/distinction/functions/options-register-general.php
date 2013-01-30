<?php 
/* Add Theme Settings Form Sections
*******************************************************************************************/
	
// Add a form section for the Header settings
add_settings_section('wpnj_distinction_settings_general_thanks', 'Thank you for choosing Distinction.', 'wpnj_distinction_settings_general_thanks_section_text', 'wpnj_distinction');
// Add a form section for the Google Analytics settings
add_settings_section('wpnj_distinction_settings_general_analytics', 'Google Analytics', 'wpnj_distinction_settings_general_analytics_section_text', 'wpnj_distinction');
// Add a form section for the Credits settings
add_settings_section('wpnj_distinction_settings_general_footer', 'Theme Credit', 'wpnj_distinction_settings_general_footer_section_text', 'wpnj_distinction');



/* Add Form Fields to Google Analytics Settings Section
*******************************************************************************************/	

// Add Footer Credit Link setting to the Footer section
add_settings_field('wpnj_distinction_setting_analytics_code', 'Google Analytics Code:', 'wpnj_distinction_setting_analytics_code', 'wpnj_distinction', 'wpnj_distinction_settings_general_analytics');

/* Add Form Fields to Footer Settings Section
*******************************************************************************************/	

// Add Footer Credit Link setting to the Footer section
add_settings_field('wpnj_distinction_setting_display_footer_credit', 'Footer Credit Link:', 'wpnj_distinction_setting_display_footer_credit', 'wpnj_distinction', 'wpnj_distinction_settings_general_footer');

/* Add Section Text for Each Form Section
*******************************************************************************************/

// Header Settings Section
function wpnj_distinction_settings_general_thanks_section_text() { ?>
	<p><?php _e( 'To get the most out of this theme make sure you are using the most recent version of WordPress and experiment with adding a post format when creating new posts.', 'wpnj_distinction' ); ?></p>
	<p><?php _e( 'For additional ideas on how to best utilize Distinction for your website check out <a href="http://themes.wpninjas.net/distinction/">http://themes.wpninjas.net/distinction/</a> or refer to the contextual help screen for descriptions and help regarding each theme option.', 'wpnj_distinction' ); ?></p>
	<p><?php _e( 'If you ever have any issues with this theme you can always submit a support ticket at <a href="http://wordpress.org/tags/distinction">http://wordpress.org/tags/distinction</a> and someone will try to assist you right away.', 'wpnj_distinction' ); ?></p>
	<p><?php _e( 'We hope you enjoy the theme and may it serve you well for a very long time.', 'wpnj_distinction' ); ?></p>
<?php }

// Social Network Profile Settings Section
function wpnj_distinction_settings_general_analytics_section_text() { ?>
	<p><?php _e( 'Add Google Analytics Code to the Distinction Theme. Refer to the contextual help screen for descriptions and help regarding each theme option.', 'wpnj_distinction' ); ?></p>
<?php }

// Footer Settings Section
function wpnj_distinction_settings_general_footer_section_text() { ?>
	<p><?php _e( 'Manage Footer options for the Distinction Theme. Refer to the contextual help screen for descriptions and help regarding each theme option.', 'wpnj_distinction' ); ?></p>
<?php }

/* Add Form Field Markup for Each Theme Option
*******************************************************************************************/
// Display Google Analytics Setting
function wpnj_distinction_setting_analytics_code() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' ); ?>
	<textarea cols="40" rows="7" name="theme_wpnj_distinction_options[analytics_code]"><?php echo $wpnj_distinction_options['analytics_code']; ?></textarea>
	<br /><span class="description">Add Google Analytics code to you site.</span>
<?php }

// Display Footer Credit Setting
function wpnj_distinction_setting_display_footer_credit() {
	$wpnj_distinction_options = get_option( 'theme_wpnj_distinction_options' ); ?>
	<select name="theme_wpnj_distinction_options[display_footer_credit]">
		<option <?php selected( true == $wpnj_distinction_options['display_footer_credit'] ); ?> value="true">Display</option>
        <option <?php selected( false == $wpnj_distinction_options['display_footer_credit'] ); ?> value="false">Do Not Display</option>
	</select>
	<span class="description">Display a credit link in the footer? This option is enabled by default, but you are under no obligation whatsoever to leave it enabled. However, if you like the theme, it won't hurt my feelings if you leave it posted. :)</span>
    <br /><br />
<?php }
?>