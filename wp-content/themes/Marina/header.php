<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>


<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php 
wp_enqueue_script('jquery');
wp_enqueue_script('easing', get_stylesheet_directory_uri() . '/js/jquery.easing.1.1.js');
wp_enqueue_script('carousal', get_stylesheet_directory_uri() . '/js/jcarousel.js');
if(get_option('marina_cufon') == "Yes") { 
wp_enqueue_script('cufon', get_stylesheet_directory_uri() . '/js/cufon.js');
wp_enqueue_script('rockwell', get_stylesheet_directory_uri() . '/js/TitilliumMaps26L_800.font.js');
wp_enqueue_script('effects', get_stylesheet_directory_uri() . '/js/effects.js');
}
?>

<script type="text/javascript"><!--//--><![CDATA[//><!--
sfHover = function() {
	if (!document.getElementsByTagName) return false;
	var sfEls1 = document.getElementById("catmenu").getElementsByTagName("li");
	for (var i=0; i<sfEls1.length; i++) {
		sfEls1[i].onmouseover=function() {
			this.className+=" sfhover1";
		}
		sfEls1[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover1\\b"), "");
		}
	}
		var sfEls1 = document.getElementById("menu").getElementsByTagName("li");
	for (var i=0; i<sfEls1.length; i++) {
		sfEls1[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls1[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
//--><!]]></script>

<?php wp_get_archives('type=monthly&format=link'); ?>
<?php //comments_popup_script(); // off by default ?>
<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); ?>

</head>
<body >

<div id="masthead"> 
<div id="catmenucontainer">
	
	<?php wp_nav_menu( array( 'container_id' => 'catmenu','theme_location' => 'primary','fallback_cb'=> '' ) ); ?>
	
</div>	
<div id="top"> 

<div class="blogname">
	<h1><a href="<?php bloginfo('siteurl');?>/" title="<?php bloginfo('name');?>"><?php bloginfo('name');?></a></h1>

</div>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
</div>

<div id="foxmenucontainer">
		<?php wp_nav_menu( array( 'container_id' => 'menu', 'theme_location' => 'secondary','fallback_cb'=> '' ) ); ?>	
</div>

</div>

<div id="wrapper">
<?php if (is_front_page()) { ?>
<?php include (TEMPLATEPATH . '/tab.php'); ?>	
<?php } ?>

<div id="casing" class="rounded <?php if (!is_front_page()){ echo "incase";} ?>">		