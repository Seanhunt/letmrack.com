<?php
/**
 * @package Priimo
 */
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <title>
        <?php
            global $page, $paged, $priimo_options;
            wp_title();
        ?>
    </title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper"> <!-- Start of wrapper -->
    <div id="header">
        <table class="tablayout"><tr>
            <td>
                <h1 class="site-title"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
                <?php if(trim(get_bloginfo('description')) != ''): ?>
                <p class="site-desc"><?php bloginfo('description'); ?></p>
                <?php endif; ?>
            </td>
            <td style="text-align:right;">
                <?php if($priimo_options['show_search']): ?>
                <?php get_search_form(); ?>
                <?php endif; ?>
                <?php if(trim($priimo_options['facebook_user']) != ''): ?>
                <a class="social-icons" href="http://www.facebook.com/<?php echo $priimo_options['facebook_user']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/facebook_icon.png"/></a>
                <?php endif; ?>
                <?php if(trim($priimo_options['twitter_user']) != ''): ?>
                <a class="social-icons" href="http://www.twitter.com/<?php echo $priimo_options['twitter_user']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/twitter_icon.png"/></a>
                <?php endif; ?>
                <?php if($priimo_options['enable_rss']): ?>
                <a class="social-icons" href="<?php bloginfo('rss2_url'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/rss_icon.png"/></a>
                <?php endif; ?>
            </td>
        </tr></table>
    </div>
    <?php wp_nav_menu(array('theme_location' => 'primary', 'container_class' => 'main-menu clearfix')); ?>
    <?php if($priimo_options['enable_slideshow'] and is_front_page()): ?>
    <div id="slider_container">
        <div class="slideshow">
            <?php priimo_slideshow(); ?>
        </div>
    </div>
    <?php endif; ?>
    <div id="content"> <!-- Start of content -->