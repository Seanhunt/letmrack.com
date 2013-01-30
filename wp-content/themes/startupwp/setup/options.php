<?php
function startup_get_option_defaults() {
$defaults = array(
'social' => '',
'description' => '',
'search' => '',
'crumbs' => '',
'slider' => '',
'twitter' => '',
'share' => '',
'customstyles' => '',
'logo' => '',
'facebookurl' => '',
'twitterurl' => '',
'linkedinurl' => '',
'googleurl' => '',
'gfont1' => '',
'twitname' => '',
'pfont' => '',
'plfont' => '',
'hfont' => '',
'psize' => '',
'plsize' => '',
'navtrans' => '',
'navbg' => '',
'textcolor' => '',
'linkcolor' => '',
'hovercolor' => '',
'pcolor' => '',
'plcolor' => '',
'hcolor' => '',
'hlcolor' => '',
);
return $defaults;
}
add_action( 'admin_init', 'startup_options_init' );
function startup_options_init(){
register_setting( 'startup_options', 'startup_options', 'startup_options_validate' );
}
add_action( 'admin_menu', 'startup_options_add_page' );
function startup_options_add_page() {
global $startup_theme_page;
$startup_theme_page = add_theme_page( __( 'Startup Options', 'startup' ), __( 'Startup Options', 'startup' ), 'edit_theme_options', 'theme_options', 'startup_options_do_page' );
add_action( 'admin_print_scripts-' . $startup_theme_page, 'startup_enqueue_admin_scripts' );
}
function startup_options_do_page() {
global $select_options;
$options = wp_parse_args( get_option( 'startup_options', array() ), startup_get_option_defaults() );
if ( ! isset( $_REQUEST['settings-updated'] ) )
$_REQUEST['settings-updated'] = false;
?>
<div class="wrap">
<?php global $startup_theme_page; ?>
<?php $current_theme = wp_get_theme(); ?>
<?php screen_icon(); echo "<h2>" . sprintf( __( 'Startup Options', 'startup' )) . "</h2>"; ?>
<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
<div class="updated fade"><p><strong><?php _e( 'Options Saved', 'startup' ); ?></strong></p></div>
<?php endif; ?>
<p><?php printf( __( 'Looking for even more features and advanced editing capabilities? %1$sUpgrade to StartupPro%2$s', 'startup' ), '<a href="https://startupwp.com/" target="_blank" class="button-primary">', '</a>'); ?></p>
<p><?php printf( __( 'Thank you for choosing Startup and WordPress as the solution for building your website. If you need help please %1$svisit the support forum%2$s.', 'startup' ), '<a href="https://startupwp.com/forum/" target="_blank">', '</a>'); ?></p>
<p><?php printf( __( 'Manage previously uploaded images under the %1$sMedia%2$s tab.', 'startup' ), '<a href="'.admin_url().'upload.php" target="_blank">', '</a>' ); ?></p>
<form method="post" action="options.php">
<p class="submit">
<input type="reset" value="<?php _e( 'Undo', 'startup' ); ?>" style="margin-left:10px;float:right" />
<input type="button" value="<?php _e( 'Clear All Settings', 'startup' ); ?>" style="float:right" onclick="clearForm(this.form)" />
<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'startup' ); ?>" />
</p>
<p style="text-align:right"><?php printf( __( 'After clicking either %1$sClear All Settings%2$s or %1$sUndo%2$s you must then click %1$sSave Options%2$s to confirm.', 'startup' ), '<em>', '</em>' ); ?><br /><?php printf( __( '%1$sUndo%2$s only restores your settings to the previous saved state before saving again after clearing all settings.', 'startup' ), '<em>', '</em>' ); ?></p>
<?php settings_fields( 'startup_options' ); ?>
<table class="form-table">
<?php
?>
<tr valign="top"><th scope="row"><?php _e( 'Turn On/Off Features', 'startup' ); ?><br /><em>(<?php _e( 'check to turn on', 'startup' ); ?>)</em></th>
<td>
<input id="startup_options[social]" name="startup_options[social]" type="checkbox" value="1" <?php checked( '1', $options['social'] ); ?> />
<label class="description" for="startup_options[social]"><?php _e( 'Social Profile Icons', 'startup' ); ?> <em>(<?php _e( 'set further settings below in social profile settings', 'startup' ); ?>)</em></label>
<br />
<input id="startup_options[description]" name="startup_options[description]" type="checkbox" value="1" <?php checked( '1', $options['description'] ); ?> />
<label class="description" for="startup_options[description]"><?php _e( 'Site Description', 'startup' ); ?> <em>(<?php _e( 'set under settings > general from the main wp admin', 'startup' ); ?>)</em></label>
<br />
<input id="startup_options[search]" name="startup_options[search]" type="checkbox" value="1" <?php checked( '1', $options['search'] ); ?> />
<label class="description" for="startup_options[search]"><?php _e( 'Menu Search Field', 'startup' ); ?> <em>(<?php _e( 'search box appears on right side of menu - allows users to search the contents of your site', 'startup' ); ?>)</em></label>
<br />
<input id="startup_options[crumbs]" name="startup_options[crumbs]" type="checkbox" value="1" <?php checked( '1', $options['crumbs'] ); ?> />
<label class="description" for="startup_options[crumbs]"><?php _e( 'Breadcrumbs', 'startup' ); ?> <em>(<?php _e( 'shows your visitors where they are in relation to the homepage', 'startup' ); ?>)</em></label>
<br />
<input id="startup_options[slider]" name="startup_options[slider]" type="checkbox" value="1" <?php checked( '1', $options['slider'] ); ?> />
<label class="description" for="startup_options[slider]"><?php _e( 'Universal Slider', 'startup' ); ?> <em>(<?php printf(__( 'important: you must create a slider first after installing %1$sNivo Slider for WordPress%2$s - for best results set slider to and prepare images at 1000px wide [or whatever you have set your max display width to] and 200px or more high - all slides need to be consistent in size', 'startup' ), '<a href="plugin-install.php?tab=search&s=Nivo+Slider+for+WordPress" target="_blank">', '</a>' ); ?>)</em></label>
<br />
<input id="startup_options[twitter]" name="startup_options[twitter]" type="checkbox" value="1" <?php checked( '1', $options['twitter'] ); ?> />
<label class="description" for="startup_options[twitter]"><?php _e( 'Twitter Feed', 'startup' ); ?> <em>(<?php _e( 'set further settings below in social profile settings', 'startup' ); ?>)</em></label>
<br />
<input id="startup_options[share]" name="startup_options[share]" type="checkbox" value="1" <?php checked( '1', $options['share'] ); ?> />
<label class="description" for="startup_options[share]"><?php _e( 'Single Post Sharing', 'startup' ); ?> <em>(<?php _e( 'sets like, tweet and +1 buttons on single posts view', 'startup' ); ?>)</em></label>
<br />
<input id="startup_options[customstyles]" name="startup_options[customstyles]" type="checkbox" value="1" <?php checked( '1', $options['customstyles'] ); ?> />
<label class="description" for="startup_options[customstyles]"><?php _e( 'Custom Styles', 'startup' ); ?> <em>(<?php _e( 'set further settings below', 'startup' ); ?>)</em></label>
</td>
</tr>
<?php
?>
<tr valign="top"><th scope="row"><?php _e( 'Image URLs', 'startup' ); ?><br /><em>(<?php _e( 'enter full image urls or click the "Select/Upload Image" button to select/upload an image from the Media Manager and then click "Insert into Post"', 'startup' ); ?>)</em></th>
<td>
<p class="startup-options-logo">
<label class="description" for="startup_options[logo]"><?php _e( 'Logo Image URL', 'startup' ); ?></label><br />
<input id="startup_options[logo]" class="logo-upload-url" type="text" name="startup_options[logo]" value="<?php echo esc_attr( $options['logo'] ); ?>" />
<input id="startup_options_logo_button" type="button" value="Select/Upload Image" />
</p>
</td>
</tr>
<?php
?>
<tr valign="top"><th scope="row"><?php _e( 'Social Profile Settings', 'startup' ); ?><br /><em>(<?php _e( 'enter full urls', 'startup' ); ?>)</em></th>
<td>
<input id="startup_options[facebookurl]" class="regular-text" type="text" name="startup_options[facebookurl]" value="<?php echo esc_attr( $options['facebookurl'] ); ?>" />
<label class="description" for="startup_options[facebookurl]"><?php _e( 'Facebook Profile URL', 'startup' ); ?> <em>(<?php printf( __( 'example: %s', 'startup' ), '<strong>http://www.facebook.com/yourusername</strong>' ); ?>)</em></label>
<br />
<input id="startup_options[twitterurl]" class="regular-text" type="text" name="startup_options[twitterurl]" value="<?php echo esc_attr( $options['twitterurl'] ); ?>" />
<label class="description" for="startup_options[twitterurl]"><?php _e( 'Twitter Profile URL', 'startup' ); ?> <em>(<?php printf( __( 'example: %s', 'startup' ), '<strong>http://twitter.com/yourusername</strong>' ); ?>)</em></label>
<br />
<input id="startup_options[linkedinurl]" class="regular-text" type="text" name="startup_options[linkedinurl]" value="<?php echo esc_attr( $options['linkedinurl'] ); ?>" />
<label class="description" for="startup_options[linkedinurl]"><?php _e( 'LinkedIn Profile URL', 'startup' ); ?> <em>(<?php printf( __( 'example: %s', 'startup' ), '<strong>http://www.linkedin.com/in/yourusername</strong>' ); ?>)</em></label>
<br />
<input id="startup_options[googleurl]" class="regular-text" type="text" name="startup_options[googleurl]" value="<?php echo esc_attr( $options['googleurl'] ); ?>" />
<label class="description" for="startup_options[googleurl]"><?php _e( 'Google+ Profile URL', 'startup' ); ?> <em>(<?php printf( __( 'example: %s', 'startup' ), '<strong>https://plus.google.com/yourusernumber</strong>' ); ?>)</em></label>
<br />
<input id="startup_options[twitname]" class="regular-text" type="text" name="startup_options[twitname]" value="<?php echo esc_attr( $options['twitname'] ); ?>" />
<label class="description" for="startup_options[twitname]"><?php _e( 'Twitter Profile Username', 'startup' ); ?> <em>(<?php _e( 'username only - no @ needed', 'startup' ); ?>)</em></label>
</td>
</tr>
<?php
?>
<tr valign="top"><th scope="row"><?php _e( 'Google Fonts', 'startup' ); ?><br /><em>(<?php printf( __( 'enter one google font from a %1$sselection of over five hundred%2$s, use proper capitalization and %3$s for spaces, example: %4$s.', 'startup' ), '<a href="//www.google.com/webfonts" target="_blank">', '</a>', '<strong>+</strong>', '<strong>Open+Sans</strong>' ); printf( __( 'Then use in either the headers font style as %1$s or custom css area below, example: %2$s', 'startup' ), '<strong>&#39;Open Sans&#39;</strong>', '<code>h2{font-family:&#39;Open Sans&#39;}</code>' ); ?>)</em></th>
<td>
<input id="startup_options[gfont1]" class="regular-text" type="text" name="startup_options[gfont1]" value="<?php echo esc_attr( $options['gfont1'] ); ?>" />
<label class="description" for="startup_options[gfont1]"><?php _e( 'Google Font One', 'startup' ); ?></label>
</td>
</tr>
<?php
?>
<tr valign="top"><th scope="row"><?php _e( 'Custom Styles', 'startup' ); ?><br /><em>(<?php printf( __( 'no %1$s for colors or %2$s or %3$s for sizes', 'startup' ), '<strong>#</strong>', '<strong>px</strong>', '<strong>%</strong>' ); ?>)</em></th>
<td>
<input id="startup_options[navbg]" class="regular-text color {required:false}" type="text" name="startup_options[navbg]" value="<?php echo esc_attr( $options['navbg'] ); ?>" />
<label class="description" for="startup_options[navbg]"><?php _e( 'Navigation Menu Background Color', 'startup' ); ?></label>
<br />
<input id="startup_options[navtrans]" class="regular-text" type="text" name="startup_options[navtrans]" value="<?php echo esc_attr( $options['navtrans'] ); ?>" />
<label class="description" for="startup_options[navtrans]"><?php _e( 'Navigation Menu Dropdown Opacity Level', 'startup' ); ?> <em>(<?php printf( __( 'default is %1$s - %2$s is fully transparent and %3$s is fully solid', 'startup' ), '<strong>0.95</strong>', '<strong>0</strong>', '<strong>1</strong>' ); ?>)</em></label>
<br />
<input id="startup_options[textcolor]" class="regular-text color {required:false}" type="text" name="startup_options[textcolor]" value="<?php echo esc_attr( $options['textcolor'] ); ?>" />
<label class="description" for="startup_options[textcolor]"><?php _e( 'Body Text Color', 'startup' ); ?></label>
<br />
<input id="startup_options[linkcolor]" class="regular-text color {required:false}" type="text" name="startup_options[linkcolor]" value="<?php echo esc_attr( $options['linkcolor'] ); ?>" />
<label class="description" for="startup_options[linkcolor]"><?php _e( 'Link Color', 'startup' ); ?> <em>(<?php printf( __( 'will not universally change %1$s all %2$s link colors - use custom css for in-depth style management', 'startup' ), '<strong>', '</strong>' ); ?>)</em></label>
<br />
<input id="startup_options[hovercolor]" class="regular-text color {required:false}" type="text" name="startup_options[hovercolor]" value="<?php echo esc_attr( $options['hovercolor'] ); ?>" />
<label class="description" for="startup_options[hovercolor]"><?php _e( 'Link Hover Color', 'startup' ); ?></label>
<br />
<input id="startup_options[pfont]" class="regular-text" type="text" name="startup_options[pfont]" value="<?php echo esc_attr( $options['pfont'] ); ?>" />
<label class="description" for="startup_options[pfont]"><?php _e( 'Page/Post Paragraph Font', 'startup' ); ?></label>
<br />
<input id="startup_options[psize]" class="regular-text" type="text" name="startup_options[psize]" value="<?php echo esc_attr( $options['psize'] ); ?>" />
<label class="description" for="startup_options[psize]"><?php _e( 'Page/Post Paragraph Font Size', 'startup' ); ?></label>
<br />
<input id="startup_options[pcolor]" class="regular-text color {required:false}" type="text" name="startup_options[pcolor]" value="<?php echo esc_attr( $options['pcolor'] ); ?>" />
<label class="description" for="startup_options[pcolor]"><?php _e( 'Page/Post Paragraph Font Color', 'startup' ); ?></label>
<br />
<input id="startup_options[plfont]" class="regular-text" type="text" name="startup_options[plfont]" value="<?php echo esc_attr( $options['plfont'] ); ?>" />
<label class="description" for="startup_options[plfont]"><?php _e( 'Page/Post Paragraph Link Font', 'startup' ); ?></label>
<br />
<input id="startup_options[plsize]" class="regular-text" type="text" name="startup_options[plsize]" value="<?php echo esc_attr( $options['plsize'] ); ?>" />
<label class="description" for="startup_options[plsize]"><?php _e( 'Page/Post Paragraph Link Font Size', 'startup' ); ?></label>
<br />
<input id="startup_options[plcolor]" class="regular-text color {required:false}" type="text" name="startup_options[plcolor]" value="<?php echo esc_attr( $options['plcolor'] ); ?>" />
<label class="description" for="startup_options[plcolor]"><?php _e( 'Page/Post Paragraph Link Font Color', 'startup' ); ?></label>
<br />
<input id="startup_options[hfont]" class="regular-text" type="text" name="startup_options[hfont]" value="<?php echo esc_attr( $options['hfont'] ); ?>" />
<label class="description" for="startup_options[hfont]"><?php _e( 'Headers Font', 'startup' ); ?></label>
<br />
<input id="startup_options[hcolor]" class="regular-text color {required:false}" type="text" name="startup_options[hcolor]" value="<?php echo esc_attr( $options['hcolor'] ); ?>" />
<label class="description" for="startup_options[hcolor]"><?php _e( 'Headers Color', 'startup' ); ?></label>
<br />
<input id="startup_options[hlcolor]" class="regular-text color {required:false}" type="text" name="startup_options[hlcolor]" value="<?php echo esc_attr( $options['hlcolor'] ); ?>" />
<label class="description" for="startup_options[hlcolor]"><?php _e( 'Headers Link Color', 'startup' ); ?></label>
</td>
</tr>
</table>
<p class="submit">
<input type="reset" value="<?php _e( 'Undo', 'startup' ); ?>" style="margin-left:10px;float:right" />
<input type="button" value="<?php _e( 'Clear All Settings', 'startup' ); ?>" style="float:right" onclick="clearForm(this.form)" />
<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'startup' ); ?>" />
</p>
<p style="text-align:right"><?php printf( __( 'After clicking either %1$sClear All Settings%2$s or %1$sUndo%2$s you must then click %1$sSave Options%2$s to confirm.', 'startup' ), '<em>', '</em>' ); ?><br /><?php printf( __( '%1$sUndo%2$s only restores your settings to the previous saved state before saving again after clearing all settings.', 'startup' ), '<em>', '</em>' ); ?></p>
</form>
</div>
<?php
?>
<?php
}
function startup_options_validate( $input ) {
$valid_input = wp_parse_args( get_option( 'startup_options', array() ), startup_get_option_defaults() );
$valid_input['social'] = ( isset( $input['social'] ) && true == $input['social'] ? true : false );
$valid_input['description'] = ( isset( $input['description'] ) && true == $input['description'] ? true : false );
$valid_input['search'] = ( isset( $input['search'] ) && true == $input['search'] ? true : false );
$valid_input['crumbs'] = ( isset( $input['crumbs'] ) && true == $input['crumbs'] ? true : false );
$valid_input['slider'] = ( isset( $input['slider'] ) && true == $input['slider'] ? true : false );
$valid_input['twitter'] = ( isset( $input['twitter'] ) && true == $input['twitter'] ? true : false );
$valid_input['share'] = ( isset( $input['share'] ) && true == $input['share'] ? true : false );
$valid_input['customstyles'] = ( isset( $input['customstyles'] ) && true == $input['customstyles'] ? true : false );
$valid_input['logo'] = ( isset( $input['logo'] ) ? esc_url_raw( $input['logo'] ) : $valid_input['logo'] );
$valid_input['facebookurl'] = ( isset( $input['facebookurl'] ) ? esc_url_raw( $input['facebookurl'] ) : $valid_input['facebookurl'] );
$valid_input['twitterurl'] = ( isset( $input['twitterurl'] ) ? esc_url_raw( $input['twitterurl'] ) : $valid_input['twitterurl'] );
$valid_input['linkedinurl'] = ( isset( $input['linkedinurl'] ) ? esc_url_raw( $input['linkedinurl'] ) : $valid_input['linkedinurl'] );
$valid_input['googleurl'] = ( isset( $input['googleurl'] ) ? esc_url_raw( $input['googleurl'] ) : $valid_input['googleurl'] );
$valid_input['gfont1'] = ( isset( $input['gfont1'] ) ? wp_filter_nohtml_kses( $input['gfont1'] ) : $valid_input['gfont1'] );
$valid_input['twitname'] = ( isset( $input['twitname'] ) ? wp_filter_nohtml_kses( $input['twitname'] ) : $valid_input['twitname'] );
$valid_input['pfont'] = ( isset( $input['pfont'] ) ? wp_filter_nohtml_kses( $input['pfont'] ) : $valid_input['pfont'] );
$valid_input['plfont'] = ( isset( $input['plfont'] ) ? wp_filter_nohtml_kses( $input['plfont'] ) : $valid_input['plfont'] );
$valid_input['hfont'] = ( isset( $input['hfont'] ) ? wp_filter_nohtml_kses( $input['hfont'] ) : $valid_input['hfont'] );
$valid_input['psize'] = ( isset( $input['psize'] ) && is_int( intval( $input['psize'] ) ) ? $input['psize'] : $valid_input['psize'] );
$valid_input['plsize'] = ( isset( $input['plsize'] ) && is_int( intval( $input['plsize'] ) ) ? $input['plsize'] : $valid_input['plsize'] );
if ( ! isset( $input['navtrans'] ) || '' == $input['navtrans'] ) {
$valid_input['navtrans'] = '';
} else {
$valid_input['navtrans'] = ( isset( $input['navtrans'] ) && is_numeric( $input['navtrans'] ) && 0 <= $input['navtrans'] && 1 >= $input['navtrans'] ? round( $input['navtrans'], 2 ) : $valid_input['navtrans'] );
}
if ( ! isset( $input['navbg'] ) || '' == $input['navbg'] ) {
$valid_input['navbg'] = '';
} else {
$input['navbg'] = ltrim( trim( $input['navbg' ] ), '#' );
$input['navbg'] = ( 6 == strlen( $input['navbg'] ) || 3 == strlen( $input['navbg'] ) ? $input['navbg'] : false );
$valid_input['navbg'] = ( ctype_xdigit( $input['navbg'] ) ? $input['navbg'] : $valid_input['navbg'] );
}
if ( ! isset( $input['textcolor'] ) || '' == $input['textcolor'] ) {
$valid_input['textcolor'] = '';
} else {
$input['textcolor'] = ltrim( trim( $input['textcolor' ] ), '#' );
$input['textcolor'] = ( 6 == strlen( $input['textcolor'] ) || 3 == strlen( $input['textcolor'] ) ? $input['textcolor'] : false );
$valid_input['textcolor'] = ( ctype_xdigit( $input['textcolor'] ) ? $input['textcolor'] : $valid_input['textcolor'] );
}
if ( ! isset( $input['linkcolor'] ) || '' == $input['linkcolor'] ) {
$valid_input['linkcolor'] = '';
} else {
$$input['linkcolor'] = ltrim( trim( $input['linkcolor' ] ), '#' );
$input['linkcolor'] = ( 6 == strlen( $input['linkcolor'] ) || 3 == strlen( $input['linkcolor'] ) ? $input['linkcolor'] : false );
$valid_input['linkcolor'] = ( ctype_xdigit( $input['linkcolor'] ) ? $input['linkcolor'] : $valid_input['linkcolor'] );
}
if ( ! isset( $input['hovercolor'] ) || '' == $input['hovercolor'] ) {
$valid_input['hovercolor'] = '';
} else {
$input['hovercolor'] = ltrim( trim( $input['hovercolor' ] ), '#' );
$input['hovercolor'] = ( 6 == strlen( $input['hovercolor'] ) || 3 == strlen( $input['hovercolor'] ) ? $input['hovercolor'] : false );
$valid_input['hovercolor'] = ( ctype_xdigit( $input['hovercolor'] ) ? $input['hovercolor'] : $valid_input['hovercolor'] );
}
if ( ! isset( $input['pcolor'] ) || '' == $input['pcolor'] ) {
$valid_input['pcolor'] = '';
} else {
$input['pcolor'] = ltrim( trim( $input['pcolor' ] ), '#' );
$input['pcolor'] = ( 6 == strlen( $input['pcolor'] ) || 3 == strlen( $input['pcolor'] ) ? $input['pcolor'] : false );
$valid_input['pcolor'] = ( ctype_xdigit( $input['pcolor'] ) ? $input['pcolor'] : $valid_input['pcolor'] );
}
if ( ! isset( $input['plcolor'] ) || '' == $input['plcolor'] ) {
$valid_input['plcolor'] = '';
} else {
$input['plcolor'] =ltrim( trim( $input['plcolor' ] ), '#' );
$input['plcolor'] = ( 6 == strlen( $input['plcolor'] ) || 3 == strlen( $input['plcolor'] ) ? $input['plcolor'] : false );
$valid_input['plcolor'] = ( ctype_xdigit( $input['plcolor'] ) ? $input['plcolor'] : $valid_input['plcolor'] );
}
if ( ! isset( $input['hcolor'] ) || '' == $input['hcolor'] ) {
$valid_input['hcolor'] = '';
} else {
$input['hcolor'] = ltrim( trim( $input['hcolor' ] ), '#' );
$input['hcolor'] = ( 6 == strlen( $input['hcolor'] ) || 3 == strlen( $input['hcolor'] ) ? $input['hcolor'] : false );
$valid_input['hcolor'] = ( ctype_xdigit( $input['hcolor'] ) ? $input['hcolor'] : $valid_input['hcolor'] );
}
if ( ! isset( $input['hlcolor'] ) || '' == $input['hlcolor'] ) {
$valid_input['hlcolor'] = '';
} else {
$input['hlcolor'] = ltrim( trim( $input['hlcolor' ] ), '#' );
$input['hlcolor'] = ( 6 == strlen( $input['hlcolor'] ) ? $input['hlcolor'] : $valid_input['hlcolor'] );
$valid_input['hlcolor'] = ( ctype_xdigit( $input['hlcolor'] ) ? $input['hlcolor'] : $valid_input['hlcolor'] );
}
return $valid_input;
}