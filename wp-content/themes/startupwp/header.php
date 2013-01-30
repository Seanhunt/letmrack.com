<?php $options = get_option('startup_options'); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(' | ', true, 'right'); ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/en_US/all.js#xfbml=1";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="wrapper" class="hfeed">
<header>
<div id="branding">
<div id="site-title">
<?php
if (!is_singular()) {echo '<h1>';}
echo '<a href="'.home_url().'/" title="'.esc_attr(get_bloginfo('name')).'" rel="home">';
if ($options['logo']!="")
echo '<img src="'.esc_url($options['logo']).'" alt="'.esc_attr(get_bloginfo('name')).'" id="logo" />';
else
echo esc_html(bloginfo( 'name' ));
echo '</a>';
if (!is_singular() ) {echo '</h1>';}
?>
</div>
<?php if ( $options['description'] ){ echo '<div id="site-description">'.esc_html(get_bloginfo('description')).'</div>'; } ?>
</div>
<?php
if ( $options['social'] ){
echo '<div id="social">';
if ($options['googleurl']!="")
echo '<a href="'.esc_url($options['googleurl']).'" id="social-google"><img src="'.get_template_directory_uri().'/images/social/google.png" alt="Google+" /></a>';
if ($options['linkedinurl']!="")
echo '<a href="'.esc_url($options['linkedinurl']).'" id="social-linkedin"><img src="'.get_template_directory_uri().'/images/social/linkedin.png" alt="LinkedIn" /></a>';
if ($options['twitterurl']!="")
echo '<a href="'.esc_url($options['twitterurl']).'" id="social-twitter"><img src="'.get_template_directory_uri().'/images/social/twitter.png" alt="Twitter" /></a>';
if ($options['facebookurl']!="")
echo '<a href="'.esc_url($options['facebookurl']).'" id="social-facebook"><img src="'.get_template_directory_uri().'/images/social/facebook.png" alt="Facebook" /></a>';
echo '</div>'; } ?>
<nav>
<?php if ( $options['search'] ){ get_search_form(); } ?>
<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
<script type="text/javascript">jQuery("ul").parent("li").addClass("parent");</script>
</nav>
<?php if ( $options['crumbs'] ){ startup_breadcrumbs(); } ?>
</header>
<?php if ( $options['slider'] ){ echo nivoslider4wp_show(); } ?>
<?php if ( $options['twitter'] ){
echo "<div id='twitter-feed'>";
echo "<script charset='utf-8' src='https://widgets.twimg.com/j/2/widget.js'></script>";
echo "<script>";
echo "new TWTR.Widget({";
echo "version: 2,";
echo "type: 'profile',";
echo "rpp: 1,";
echo "interval: 30000,";
echo "width: 'auto',";
echo "height: 100,";
echo "theme: {";
echo "shell: {";
echo "background: '#eee',";
echo "color: '#333'";
echo "},";
echo "tweets: {";
echo "background: '#eee',";
echo "color: '#333',";
echo "links: '#09f'";
echo "}";
echo "},";
echo "features: {";
echo "scrollbar: false,";
echo "loop: false,";
echo "live: false,";
echo "behavior: 'all'";
echo "}";
echo "}).render().setUser('".sanitize_text_field($options['twitname'])."').start();";
echo "</script>";
echo "</div>"; } ?>
<div id="container">