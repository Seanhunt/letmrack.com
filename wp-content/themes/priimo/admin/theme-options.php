<?php
/**
 * @package Priimo
 */
?>
<?php
/* THEME SETTINGS PAGE */
/***********************/
//Initialize theme options on load
function priimo_init_options() {
    if(false === priimo_get_options()) add_option('priimo_options', priimo_get_default_options());
    register_setting('priimo_options', 'priimo_options', 'priimo_validate_options');
}
add_action('admin_init', 'priimo_init_options');

//Retrieve theme options
function priimo_get_options() {
    return get_option('priimo_options', priimo_get_default_options());
}

//Return capability
function priimo_page_capability($capability) {
    return 'edit_theme_options';
}
add_filter('option_page_capability_priimo_options', 'priimo_page_capability');

//Enqueue stylesheets and dependent javascripts
function priimo_enqueue_scripts($hook_suffix) {
    wp_enqueue_script('jquery');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
    wp_enqueue_script('priimo_color_picker', get_template_directory_uri().'/admin/js/jscolor.js', false, false);
    wp_enqueue_script('priimo_admin_js', get_template_directory_uri().'/admin/js/theme-options.js', array('jquery'), false);
    wp_enqueue_style('priimo_admin_css', get_template_directory_uri().'/admin/theme-options.css', false, false, 'all');
}

//Add theme option page to the admin menu
function priimo_activate_options() {
    $priimo_theme_page = add_theme_page(__('Priimo Settings','priimo'), __('Priimo Settings','priimo'), 'edit_theme_options', 'priimo_options', 'priimo_options_page');
    if(!$priimo_theme_page) return;
    add_action('admin_print_styles-' . $priimo_theme_page, 'priimo_enqueue_scripts');
}
add_action('admin_menu', 'priimo_activate_options');

//Default dropdowns
function priimo_tab_list() {
    $tabs = array(
        'typography' => array('value' => 'typography', 'label' => __('Fonts & Colors','priimo')),
        'logo' => array('value' => 'logo', 'label' => __('Favicon','priimo')),
        'layout' => array('value' => 'layout', 'label' => __('Layout','priimo')),
        'slideshow' => array('value' => 'slideshow', 'label' => __('Slideshow','priimo')),
        'portfolio' => array('value' => 'portfolio', 'label' => __('Portfolio','priimo')),
        'blog' => array('value' => 'blog', 'label' => __('Blog','priimo')),
        'social' => array('value' => 'social', 'label' => __('Social','priimo')),
    );
    return apply_filters('priimo_tab_list', $tabs);
}

function priimo_content_fonts() {
    $fonts = array(
        '"Helvetica Nue",Arial,sans-serif' => array('value' => '"Helvetica Nue",Arial,sans-serif', 'label' => __('Helvetica, Arial','priimo')),
        'Georgia, serif' => array('value' => 'Georgia, serif', 'label' => __('Georgia','priimo')),
        'Verdana' => array('value' => 'Verdana', 'label' => __('Verdana','priimo')),
    );
    return apply_filters('priimo_content_fonts', $fonts);
}

function priimo_sidebar_layout() {
    $sidebar_layouts = array(
        'one-right-sidebar' => array('value' => 'one-right-sidebar', 'label' => __('One right sidebar','priimo')),
        'one-left-sidebar' => array('value' => 'one-left-sidebar', 'label' => __('One left sidebar','priimo'))
    );
    return apply_filters('priimo_sidebar_layout', $sidebar_layouts);
}

function priimo_blog_style() {
    $blog_styles = array(
        'standard' => array('value' => 'standard', 'label' => __('Standard','priimo')),
        'magazine' => array('value' => 'magazine', 'label' => __('Magazine','priimo'))
    );
    return apply_filters('priimo_blog_style', $blog_styles);
}

function priimo_portfolio_columns() {
    $columns = array(
        '2' => array('value' => '2', 'label' => __('Two','priimo')),
        '3' => array('value' => '3', 'label' => __('Three','priimo')),
        '4' => array('value' => '4', 'label' => __('Four','priimo')),
    );
    return apply_filters('priimo_portfolio_columns', $columns);
}

//Retrieve Default theme settings
function priimo_get_default_options() {
    $priimo_default_options = array(
        'content_font' => '"Helvetica Nue",Arial,sans-serif',
        'font_size' => 83.3,
        'font_color' => '666666',
        'link_color' => '724912',
        'button_color' => '53912b',
        'custom_favicon' => '',
        'blog_style' => 'magazine',
        'show_search' => true,
        'show_single_utility' => true,
        'show_page_meta' => false,
        'show_bookmark_buttons' => false,
        'sidebar_layout' => 'one-right-sidebar',
        'enable_slideshow' => true,
        'slideshow_count' => 5,
        'slideshow_interval' => 3,
        'portfolio_columns' => 3,
        'facebook_user' => '',
        'twitter_user' => '',
        'enable_rss' => true
    );
    return apply_filters('priimo_get_default_options', $priimo_default_options);
}

function priimo_options_page() {
    if(isset($_POST['settings-reset'])) {
        delete_option('priimo_options');
        add_settings_error('priimo_options','settings-reset-update',__('Default settings restored','priimo'),'updated');
    }
    ?>
    <div class="settings-wrap"> <!-- Start of Settings Wrap -->
        <div class="settings-header">
            <table class="tablayout" style="height:100%;"><tr>
            <td class="left" style="width:50%;">
                <?php $theme_name = function_exists('wp_get_theme') ? wp_get_theme() : get_current_theme(); ?>
                <h2 class="settings-title"><?php printf(__('%s Theme Options', 'priimo'), $theme_name); ?></h2>
            </td>
            </tr></table>
        </div>
        <div class="settings-menu clearfix">
            <?php $count = 1; ?>
            <?php foreach (priimo_tab_list() as $tab) { if($count == 1) $class = " settings-menu-tab-active"; else $class = ""; ?>
            <a href="javascript:void(0)" id="settings-menu-tab-<?php echo $tab['value']; ?>" class="settings-menu-tab<?php echo $class; ?>">
                <img class="settings-menu-tab-icon" src="<?php echo get_template_directory_uri(); ?>/admin/images/<?php echo $tab['value']; ?>_icon.png"/>
                <?php echo $tab['label']; ?>
            </a>
            <?php $count++; } ?>
        </div>
        <div class="settings-content-container">
             <?php settings_errors(); ?>
            <form class="settings-form" method="post" id="settings-form" action="options.php">
            <?php
                settings_fields('priimo_options');
                $priimo_options = priimo_get_options();
                $priimo_default_options = priimo_get_default_options();
            ?>
            <div id="settings-content-typography" class="settings-content settings-content-active">
                <p class="lblfield"><label><?php _e('Content Font','priimo'); ?></label></label></p>
                <p class="frmfield">
                    <select name="priimo_options[content_font]">
                        <?php foreach (priimo_content_fonts() as $content_font) { ?>
                            <option value="<?php echo $content_font['value']; ?>" <?php selected($priimo_options['content_font'], $content_font['value']); ?>><?php echo $content_font['label']; ?></option>
                        <?php } ?>
                    </select>
                </p>
                <p class="lblfield"><label><?php _e('Content Font Size','priimo'); ?></label></label></p>
                <p class="frmfield">
                    <input class="roundbtn" type="button" value=" - " id="font_size_decr_btn"/>
                    <input type="text" readonly="readonly" name="priimo_options[font_size]" id="font_size" value="<?php echo esc_attr($priimo_options['font_size']); ?>"/> %
                    <input class="roundbtn" type="button" value=" + " id="font_size_incr_btn"/>
                </p>
                <p class="lblfield"><label><?php _e('Font color','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="text" name="priimo_options[font_color]" class="color" value="<?php echo esc_attr($priimo_options['font_color']); ?>"/>
                </p>
                <p class="lblfield"><?php _e('Link color','priimo'); ?></p>
                <p class="frmfield">
                    <input type="text" name="priimo_options[link_color]" class="color" value="<?php echo esc_attr($priimo_options['link_color']); ?>"/>
                </p>
                <p class="lblfield"><label><?php _e('Button color','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="text" name="priimo_options[button_color]" class="color" value="<?php echo esc_attr($priimo_options['button_color']); ?>"/>
                </p>
            </div>
            <div id="settings-content-logo" class="settings-content">
                <p class="lblfield"><label><?php _e('Custom Favicon','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="text" name="priimo_options[custom_favicon]" id="custom_favicon" size="36" value="<?php echo esc_attr($priimo_options['custom_favicon']); ?>"/>
                    <input id="custom_favicon_upload" type="button" class="image_upload" value="<?php _e('Upload Favicon Image','priimo'); ?>" />
                </p>
            </div>
            <div id="settings-content-layout" class="settings-content">
                <p class="lblfield"><label><?php _e('Sidebar Layout','priimo'); ?></label></p>
                <p class="frmfield">
                    <?php foreach (priimo_sidebar_layout() as $sidebar_layout) { ?>
                        <?php
                            if($priimo_options['sidebar_layout'] == $sidebar_layout['value'])
                                $current = ' selected-sidebar-layout';
                            else
                                $current = '';
                        ?>
                        <img title="<?php echo $sidebar_layout['label']; ?>" class="sidebar-layout-image<?php echo $current; ?>" id="<?php echo $sidebar_layout['value']; ?>" src="<?php echo get_template_directory_uri(); ?>/admin/images/<?php echo $sidebar_layout['value']; ?>.png"/>
                    <?php } ?>
                    <input type="hidden" name="priimo_options[sidebar_layout]" id="sidebar_layout" value="<?php echo esc_attr($priimo_options['sidebar_layout']); ?>"/>
                </p>
            </div>
            <div id="settings-content-slideshow" class="settings-content">
                <p class="lblfield"><label><?php _e('Enable slideshow','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="checkbox" name="priimo_options[enable_slideshow]" value="true" <?php checked(true,$priimo_options['enable_slideshow']); ?> />
                    <span class="field-meta"><?php _e('Display post slideshow on front page','priimo'); ?></span>
                </p>
                <p class="lblfield"><label><?php _e('Number of slides','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="text" id="slideshow-count" name="priimo_options[slideshow_count]" value="<?php echo esc_attr($priimo_options['slideshow_count']); ?>"/>
                </p>
                <p class="lblfield"><label><?php _e('Slideshow interval','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="text" id="slideshow-interval" name="priimo_options[slideshow_interval]" value="<?php echo esc_attr($priimo_options['slideshow_interval']); ?>"/>
                    <span class="field-meta"><?php _e('Seconds','priimo'); ?></span>
                </p>
            </div>
            <div id="settings-content-portfolio" class="settings-content">
                <p class="lblfield"><label><?php _e('Number of portfolio columns','priimo'); ?></label></p>
                <p class="frmfield">
                    <select name="priimo_options[portfolio_columns]">
                        <?php foreach (priimo_portfolio_columns() as $portfolio_cols) { ?>
                            <option value="<?php echo $portfolio_cols['value']; ?>" <?php selected($priimo_options['portfolio_columns'], $portfolio_cols['value']); ?>><?php echo $portfolio_cols['label']; ?></option>
                        <?php } ?>
                    </select>
                </p>
            </div>
            <div id="settings-content-blog" class="settings-content">
                <p class="lblfield"><label><?php _e('Blog Layout Style','priimo'); ?></label></p>
                <p class="frmfield">
                    <select name="priimo_options[blog_style]">
                        <?php foreach (priimo_blog_style() as $blog_style) { ?>
                            <option value="<?php echo $blog_style['value']; ?>" <?php selected($priimo_options['blog_style'], $blog_style['value']); ?>><?php echo $blog_style['label']; ?></option>
                        <?php } ?>
                    </select>
                </p>
                <p class="lblfield"><label><?php _e('Default Search Box','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="checkbox" name="priimo_options[show_search]" value="true" <?php checked(true,$priimo_options['show_search']); ?> />
                    <span class="field-meta"><?php _e('Display default search box in header','priimo'); ?></span>
                </p>
                <p class="lblfield"><label><?php _e('Categories and Tags','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="checkbox" name="priimo_options[show_single_utility]" value="true" <?php checked(true,$priimo_options['show_single_utility']); ?> />
                    <span class="field-meta"><?php _e('Show tags and categories for single entries','priimo'); ?></span>
                </p>
                <p class="lblfield"><label><?php _e('Page Meta','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="checkbox" name="priimo_options[show_page_meta]" value="true" <?php checked(true,$priimo_options['show_page_meta']); ?> />
                    <span class="field-meta"><?php _e('Show meta information for pages','priimo'); ?></span>
                </p>
                <p class="lblfield"><label><?php _e('Post bookmarking','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="checkbox" name="priimo_options[show_bookmark_buttons]" value="true" <?php checked(true,$priimo_options['show_bookmark_buttons']); ?> />
                    <span class="field-meta"><?php _e('Show post bookmarking buttons for single templates','priimo'); ?></span>
                </p>
            </div>
            <div id="settings-content-social" class="settings-content">
                <p class="lblfield"><label><img class="social-lbl" src="<?php echo get_template_directory_uri(); ?>/admin/images/facebook.png"/><?php _e('Facebook','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="text" class="social-field" id="facebook-user" name="priimo_options[facebook_user]" value="<?php echo esc_attr($priimo_options['facebook_user']); ?>"/>
                    <span class="field-meta"><?php _e('Enter your facebook user id','priimo'); ?></span>
                </p>
                <p class="lblfield"><label><img class="social-lbl" src="<?php echo get_template_directory_uri(); ?>/admin/images/twitter.png"/><?php _e('Twitter','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="text" class="social-field" id="twitter-user" name="priimo_options[twitter_user]" value="<?php echo esc_attr($priimo_options['twitter_user']); ?>"/>
                    <span class="field-meta"><?php _e('Enter your twitter user id','priimo'); ?></span>
                </p>
                <p class="lblfield"><label><img class="social-lbl" src="<?php echo get_template_directory_uri(); ?>/admin/images/rss.png"/><?php _e('RSS','priimo'); ?></label></p>
                <p class="frmfield">
                    <input type="checkbox" name="priimo_options[enable_rss]" value="true" <?php checked(true,$priimo_options['enable_rss']); ?> />
                    <span class="field-meta"><?php _e('Show RSS icon','priimo'); ?></span>
                </p>
            </div>
            <div class="settings-content-container-footer">
                <?php submit_button('Save Settings','primary','settings-submit',false); ?>
            </div>
            </form>            
        </div>
        <div class="settings-footer">
            <table class="tablayout"><tr>
            <td class="left" style="width:50%;">
                <form class="settings-form" method="post" id="reset-form" onsubmit="return confirmAction()">
                    <input type="submit" name="settings-reset" id="settings-reset" value="<?php _e('Reset Settings','priimo'); ?>" />
                </form>
            </td>
            <td class="right" style="width:50%;">
                <?php _e('Theme designed and developed by ','priimo'); ?><a href="http://www.dezineappz.com"><?php _e('DezineAppz','priimo'); ?></a>
            </td>
            </tr></table>
        </div>
    </div>  <!-- End of Settings Wrap -->  
    <?php
}

function priimo_validate_options($input) {
    $output = $defaults = priimo_get_default_options();

    //Validating dropdowns and radio options
    if (isset($input['sidebar_layout']) && array_key_exists($input['sidebar_layout'], priimo_sidebar_layout()))
	$output['sidebar_layout'] = $input['sidebar_layout'];
    if (isset($input['blog_style']) && array_key_exists($input['blog_style'], priimo_blog_style()))
	$output['blog_style'] = $input['blog_style'];
    if (isset($input['portfolio_columns']) && array_key_exists($input['portfolio_columns'], priimo_portfolio_columns()))
	$output['portfolio_columns'] = $input['portfolio_columns'];
    if (isset($input['content_font']) && array_key_exists($input['content_font'], priimo_content_fonts()))
	$output['content_font'] = $input['content_font'];

    //Validating Color Boxes
    if(priimo_validate_color($input['font_color']))
        $output['font_color'] = $input['font_color'];
    if(priimo_validate_color($input['link_color']))
        $output['link_color'] = $input['link_color'];
    if(priimo_validate_color($input['button_color']))
        $output['button_color'] = $input['button_color'];

    //Validating Image fields
    if(trim($input['custom_favicon']) == "") {
        $output['custom_favicon'] = $input['custom_favicon'];
    } else {
        if(priimo_validate_image_url($input['custom_favicon'])) {
            if(priimo_validate_image_size($input['custom_favicon'],16,16))
                $output['custom_favicon'] = $input['custom_favicon'];
            else
                add_settings_error('priimo_options', 'invalid-favicon-size', __('Favicon size cannot exceed 16 x 16 pixels','priimo'), 'error');
        } else
            add_settings_error('priimo_options', 'invalid-favicon-image', __('Invalid favicon image URL','priimo'), 'error');
    }

    //Validating number fields
    if(priimo_validate_number($input['slideshow_count'],1,20)) {
        $output['slideshow_count'] = $input['slideshow_count'];
    } else {
        add_settings_error('priimo_options', 'invalid-slideshow-count', __('Number of slides should be a number and should be in the range from 1 to 20','priimo'), 'error');
    }

    if(priimo_validate_number($input['slideshow_interval'],1,10)) {
        $output['slideshow_interval'] = $input['slideshow_interval'];
    } else {
        add_settings_error('priimo_options', 'invalid-slideshow-interval', __('Slideshow interval should be a number and should be in range from 1 to 10 secs','priimo'), 'error');
    }

    if(priimo_validate_decimal($input['font_size'])) {
        $output['font_size'] = $input['font_size'];
    } else {
        add_settings_error('priimo_options', 'invalid-font-size', __('Invalid font size. Font size should be in decimal format','priimo'), 'error');
    }

    //Validating Social site usernames
    if(priimo_validate_social_user($input['facebook_user']) or trim($input['facebook_user']) == "")
        $output['facebook_user'] = $input['facebook_user'];
    else
        add_settings_error('priimo_options', 'invalid-facebook-user', 'Invalid facebook username', 'error');

    if(priimo_validate_social_user($input['twitter_user']) or trim($input['twitter_user']) == "")
        $output['twitter_user'] = $input['twitter_user'];
    else
        add_settings_error('priimo_options', 'invalid-twitter-user', 'Invalid twitter username', 'error');

    //Validating all the checkboxes
    $chkboxinputs = array('show_search','show_single_utility','show_page_meta','show_bookmark_buttons','enable_slideshow','enable_rss');
    foreach($chkboxinputs as $chkbox) {
        if (!isset($input[$chkbox]))
            $input[$chkbox] = null;
        $output[$chkbox] = ($input[$chkbox] == true ? true : false);
    }

    return apply_filters('priimo_validate_options', $output, $input, $defaults);
}

/* Supporting validation functions */
function priimo_validate_color($color) {
    $exp = "/([A-Za-z0-9])/";
    if(!preg_match($exp,$color))
        return false;
    else
        return true;
}

function priimo_validate_image_url($url) {
    $exp = "/^https?:\/\/(.)*\.(jpg|png|gif|ico)$/i";
    if(!preg_match($exp,$url))
        return false;
    else
        return true;
}

function priimo_validate_image_size($url,$width,$height) {
    $size = getimagesize($url);
    if($size[0] > $width or $size[1] > $height)
        return false;
    else
        return true;
}

function priimo_validate_number($value,$min,$max) {
    if(is_numeric($value)) {
        $value = intval($value);
        if($value < $min or $value > $max)
            return false;
        else
            return true;
    } else return false;
}

function priimo_validate_decimal($value) {
    if(is_numeric($value))
        return true;
    else return false;
}

function priimo_validate_social_user($user) {
    $exp = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*$/";
    if(!preg_match($exp,$user))
        return false;
    else
        return true;
}
?>