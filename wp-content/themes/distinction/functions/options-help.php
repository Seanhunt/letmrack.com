<?php

if ( isset ( $_GET['tab'] ) ) {
       	$tab = $_GET['tab'];
} else {
       	$tab = 'general';
}
switch ( $tab ) {
       	case 'general' :
       		$tabtext = wpnj_distinction_get_contextual_help_options_general();
       		break;
       	case 'layout' :
       		$tabtext = wpnj_distinction_get_contextual_help_options_layout();
       		break;
		case 'style' :
       		$tabtext = wpnj_distinction_get_contextual_help_options_style();
       		break;
}

function wpnj_distinction_get_contextual_help_options_general() {

	$tabtext = '';
	$tabtext .= <<<EOT
	<h2>General Options</h2>
	<h3>Google Analytics</h3>
	<p>You probably would like to track how your site traffic is doing. If you are using Google Analytics you can simply past the analytics code here.</p>
	<h3>Theme Credit</h3>
	<p>This setting controls the display of a footer credit link. By default, a footer credit link is displayed. You are under no obligation to display a credit link in the footer or anywhere. If you like the theme, feel free to leave it, or not. Whichever.</p>
EOT;
	return $tabtext;
}
	
function wpnj_distinction_get_contextual_help_options_layout() {

	$tabtext = '';
	$tabtext .= <<<EOT
	<h2>Layout Options</h2>
	<h3>Header Options</h3>
	<p><strong>Menu Position:</strong> The default location of the header menu is below the site title/description. Use this setting to 
	display the header navigation menu above the site title/description.</p>
	<p><strong>Header Menu Depth:</strong> By default, the Header Menu displays pages three levels deep with
	a drop down. You can limit the depth of the menu with the following options:</p>
	<ol>
	<li><strong>One</strong> displays only the top-level Pages in the Header Menu</li>
	<li><strong>Two</strong> displays the top-level Pages in the Header Menu, and displays second-level
	Pages in a dropdown menu when the top-level Page is hovered.</li>
	<li><strong>Three</strong> (default) displays the top-level Pages in the Header Menu, displays second-level
	Pages in a dropdown menu when the top-level Page is hovered, and displays third-level Pages in a dropdown menu 
	when the second-level Page is hovered.</li>
	</ol>	
	<p><strong>Site Description:</strong> By default the site description is displayed below the site title. Use this setting to 
	display or hide the site description.</p>
	<p><strong>Search Form:</strong> By default the search form is displayed in the header menu bar. Use this setting to 
	display or hide the search form in the header. This is helpful if you would rather use the search widget in the sidebar.</p>
	<h3>Sidebar Options</h3>
	<p><strong>Sidebar Position:</strong> By default the sidebar is located on the right side of single posts and default pages.
	Use this setting to choose which side you would like your sidebar display.</p>
	
	
EOT;
	return $tabtext;
}

function wpnj_distinction_get_contextual_help_options_style() {

	$tabtext = '';
	$tabtext .= <<<EOT
	<h2>Style Options</h2>
	<h3>Header Graphics</h3>
	<p>By default there are two images surrounding the site title. Use this option to hide these graphicsif you would like.</p>
	<h3>Custom Fonts</h3>
	<p>Use this setting to choose a unique font for all your header text throughout the site.</p>
EOT;
	return $tabtext;
}
?>