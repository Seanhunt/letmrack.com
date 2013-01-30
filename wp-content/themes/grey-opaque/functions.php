<?php
/**
 * Grey Opaque functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, greyopaque_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *		// We are providing our own filter for excerpt_length (or using the unfiltered value)
 *		remove_filter( 'excerpt_length', 'greyopaque_excerpt_length' );
 *		...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Grey_Opaque
 * @since Grey Opaque 1.0.0
 */

/**
 * Definig constants.
 *
 * @since Grey Opaque 1.0.0
 */
define('GREY_OPAQUE_DONATE_LINK_FLATTR', 'http://flattr.com/thing/147612/WordPress-Theme-Grey-Opaque'); // Flattr Link :-)
define('GREY_OPAQUE_DONATE_LINK_PAYPAL', 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9Y3KMDEH9LR28'); // Paypal Link :-)

define('GREY_OPAQUE_WP_VERSION_REQUIRED', '3.0'); // Minimum Required WordPress Version
define('GREY_OPAQUE_WP_VERSION_RUNNING', $GLOBALS['wp_version']); // Running WordPress Version

define('GREY_OPAQUE_PHP_VERSION_REQUIRED', '5.0.0'); // Minimum Required PHP-Version
define('GREY_OPAQUE_PHP_VERSION_RECOMMEND', '5.2.0'); // Recommend PHP-Version
define('GREY_OPAQUE_PHP_VERSION_RUNNING', phpversion()); // Running PHP-Version

if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.4', '<=')) {
	$array_ThemeData = get_theme_data(get_template_directory() . '/style.css');
} else {
	$array_ThemeData = wp_get_theme('grey-opaque');
}

define('GREY_OPAQUE_DEBUG', false); // ONLY for Debug

define('GREY_OPAQUE_THEME_NAME', $array_ThemeData['Title']); // the Themes Name
define('GREY_OPAQUE_THEME_VERSION', $array_ThemeData['Version']); // The Themes Version
define('GREY_OPAQUE_THEME_URI', $array_ThemeData['URI']); // The Themes Page
define('GREY_OPAQUE_THEME_AUTHOR', strip_tags($array_ThemeData['Author'])); // The Themes Author
define('GREY_OPAQUE_THEME_AUTHOR_URI', $array_ThemeData['AuthorURI']); // The Themes Author Page


define('NULL', NULL); // because I can ... damnit !!!

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if(!isset($content_width)) {
	$content_width = 500;
}

/**
 * Check for requirements.
 * We will check for the required PHP-Version..
 *
 * @uses GREY_OPAQUE_PHP_VERSION_REQUIRED
 * @uses GREY_OPAQUE_PHP_VERSION_RUNNING
 * @since Grey Opaque 1.1.0
 */
if(version_compare(GREY_OPAQUE_PHP_VERSION_REQUIRED, GREY_OPAQUE_PHP_VERSION_RUNNING, '>')) {
	if(!function_exists('greyopaque_php_version_check')) {
		function greyopaque_php_version_check() {
			if(!is_admin()) {
				wp_die(sprintf(__('Your server is running PHP Version %1$s. %2$s requires at least %3$s.<br />Please Update.', 'grey-opaque'),
					GREY_OPAQUE_PHP_VERSION_RUNNING,
					'<strong>' . GREY_OPAQUE_THEME_NAME . '</strong>',
					GREY_OPAQUE_PHP_VERSION_REQUIRED
				));
			}
			?>

			<div class='error fade'>
				<p>
					<?php
					printf(__('<p>Your server is running PHP Version %1$s. %2$s requires at least %3$s.<br />Please Update.</p>', 'grey-opaque'),
						GREY_OPAQUE_PHP_VERSION_RUNNING,
						'<strong>' . GREY_OPAQUE_THEME_NAME . '</strong>',
						GREY_OPAQUE_PHP_VERSION_REQUIRED
					);
					?>
				</p>
			</div>
			<?php
		}

		add_action('admin_notices', 'greyopaque_php_version_check');
		add_action('wp', 'greyopaque_php_version_check');
	}
}

/**
 * Check for requirements.
 * We will check for the required WordPress-version..
 *
 * @uses GREY_OPAQUE_WP_VERSION_REQUIRED
 * @uses GREY_OPAQUE_WP_VERSION_RUNNING
 * @since Grey Opaque 1.0.0
 */
if(version_compare(GREY_OPAQUE_WP_VERSION_REQUIRED, GREY_OPAQUE_WP_VERSION_RUNNING, '>')) {
	if(!function_exists('greyopaque_wp_version_check')) {
		function greyopaque_wp_version_check() {
			$var_sLinkWPCodexUpgrade = 'http://codex.wordpress.org/Upgrading_WordPress';

			if(!is_admin()) {
				wp_die(sprintf(__('Your site is running WordPress Version %1$s. %2$s requires at least %3$s.<br />Please Update.</p><p><a href="%4$s">%5$s</a>', 'grey-opaque'),
					GREY_OPAQUE_WP_VERSION_RUNNING,
					'<strong>' . GREY_OPAQUE_THEME_NAME . '</strong>',
					'<a href="' . $var_sLinkWPCodexUpgrade . '">WordPress ' . GREY_OPAQUE_WP_VERSION_REQUIRED . '</a>',
					admin_url(),
					__('Open Dashboard',"grey-opaque")
				));
			}
			?>

			<div class='error fade'>
				<p>
					<?php
					printf(__('<p>Your site is running WordPress Version %1$s. %2$s requires at least %3$s.<br />Please Update.</p>', 'grey-opaque'),
						GREY_OPAQUE_WP_VERSION_RUNNING,
						'<strong>' . GREY_OPAQUE_THEME_NAME . '</strong>',
						'<a href="' . $var_sLinkWPCodexUpgrade . '">WordPress ' . GREY_OPAQUE_WP_VERSION_REQUIRED . '</a>'
					);
					?>
				</p>
			</div>
			<?php
		}

		add_action('admin_notices', 'greyopaque_wp_version_check');
		add_action('wp', 'greyopaque_wp_version_check');
	}
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override greyopaque_setup() in a child theme, add your own greyopaque_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_setup')) {
	function greyopaque_setup() {
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
		/**
		 * Theme supports Post-Formats.
		 *
		 * @since Grey Opaque 1.0.0
		 */
		if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.1', '>=')) {
			add_theme_support('post-formats', array(
				'aside',
				'chat',
				'gallery',
				'link',
				'image',
				'quote',
				'status',
				'video',
				'audio'
			));
		}

		// This theme uses post thumbnails
		add_theme_support('post-thumbnails');

		// Add default posts and comments RSS feed links to head
		add_theme_support('automatic-feed-links');

		// Make theme available for translation
		// Translations can be filed in the /l10n/ directory
		load_theme_textdomain('grey-opaque', get_template_directory() . '/l10n');

		$locale = get_locale();
		$locale_file = get_template_directory() . "/l10n/$locale.php";
		if(is_readable($locale_file)) {
			require_once($locale_file);
		}

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => __('Primary Navigation', 'grey-opaque'),
		));

		// This theme allows users to set a custom background
		/**
		 * Changing background. Depends on settings in themes option.-page.
		 * If no background is given, we will use themes own..
		 */
		if(function_exists('get_background_image') && (get_background_image() || get_background_color())) {
			if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.4', '>=')) {
				add_theme_support('custom-background', array(
					'wp-head-callback' => 'greyopaque_background_edited'
				));

				// This is for the Navigation
				add_theme_support('custom-background', array(
					'wp-head-callback' => 'greyopaque_navigation_background_edited'
				));
			} else {
				add_custom_background();

				// This is for ne Navigation
				add_custom_background('greyopaque_navigation_background_edited');
			}
		} else {
			if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.4', '>=')) {
				add_theme_support('custom-background', array(
					'wp-head-callback' => 'greyopaque_background'
				));
			} else {
				add_custom_background('greyopaque_background');
			}
		}
	}

	/**
	 * Tell WordPress to run greyopaque_setup() when the 'after_setup_theme' hook is run.
	 */
	add_action('after_setup_theme', 'greyopaque_setup');
}

/**
 * Custom Header Business
 * Now in a seperate function for better override in a child theme.
 *
 * @since Grey Opaque 1.3.0
 */
if(!function_exists('greyopaque_custom_header')) {
	function greyopaque_custom_header() {
		// Your changeable header business starts here
		if(!defined('HEADER_TEXTCOLOR')) {
			define('HEADER_TEXTCOLOR', '');
		}

		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		if(!defined('HEADER_IMAGE')) {
			define('HEADER_IMAGE', '%s/images/headers/clouds.jpg');
		}

		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to greyopaque_header_image_width and greyopaque_header_image_height to change these values.
		define('HEADER_IMAGE_WIDTH', apply_filters('greyopaque_header_image_width', 960));
		define('HEADER_IMAGE_HEIGHT', apply_filters('greyopaque_header_image_height', 200));

		// We'll be using post thumbnails for custom header images on posts and pages.
		// We want them to be 960 pixels wide by 200 pixels tall.
		// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
		set_post_thumbnail_size(HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true);
		add_image_size('thumbnail-loop', 150, 150, true);
		add_image_size('thumbnail-single', 510, 150, true);

		// Don't support text inside the header image.
		if(!defined('NO_HEADER_TEXT')) {
			define('NO_HEADER_TEXT', true);
		}

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See greyopaque_admin_header_style(), below.
		$array_GreyOPaqueThemeOptions = greyopaque_get_theme_options('greyopaque-options');
		if(isset($array_GreyOPaqueThemeOptions['show-headerimage']) && $array_GreyOPaqueThemeOptions['show-headerimage'] == 'on') {
			/**
			 * Backward compatibility to WordPress < 3.4
			 *
			 * @since Grey Opaque 1.7.1
			 */
			if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.4', '>=')) {
				add_theme_support('custom-header', array(
					'random-default' => true,
					'wp-head-callback' => 'greyopaque_admin_header_style'
				));
			} else {
				add_custom_image_header('', 'greyopaque_admin_header_style');
			}
		}

		// ... and thus ends the changeable header business.

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		/**
		 * Checking for headerimages.
		 *
		 * @since Grey Opaque 1.0.0
		 */
		$var_sPathHeaderpics = get_template_directory() . '/images/headers';
		$var_sType = 'file';
		$var_bRecursive = false;
		$array_headerImagesList = greyopaque_scan_directory($var_sPathHeaderpics, $var_sType, $var_bRecursive);
		$array_Headers = array();

		for($count_i = 0; count($array_headerImagesList) > $count_i; $count_i++) {
			if(!strstr($array_headerImagesList[$count_i], '-thumbnail')) {
				$array_HeaderImageParts = explode('.', $array_headerImagesList[$count_i]);

				// do we have a thumbnail?
				if(in_array($array_HeaderImageParts['0'] . '-thumbnail.' . $array_HeaderImageParts['1'], $array_headerImagesList)) {
					$array_Headers[$array_HeaderImageParts['0']] = array(
						'url' => '%s/images/headers/' . $array_HeaderImageParts['0'] . '.' . $array_HeaderImageParts['1'],
						'thumbnail_url' => '%s/images/headers/' . $array_HeaderImageParts['0'] . '-thumbnail.' . $array_HeaderImageParts['1'],
						'description' => $array_headerImagesList[$count_i]
					);
				}
			} else {
				continue;
			}
		}

		register_default_headers($array_Headers);
	}

	add_action('after_setup_theme', 'greyopaque_custom_header');
}

/**
 * Default theme options.
 *
 * @since Grey Opaque 1.1.0
 */
if(!function_exists('greyopaque_get_default_options')) {
	function greyopaque_get_default_options() {
		$array_DefaultThemeOptins = array(
			'greyopaque-version' => GREY_OPAQUE_THEME_VERSION,
			'greyopaque-options' => array(
				'show-branding' => 'on',
				'show-headerimage' => 'on'
			),
			'greyopaque-authorbox' => array(
				'show-authorbox' => 'on'
			),
			'greyopaque-adminbar' => array(
				'move-to-bottom' => 'on',
				'in-frontend' => 'on'
			),
			'greyopaque-smilies' => array(
				'show-smilies' => 'on',
				'replace-in-content' => 'on',
				'replace-in-comments' => 'on'
			)
		);

		return $array_DefaultThemeOptins;
	}
}

/**
 * Setting default theme options.
 *
 * @uses greyopaque_get_default_options();
 * @since Grey Opaque 1.1.0
 */
if(!function_exists('greyopaque_set_default_options')) {
	function greyopaque_set_default_options() {
		// set options equal to defaults
		global $array_GreyOpaqueOptions;
		$array_GreyOpaqueOptions = greyopaque_get_theme_options();

		if(false === $array_GreyOpaqueOptions) {
			$array_GreyOpaqueOptions = greyopaque_get_default_options();
		}

		update_option('greyopaque_theme_options', $array_GreyOpaqueOptions);
	}

	// Initialize Theme options
	add_action('after_setup_theme','greyopaque_set_default_options', 9 );
}

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in greyopaque_setup().
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_admin_header_style')) {
	function greyopaque_admin_header_style() {
		?>
		<style type="text/css">
		/* Shows the same border as on front end */
		#headimg {
			border-bottom: 1px solid #000;
			border-top: 4px solid #000;
		}
		/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
			#headimg #name { }
			#headimg #desc { }
		*/
		</style>
		<?php
	}
}

/**
 * Setting default background.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_background')) {
	function greyopaque_background() {
		// Defaults.
		$var_sBackgroundImage = 'background-image:url("' . get_template_directory_uri() . '/images/backgrounds/background.jpg"); ';
		$var_sDefaultBackgroundColor = 'background-color:#474C52;';

		// CSS.
		echo "\n" . '<!-- Default Background Settings -->' . "\n";
		echo '<style type="text/css">';
		echo 'body {' . $var_sBackgroundImage . $var_sDefaultBackgroundColor . 'background-attachment:fixed; background-position:center center; background-repeat:repeat;}';
		echo '#access .menu-header ul, div.menu ul {' . $var_sBackgroundImage . $var_sDefaultBackgroundColor . 'background-attachment:fixed; background-position:center center; background-repeat:repeat;}';
		echo '</style>' . "\n";
		echo '<!-- /Default Background Settings -->' . "\n";
	}
}

/**
 * Setting custom background.
 *
 * @since Grey Opaque 1.7.0
 */
if(!function_exists('greyopaque_background_edited')) {
	function greyopaque_background_edited() {
		$array_ThemeModsGreyOpaque = get_option('theme_mods_grey-opaque');
		$var_sBackgroundImage = (isset($array_ThemeModsGreyOpaque['background_image']) && $array_ThemeModsGreyOpaque['background_image'] != '') ? 'background-image:url("' . $array_ThemeModsGreyOpaque['background_image'] . '");' : false;
		$var_sBackgroundColor = (isset($array_ThemeModsGreyOpaque['background_color']) && $array_ThemeModsGreyOpaque['background_color'] != '') ? 'background-color:#' . $array_ThemeModsGreyOpaque['background_color'] . ';' : false;
		$var_sBackgroundPosition = (isset($array_ThemeModsGreyOpaque['background_position_x']) && $array_ThemeModsGreyOpaque['background_position_x'] != '') ? 'background-position:' . $array_ThemeModsGreyOpaque['background_position_x'] . ';' : false;
		$var_sBackgroundRepeat = (isset($array_ThemeModsGreyOpaque['background_repeat']) && $array_ThemeModsGreyOpaque['background_repeat'] != '') ? 'background-repeat:' . $array_ThemeModsGreyOpaque['background_repeat'] . ';' : false;
		$var_sBackgroundAttachment = (isset($array_ThemeModsGreyOpaque['background_attachment']) && $array_ThemeModsGreyOpaque['background_attachment'] != '') ?  'background-attachment:' . $array_ThemeModsGreyOpaque['background_attachment'] . ';' : false;

		// CSS.
		echo "\n" . '<!-- Default Background Settings -->' . "\n";
		echo '<style type="text/css">';
		echo 'body {' . $var_sBackgroundImage . $var_sBackgroundColor . $var_sBackgroundAttachment . $var_sBackgroundPosition . $var_sBackgroundRepeat . '}';
		echo '#access .menu-header ul, div.menu ul {' . $var_sBackgroundImage . $var_sBackgroundColor . $var_sBackgroundAttachment . $var_sBackgroundPosition . $var_sBackgroundRepeat . '}';
		echo '</style>' . "\n";
		echo '<!-- /Default Background Settings -->' . "\n";
	}
}

/**
 * Set the new background for navigations submenues
 * if the background is not longer default.
 *
 * @since Grey Opaque 1.1.0
 */
if(!function_exists('greyopaque_navigation_background_edited')) {
	function greyopaque_navigation_background_edited() {
		$array_ThemeModsGreyOpaque = get_option('theme_mods_grey-opaque');
		$var_sBackgroundImage = (isset($array_ThemeModsGreyOpaque['background_image']) && $array_ThemeModsGreyOpaque['background_image'] != '') ? 'background-image:url("' . $array_ThemeModsGreyOpaque['background_image'] . '");' : false;
		$var_sBackgroundColor = (isset($array_ThemeModsGreyOpaque['background_color']) && $array_ThemeModsGreyOpaque['background_color'] != '') ? 'background-color:#' . $array_ThemeModsGreyOpaque['background_color'] . ';' : false;
		$var_sBackgroundPositionX = (isset($array_ThemeModsGreyOpaque['background_position_x']) && $array_ThemeModsGreyOpaque['background_position_x'] != '') ? 'background-position:' . $array_ThemeModsGreyOpaque['background_position_x'] . ' top;' : false;
		$var_sBackgroundRepeat = (isset($array_ThemeModsGreyOpaque['background_repeat']) && $array_ThemeModsGreyOpaque['background_repeat'] != '') ? 'background-repeat:' . $array_ThemeModsGreyOpaque['background_repeat'] . ';' : false;
		$var_sBackgroundAttachment = (isset($array_ThemeModsGreyOpaque['background_attachment']) && $array_ThemeModsGreyOpaque['background_attachment'] != '') ?  'background-attachment:' . $array_ThemeModsGreyOpaque['background_attachment'] . ';' : false;

		// CSS for Navigation.
		echo "\n" . '<!-- Navigation Background Settings -->' . "\n";
		echo '<style type="text/css">';
		echo '#access .menu-header ul, div.menu ul {' . $var_sBackgroundImage . $var_sBackgroundColor . $var_sBackgroundAttachment . $var_sBackgroundPositionX . $var_sBackgroundRepeat . ';}';
		echo '</style>' . "\n";
		echo '<!-- /Navigation Background Settings -->' . "\n";
	}
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_page_menu_args')) {
	function greyopaque_page_menu_args($args) {
		$args['show_home'] = true;

		return $args;
	}

	add_filter('wp_page_menu_args', 'greyopaque_page_menu_args');
}

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @return int
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_excerpt_length')) {
	function greyopaque_excerpt_length($length) {
		return 40;
	}

	add_filter('excerpt_length', 'greyopaque_excerpt_length');
}

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @return string "Continue Reading" link
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_continue_reading_link')) {
	function greyopaque_continue_reading_link() {
		return ' <a href="'. get_permalink() . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>', 'grey-opaque') . '</a>';
	}
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and greyopaque_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @return string An ellipsis
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_auto_excerpt_more')) {
	function greyopaque_auto_excerpt_more($more) {
		return ' &hellip;' . greyopaque_continue_reading_link();
	}

	add_filter('excerpt_more', 'greyopaque_auto_excerpt_more');
}

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @return string Excerpt with a pretty "Continue Reading" link
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_custom_excerpt_more')) {
	function greyopaque_custom_excerpt_more($output) {
		if(has_excerpt() && ! is_attachment()) {
			$output .= greyopaque_continue_reading_link();
		}

		return $output;
	}

	add_filter('get_the_excerpt', 'greyopaque_custom_excerpt_more');
}

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Grey Opaque's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 * @since Grey Opaque 1.0.0
 */
add_filter('use_default_gallery_style', '__return_false');

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own greyopaque_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @uses greyopaque_sanitize_twittername();
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_comment')) {
	function greyopaque_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;

		switch($comment->comment_type) {
			case '' :
				?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<?php
					// For comment authors who are the author of the post
					$var_sCommentClass = '';
					if($post = get_post($post_id)) {
						if($comment->user_id === $post->post_author) {
							$var_sCommentClass = 'class="bypostauthor"';
						}
					}
					?>

					<div <?php echo $var_sCommentClass; ?> id="comment-<?php comment_ID(); ?>">
					<div class="comment-author vcard">
						<?php
						echo get_avatar($comment, 40);

						$array_CommentMeta = get_comment_meta($comment->comment_ID, NULL);
						$array_NewCommentFields = greyopaque_commentform_get_new_fields();

						/**
						 * Display twitter and facebook icons for a commenter.
						 *
						 * @since Opaque 1.0.0
						 */
						echo '<div id="comment-author-profiles">' . "\n";
						echo '<ul class="comment-author-profile-services">' . "\n";

						if($comment->user_id != '0') {
							$array_CommentProfiles = greyopaque_commentform_get_new_fields();

							foreach($array_CommentProfiles as $custom_field) {
								$var_sCommentFieldName = str_replace('comment-', '', $custom_field['name']);
								$var_sCommentProfileData = get_user_meta($comment->user_id, $var_sCommentFieldName, true);

								if(!empty($var_sCommentProfileData)) {
									if($var_sCommentFieldName == 'twitter') {
										$var_sCommentProfileData = 'http://twitter.com/#!/' . greyopaque_sanitize_twittername($var_sCommentProfileData);
									}

									echo '<li><a class="comment-author-' . $var_sCommentFieldName . '" href="' . $var_sCommentProfileData . '"></a></li>';
								}
//								else {
//									if(!empty($var_sCommentProfileData)) {
//										$var_sCommentProfileData = get_user_meta($comment->user_id, $var_sCommentFieldName, true);
//									}
//								}
//								if(!empty($var_sCommentProfileData)) {
//									echo '<li><a class="comment-author-' . $var_sCommentFieldName . '" href="' . $var_sCommentProfileData . '"></a></li>';
//								}
							}
						} else {
							if(is_array($array_CommentMeta) && count($array_CommentMeta) != '0') {
								foreach($array_NewCommentFields as $custom_field){
									switch($custom_field['name']) {
										// Twitter
										case 'comment-twitter':
											if(!empty($array_CommentMeta[$custom_field['name']]['0'])) {
												$var_sCommentAuthorProfileLink = 'http://twitter.com/#!/' . $array_CommentMeta[$custom_field['name']]['0'];
												echo '<li><a class="comment-author-' . str_replace('comment-', '', $custom_field['name']) . '" href="' . $var_sCommentAuthorProfileLink . '"></a></li>';
											}
											break;

										default:
											if(!empty($array_CommentMeta[$custom_field['name']]['0'])) {
												echo '<li><a class="comment-author-' . str_replace('comment-', '', $custom_field['name']) . '" href="' . $array_CommentMeta[$custom_field['name']]['0'] . '"></a></li>';
											}
											break;
									}
								}
							}
						}

						echo '</ul>';
						echo '</div>';
						?>

						<?php printf(__('%s <span class="says">says:</span>', 'grey-opaque'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?>

						<div class="comment-meta commentmetadata">
							<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
								<?php
								/* translators: 1: date, 2: time */
								printf(__('%1$s at %2$s', 'grey-opaque'),
									get_comment_date(),
									get_comment_time()
								);
								?>
							</a>
							<?php edit_comment_link(__('(Edit)', 'grey-opaque'), ' '); ?>
						</div><!-- .comment-meta .commentmetadata -->

					</div><!-- .comment-author .vcard -->
					<?php if($comment->comment_approved == '0') : ?>
						<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'grey-opaque'); ?></em>
						<br />
					<?php endif; ?>

					<div class="comment-body"><?php comment_text(); ?></div>

					<div class="reply">
						<?php
						comment_reply_link(array_merge($args, array(
							'depth' => $depth,
							'max_depth' => $args['max_depth']
						)));
						?>
					</div><!-- .reply -->
				</div><!-- #comment-##  -->

				<?php
				break;
			case 'pingback'  :
			case 'trackback' :
				?>
				<li class="post pingback">
					<div class="pingback-link">
						<?php //_e('Pingback:', 'grey-opaque'); ?> <?php comment_author_link(); ?>
						<div class="comment-meta commentmetadata">
							<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
								<?php
								/* translators: 1: date, 2: time */
								printf(__('%1$s at %2$s', 'grey-opaque'),
									get_comment_date(),
									get_comment_time()
								);
								?>
							</a>
							<?php edit_comment_link(__('(Edit)', 'grey-opaque'), ' '); ?>
						</div><!-- .comment-meta .commentmetadata -->
					</div>
					<div class="comment-body"><?php comment_text(); ?></div>
				<?php
				break;
		}
	}
}

/**
 * New fields in commentform.
 *
 * @since Grey Opaque 1.3.0
 */
if(!function_exists('greyopaque_commentform_get_new_fields')) {
	function greyopaque_commentform_get_new_fields() {
		$array_NewCommentFields = array(
			// Twitter
			array(
				'name' => 'comment-twitter',
				'label' => __('Twittername <em>(without @)</em>', 'grey-opaque')
			),
			// Facebook
			array(
				'name' => 'comment-facebook',
				'label' => __('Facebook <em>(complete URL)</em>', 'grey-opaque')
			),
			// Google+
			array(
				'name' => 'comment-googleplus',
				'label' => __('Google+ <em>(complete URL)</em>', 'grey-opaque')
			)
		);

		return $array_NewCommentFields;
	}
}

/**
 * Adding fields to the commentform..
 *
 * @uses greyopaque_add_comment_meta();
 * @uses greyopaque_commentform_get_new_fields();
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_comment_meta')) {
	function greyopaque_comment_meta($fields) {
		$array_NewCommentFields = greyopaque_commentform_get_new_fields();

		if(!empty($array_NewCommentFields)) {
			foreach($array_NewCommentFields as $custom_field) {
				$custom_field_value = '';
				if(isset($_COOKIE['comment_author_' . str_replace('comment-', '', $custom_field['name']) . '_'. COOKIEHASH])) {
					$custom_field_value = $_COOKIE['comment_author_' . str_replace('comment-', '', $custom_field['name']) . '_'. COOKIEHASH];
				}

				$custom_field_name = $custom_field['name'];
				$fields[$custom_field_name] = '<p class="comment-form-' . str_replace('comment-', '', $custom_field_name) . '">' . '<label for="' . $custom_field_name . '">' . $custom_field['label'] . '</label> ' . '<input id="' . $custom_field_name . '" name="' . $custom_field_name . '" type="text" value="' . esc_attr($custom_field_value) . '" size="30" /></p>';
			}
		}

		return $fields;
	}

	add_filter('comment_form_default_fields', 'greyopaque_comment_meta');
}

/**
 * Writing new fields from commentform to database.
 *
 * @uses greyopaque_sanitize_twittername();
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_add_comment_meta')) {
	function greyopaque_add_comment_meta($comment_id) {
		$array_NewCommentFields = greyopaque_commentform_get_new_fields();

		foreach($array_NewCommentFields as $custom_field) {
			if(isset($_REQUEST[$custom_field['name']]) && $_REQUEST[$custom_field['name']] != '') {
				if($custom_field['name'] == 'comment-twitter') {
					add_comment_meta($comment_id, $custom_field['name'], greyopaque_sanitize_twittername($_REQUEST[$custom_field['name']]), true);
				} else {
					add_comment_meta($comment_id, $custom_field['name'], $_REQUEST[$custom_field['name']], true);
				}

				setcookie('comment_author_' . str_replace('comment-', '', $custom_field['name']) . '_'. COOKIEHASH, $_REQUEST[$custom_field['name']], time() + 30000000, COOKIEPATH);
			} else {
				setcookie('comment_author_' . str_replace('comment-', '', $custom_field['name']) . '_'. COOKIEHASH, '', time() + 30000000, COOKIEPATH);
			}
		}
	}

	add_action('comment_post', 'greyopaque_add_comment_meta');
}

/**
 * Clears all floats after commentfields.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_clear_after_commentfields')) {
	function greyopaque_clear_after_commentfields() {
		echo '<div id="commentfield-clear">&nbsp;</div>';
	}

	add_action('comment_form_after_fields', 'greyopaque_clear_after_commentfields');
}

/**
 * Seperate counter for comments and pings/tracks..
 *
 * @since Grey opaque 1.0.0
 */
if(!function_exists('greyopaque_get_ping_count')) {
	function greyopaque_get_ping_count() {
		global $id;

		$comments_by_type = &separate_comments(get_comments('post_id=' . $id));

		return count($comments_by_type['pings']);
	}
}

if(!function_exists('greyopaque_get_comment_count')) {
	function greyopaque_get_comment_count() {
		global $id;

		$comments_by_type = &separate_comments(get_comments('post_id=' . $id));

		return count($comments_by_type['comment']);
	}

	add_filter('get_comments_number', 'greyopaque_get_comment_count', 0);
}

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override greyopaque_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @uses register_sidebar
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_widgets_init')) {
	function greyopaque_widgets_init() {
		// Area 1, located at the top of the sidebar.
		register_sidebar(array(
			'name' => __('Primary Widget Area', 'grey-opaque'),
			'id' => 'primary-widget-area',
			'description' => __('The primary widget area', 'grey-opaque'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));

		// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
		register_sidebar(array(
			'name' => __('Secondary Widget Area', 'grey-opaque'),
			'id' => 'secondary-widget-area',
			'description' => __('The secondary widget area', 'grey-opaque'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));

		// Area 3, located in the footer. Empty by default.
		register_sidebar(array(
			'name' => __('First Footer Widget Area', 'grey-opaque'),
			'id' => 'first-footer-widget-area',
			'description' => __('The first footer widget area', 'grey-opaque'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));

		// Area 4, located in the footer. Empty by default.
		register_sidebar(array(
			'name' => __('Second Footer Widget Area', 'grey-opaque'),
			'id' => 'second-footer-widget-area',
			'description' => __('The second footer widget area', 'grey-opaque'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));

		// Area 5, located in the footer. Empty by default.
		register_sidebar(array(
			'name' => __('Third Footer Widget Area', 'grey-opaque'),
			'id' => 'third-footer-widget-area',
			'description' => __('The third footer widget area', 'grey-opaque'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));

		// Area 6, located in the footer. Empty by default.
		register_sidebar(array(
			'name' => __('Fourth Footer Widget Area', 'grey-opaque'),
			'id' => 'fourth-footer-widget-area',
			'description' => __('The fourth footer widget area', 'grey-opaque'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
	}

	/** Register sidebars by running greyopaque_widgets_init() on the widgets_init hook. */
	add_action('widgets_init', 'greyopaque_widgets_init');
}

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
 * to remove the default style. Using Grey Opaque in WordPress 3.0 will show the styles,
 * but they won't have any effect on the widget in default Grey Opaque styling.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_remove_recent_comments_style')) {
	function greyopaque_remove_recent_comments_style() {
		add_filter('show_recent_comments_widget_style', '__return_false');
	}

	add_action('widgets_init', 'greyopaque_remove_recent_comments_style');
}

/**
 * Prints HTML with meta information for the current post-date/time.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_posted_on_timestamp')) {
	function greyopaque_posted_on_timestamp() {
		echo get_the_date() . '<br /><span>' . esc_attr(get_the_time()) . '</span>';
	}
}

/**
 * Prints HTML with meta information for the current post-author.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_posted_on_auhtor')) {
	function greyopaque_posted_on_auhtor() {
		echo '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_the_author() . '</a>';
	}
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists( 'greyopaque_posted_on')) {
	function greyopaque_posted_on() {
		printf(__('<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'grey-opaque'),
			'meta-prep meta-prep-author',
			sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
				get_permalink(),
				esc_attr(get_the_time()),
				get_the_date()
			),
			sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
				get_author_posts_url(get_the_author_meta('ID')),
				sprintf(esc_attr__('View all posts by %s', 'grey-opaque'), get_the_author()),
				get_the_author()
			)
		);
	}
}

/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_posted_in')) {
	function greyopaque_posted_in() {
		// Retrieves tag list of current post, separated by commas.
		$tag_list = get_the_tag_list('', ', ');
		if($tag_list) {
			$posted_in = __('Category(s): %1$s<br />Tags: %2$s', 'grey-opaque');
		} elseif(is_object_in_taxonomy(get_post_type(), 'category')) {
			$posted_in = __('Category(s): %1$s', 'grey-opaque');
		} else {
			$posted_in = '';
		}

		// Prints the string, replacing the placeholders.
		printf(
			$posted_in,
			get_the_category_list(', '),
			$tag_list,
			get_permalink(),
			the_title_attribute('echo=0')
		);
	}
}

/**
 * Change contact info in users profile.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_extra_contact_info')) {
	function greyopaque_extra_contact_info($contactmethods) {
		$array_Contactmethods = array(
			'facebook' => 'Facebook',
			'twitter' => 'Twitter',
			'youtube' => 'Youtube',
			'delicious' => 'Delicious',
			'linkedin' => 'LinkedIn',
			'googleplus' => 'Google+'
		);

//		$array_Contactmethods = array_merge($contactmethods, $array_Contactmethods);

		return $array_Contactmethods;
	}

	add_filter('user_contactmethods', 'greyopaque_extra_contact_info');
}

/**
 * Show the content.
 *
 * Overwriting the_content() for our own styling.
 *
 * @uses greyopaque_the_authorbox();
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_the_content')) {
	function greyopaque_the_content($more_link_text = null, $stripteaser = 0) {
		$content = get_the_content($more_link_text, $stripteaser) . greyopaque_the_authorbox();
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]>', $content);

		echo $content;
	}
}

/**
 * Infobox left beside the entries.
 *
 * @var string $var_sType
 * @since Grey Opaque 1.0.1
 */
if(!function_exists('greyopaque_the_infobox')) {
	function greyopaque_the_infobox($var_sType = '') {
		echo '<div class="entry-actions">';

		switch($var_sType) {
			/**
			 * Only tagcloud.
			 * 'search' => empty search.
			 * '404' => 404-page.
			 * 'tagcloud' => only tagcloud shoul'd be displayed.
			 */
			case '404':
			case 'search':
			case 'tagcloud':
				?>
				<div class="tagcloud">
					<?php
					if(function_exists('wp_tag_cloud')) {
						wp_tag_cloud(array(
							'number' => 20,
							'taxonomy' => 'post_tag'
						));
					}
					?>
				</div>
				<?php
				break;

			/**
			 * Complete infobox.
			 */
			default:
				?>
				<?php
				$var_sPermalink = get_permalink();
				$var_sTitle = rawurlencode(get_the_title());
				?>
				<div class="timestamp">
					<p class="entry-timestamp">
						<?php greyopaque_posted_on_timestamp(); ?>
						<br />
						<?php _e('by: ', 'grey-opaque') . greyopaque_posted_on_auhtor(); ?>
						<br />
						<?php
						/**
						 * Attachment
						 */
						if(is_attachment()) {
							if(wp_attachment_is_image()) {
								$metadata = wp_get_attachment_metadata();
								printf(__('Size: %s px', 'grey-opaque'),
									sprintf('<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
										wp_get_attachment_url(),
										esc_attr(__('Link to full-size image', 'grey-opaque')),
										$metadata['width'],
										$metadata['height']
									)
								);
							}
						} // ENDE if(is_attachment())

						edit_post_link(__('Edit', 'grey-opaque'), '<span class="edit-link">', '</span>');
						?>
					</p>
				</div>
				<div class="actions">
					<ul>
						<?php if(comments_open()) : ?>
							<li>
								<a class="comment" href="<?php echo $var_sPermalink; ?>#comments"><?php echo number_format_i18n(get_comments_number()); ?> <?php _e('comment(s)', 'grey-opaque'); ?></a>
							</li>

							<?php if(greyopaque_get_ping_count()) :?>
								<li>
									<a class="comment" href="<?php echo $var_sPermalink; ?>#pings"><?php echo number_format_i18n(greyopaque_get_ping_count()); ?> <?php _e('pingback(s)', 'grey-opaque'); ?></a>
								</li>
							<?php endif; ?>
						<?php endif; ?>

						<?php if(is_singular()) : ?>
							<li>
								<div class="share">
									<span><?php _e('share this post', 'grey-opaque'); ?></span>
									<ul class="sharing">
										<li class="first">
											<a rel="nofollow external" title="<?php _e('Add to del.icio.us', 'grey-opaque'); ?>" id="share_delicious" href="http://del.icio.us/post?url=<?php echo $var_sPermalink; ?>&amp;title=<?php echo $var_sTitle; ?>"><span class="network">del.icio.us</span></a>
										</li>
										<li>
											<a rel="nofollow external" title="<?php _e('Share on Facebook', 'grey-opaque'); ?>" id="share_facebook" href="http://www.facebook.com/share.php?u=<?php echo $var_sPermalink; ?>"><span class="network">Facebook</span></a>
										</li>
										<li>
											<a rel="nofollow external" title="<?php _e('Share on Reddit', 'grey-opaque'); ?>" id="share_reddit" href="http://reddit.com/submit?url=<?php echo $var_sPermalink; ?>&amp;title=<?php echo $var_sTitle; ?>"><span class="network">Reddit</span></a>
										</li>
										<li>
											<a rel="nofollow external" title="<?php _e('Share on StumbleUpon', 'grey-opaque'); ?>" id="share_stumbleupon" href="http://www.stumbleupon.com/submit?url=<?php echo $var_sPermalink; ?>&amp;title=<?php echo $var_sTitle; ?>"><span class="network">StumbleUpon</span></a>
										</li>
										<li>
											<a rel="nofollow external" title="<?php _e('Tweet this!', 'grey-opaque'); ?>" id="share_twitter" href="https://twitter.com/intent/tweet?text=<?php echo $var_sTitle; ?>&url=<?php echo $var_sPermalink; echo (get_the_author_meta('twitter')) ? '&via=' . greyopaque_sanitize_twittername(get_the_author_meta('twitter')) . '&related=' . greyopaque_sanitize_twittername(get_the_author_meta('twitter')) : ''; ?>"><span class="network">Twitter</span></a>
										</li>
										<li>
											<a rel="nofollow external" title="<?php _e('Favourite on Technorati', 'grey-opaque'); ?>" id="share_technorati" href="http://www.technorati.com/faves?add=<?php echo $var_sPermalink; ?>"><span class="network">Technorati</span></a>
										</li>
										<li class="last">
											<a rel="nofollow external" title="<?php _e('Share on Google+', 'grey-opaque'); ?>" id="share_googleplus" href="https://plusone.google.com/_/+1/confirm?url=<?php echo $var_sPermalink; ?>"><span class="network">Google+</span></a>
										</li>
									</ul>
								</div>
							</li>
						<?php endif; ?>

						<?php if(comments_open()) : ?>
							<li>
								<a class="subscribe" href="<?php echo get_post_comments_feed_link(); ?>"><?php _e('comments RSS', 'grey-opaque'); ?></a>
							</li>
						<?php endif; ?>

						<li>
							<a rel="trackback" class="trackback" href="<?php echo get_trackback_url(); ?>"><?php _e('trackback', 'grey-opaque'); ?></a>
						</li>
						<li>
							<a class="permalink" href="<?php echo $var_sPermalink; ?>"><?php _e('permalink', 'grey-opaque'); ?></a>
						</li>
					</ul>
				</div>
				<?php
				break;
		}

		echo '</div>';
	}
}

/**
 * Escaping characters inside the <code>-block in comments.
 *
 * Allows readers post fragments of code in their comments
 * (wrapped in <code>...</code>) which are automatically
 * encoded (think of < and &lt;)
 *
 * !!! Use only for comments !!!
 *
 * @since Grey Opaque 1.5
 *
 * @uses esc_html();
 */
if(!function_exists('greyopaque_escape_code_in_comment')) {
	function greyopaque_escape_code_in_comment($content) {
// 		$encoded = preg_replace_callback('/<code>(.*?)<\/code>/ims', create_function('$matches', '$matches[1] = preg_replace(array("/^[\r|\n]+/i", "/[\r|\n]+$/i"), "", $matches[1]); return "<code>" . htmlentities($matches[1]) . "</code>";'), $content);

// 		if($encoded) {
// 			return $encoded;
// 		} else {
// 			return $content;
// 		}

		return preg_replace_callback(
				'#(<pre.*?>)(.*?)(</pre>)#imsu',
				create_function(
						'$i',
						'return $i[1] . esc_html($i[2]) . $i[3];'
				),
				$content
		);
	}

// 	add_filter('pre_comment_content', 'greyopaque_escape_code_in_comment');
	add_filter('comment_text', 'greyopaque_escape_code_in_comment');
}

/**
 * Escaping characters inside the <code>-block in comments.
 *
 * Allows readers post fragments of code in their comments
 * (wrapped in <pre>...</pre>) which are automatically
 * encoded (think of < and &lt;)
 *
 * !!! Use only for comments !!!
 *
 * @since Grey Opaque 1.0.0
 *
 * @uses esc_html();
 */
if(!function_exists('greyopaque_pre_esc_html')) {
	function greyopaque_pre_esc_html($content) {
		return preg_replace_callback(
				'#(<pre.*?>)(.*?)(</pre>)#imsu',
				create_function(
						'$i',
						'return $i[1] . esc_html($i[2]) . $i[3];'
				),
				$content
		);
	}

	add_filter('comment_text', 'greyopaque_pre_esc_html');
}

/**
 * JavaScript for SmoothScroll.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_smooth_scroll')) {
	function greyopaque_smooth_scroll() {
		echo '<script type="text/javascript" src="' . get_template_directory_uri() . '/javascript/jquery-smooth-scroll-to-anchor.js"></script>';
	}

	wp_enqueue_script('jquery');
	add_action('wp_footer', 'greyopaque_smooth_scroll');
}

/**
 * Loading classes.
 *
 * @since Grey Opaque 1.0.3.5
 */
get_template_part('includes/classes');

/**
 * Theme Options.
 * Settings for Grey Opaque.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_theme_admin')) {
	function greyopaque_theme_admin() {
		/**
		 * Check if we have the recommend php-version at least.
		 * If not, give a warning in settings-page.
		 *
		 * @since Grey Opaque 1.3.0
		 */
		if(version_compare(GREY_OPAQUE_PHP_VERSION_RECOMMEND, GREY_OPAQUE_PHP_VERSION_RUNNING, '>')) {
			echo '<div id="message" class="error"><p>';
			echo sprintf(__('Your server is running PHP Version %1$s. %2$s recommends at least %3$s.<br />Please Update.', 'grey-opaque'),
				GREY_OPAQUE_PHP_VERSION_RUNNING,
				'<strong>' . GREY_OPAQUE_THEME_NAME . '</strong>',
				GREY_OPAQUE_PHP_VERSION_RECOMMEND
			);
			echo '</p></div>';
		}

		$nonce= wp_create_nonce('grey-opaque-theme-settings');

		if(isset($_REQUEST['submitted']) and $_REQUEST['submitted'] == 'yes') {
			check_admin_referer($nonce);
			greyopaque_update_options();

			echo '<div id="message" class="updated fade"><p><strong>' . __('Your settings have been saved.', 'grey-opaque') . '</strong></p></div>';
		}

		$array_ThemeOptions = greyopaque_get_theme_options();
		?>
		<script type="text/javascript">
		/* <![CDATA[ */
		function getStyle(oElm, strCssRule) {
			var strValue = "";
			if(document.defaultView && document.defaultView.getComputedStyle) {
				strValue = document.defaultView.getComputedStyle(oElm, "").getPropertyValue(strCssRule);
			} else if(oElm.currentStyle) {
				strCssRule = strCssRule.replace(/\-(\w)/g, function (strMatch, p1) {
					return p1.toUpperCase();
				});
				strValue = oElm.currentStyle[strCssRule];
			}

			return strValue;
		}

		function toggleVisibility(ElementId) {
			var htmlStyle = getStyle(document.getElementById(ElementId), "display");
			if(htmlStyle == 'none') {
				document.getElementById(ElementId).style.display = 'block';
			} else {
				document.getElementById(ElementId).style.display = 'none';
			}
		}
		/* ]]> */
		</script>
		<div class="wrap">
			<form method="post" action="themes.php?page=functions">
				<?php wp_nonce_field($nonce); ?>
				<h2><?php _e('Grey Opaque Configuration', 'grey-opaque'); ?></h2>
				<h3>
					<?php
					echo sprintf(__('You find a documentation of these settings under %1$s.', 'grey-opaque'),
						'<a href="' . admin_url('themes.php?page=documentation') . '">' . __('Appearance -> Documentation', 'grey-opaque') . '</a>'
					);
					?>
				</h3>
				<div class="themeconfig-wrapper" style="clear:both;">
					<!-- Themeoptions -->
					<div class="themeoptions" style="width:33%; float:left;">
						<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Theme Options', 'grey-opaque'); ?></h3>
						<?php
						$var_sShowBranding = (isset($array_ThemeOptions['greyopaque-options']['show-branding'])) ? checked($array_ThemeOptions['greyopaque-options']['show-branding'], 'on', 0) : false;
						$var_sShowCursor = (isset($array_ThemeOptions['greyopaque-options']['show-cursor'])) ? checked($array_ThemeOptions['greyopaque-options']['show-cursor'], 'on', 0) : false;
						$var_sShowHovercards = (isset($array_ThemeOptions['greyopaque-options']['show-hovercards'])) ? checked($array_ThemeOptions['greyopaque-options']['show-hovercards'], 'on', 0) : false;
						$var_sGetPostthumbnailForFacebook = (isset($array_ThemeOptions['greyopaque-options']['get-postthumbnail-for-facebook'])) ? checked($array_ThemeOptions['greyopaque-options']['get-postthumbnail-for-facebook'], 'on', 0) : false;
						$var_sDeactivateHyperlinks = (isset($array_ThemeOptions['greyopaque-options']['deactivate-hyperlinks-in-comments'])) ? checked($array_ThemeOptions['greyopaque-options']['deactivate-hyperlinks-in-comments'], 'on', 0) : false;
						$var_sRemoveGeneratorTag = (isset($array_ThemeOptions['greyopaque-options']['remove-generator-metatag'])) ? checked($array_ThemeOptions['greyopaque-options']['remove-generator-metatag'], 'on', 0) : false;

						if(isset($array_ThemeOptions['greyopaque-options']['show-headerimage'])) {
							$var_sShowHeaderimage = checked($array_ThemeOptions['greyopaque-options']['show-headerimage'], 'on', 0);
							$var_sHeaderImageHintVisibility = ' display:block;';
						} else {
							$var_sShowHeaderimage = '';
							$var_sHeaderImageHintVisibility = ' display:none;';
						}

						/**
						 * Show Branding.
						 */
						echo '<div class="greyopaque-settings-line-wrapper_show-branding" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Show Branding', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-options[show-branding]" ' . $var_sShowBranding . ' />';
						echo '</div>';
						echo '</div>';

						/**
						 * Show Headerimage.
						 */
						echo '<div class="greyopaque-settings-line-wrapper_show-headerimage" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Show Headerimage', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-options[show-headerimage]" ' . $var_sShowHeaderimage . ' onclick=toggleVisibility(\'greyopaque-settings-line-wrapper_show-headerimage_hint\') />';
						echo '</div>';
						echo '</div>';

						/**
						 * New headwerimage?
						 *
						 * @since Grey Opaque 1.0.0-beta-3
						 */
						echo '<div id="greyopaque-settings-line-wrapper_show-headerimage_hint" style="clear:both; width:240px; -moz-border-radius:10px; -webkit-border-radius:10px; margin:0 0 10px; -moz-box-shadow:3px 3px 11px 1px rgba(0, 0, 0, 0.1); -webkit-box-shadow:3px 3px 11px 1px rgba(0, 0, 0, 0.1); border:1px solid #E09B85; color:#957368;-moz-border-radius:10px 10px 10px 10px; min-height:35px; padding:5px; text-shadow:1px 1px 1px #FFFFFF;' . $var_sHeaderImageHintVisibility . '">';
						echo sprintf(__('You can select or upload a new headerimage <a href="%1$s">%2$s</a>.', 'grey-opaque'),
								admin_url('themes.php?page=custom-header'),
								__('here', 'grey-opaque')
							);
						echo '</div>';

						/**
						 * Show Cursor.
						 */
						echo '<div class="greyopaque-settings-line-wrapper_show-cursor" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Show Themecursor', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-options[show-cursor]" ' . $var_sShowCursor . ' />';
						echo '</div>';
						echo '</div>';

						/**
						 * Postthumbnail for Facebook.
						 */
						echo '<div class="greyopaque-settings-line-wrapper_get-postthumbnail-for-facebook" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Get Postthumbnail for Facebook', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-options[get-postthumbnail-for-facebook]" ' . $var_sGetPostthumbnailForFacebook . ' />';
						echo '</div>';
						echo '</div>';

						/**
						 * Deactivate hyperlinks in commentform.
						 */
						echo '<div class="greyopaque-settings-line-wrapper_deactivate-hyperlinks-in-comments" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Remove automatic Hyperlinks in Comments', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-options[deactivate-hyperlinks-in-comments]" ' . $var_sDeactivateHyperlinks . ' />';
						echo '</div>';
						echo '</div>';

						/**
						 * Remove meta-generator-tag from HTML header.
						 */
						echo '<div class="greyopaque-settings-line-wrapper_remove-generator-metatag" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Remove Generator-Tag', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-options[remove-generator-metatag]" ' . $var_sRemoveGeneratorTag . ' />';
						echo '</div>';
						echo '</div>';
						?>
					</div>
					<!-- /Themeoptions -->

					<!-- Authorbox and adminbar -->
					<div class="themeoptions" style="width:33%; float:left;">
						<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Authorbox', 'grey-opaque'); ?></h3>
						<?php
						global $authordata;

						$var_sShowAuthorbox = (isset($array_ThemeOptions['greyopaque-authorbox']['show-authorbox'])) ? checked($array_ThemeOptions['greyopaque-authorbox']['show-authorbox'], 'on', 0) : false;
						$var_sDisplayShowAuthorboxProfiles = (isset($array_ThemeOptions['greyopaque-authorbox']['show-authorbox'])) ? 'block' : 'none';
						$var_sShowProfilesInAuthorbox = (isset($array_ThemeOptions['greyopaque-authorbox']['show-profiles-in-authorbox'])) ? checked($array_ThemeOptions['greyopaque-authorbox']['show-profiles-in-authorbox'], 'on', 0) : false;
						$var_sShowMailProfileInAuthorbox = (isset($array_ThemeOptions['greyopaque-authorbox']['show-mailprofile-in-authorbox'])) ? checked($array_ThemeOptions['greyopaque-authorbox']['show-mailprofile-in-authorbox'], 'on', 0) : false;

						/**
						 * Show Authorbox.
						 */
						echo '<div class="greyopaque-settings-line-wrapper_show-authorbox" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Show Authorbox', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-authorbox[show-authorbox]" ' . $var_sShowAuthorbox . ' onchange="toggleVisibility(\'show-authorbox-profiles\')" />';
						echo '</div>';
						echo '</div>';

						/**
						 * We have "About You"?.
						 */
						if(!wp_get_current_user()->description && $var_sShowAuthorbox) {
							$var_sProfileLink = 'profile.php';

							echo '<div class="greyopaque-settings-line-wrapper_show-authorbox" style="clear:both;">';
							echo '<div style="width:240px; -moz-border-radius:10px; -webkit-border-radius:10px; margin:0 0 10px; -moz-box-shadow:3px 3px 11px 1px rgba(0, 0, 0, 0.1); -webkit-box-shadow:3px 3px 11px 1px rgba(0, 0, 0, 0.1); border:1px solid #E09B85; color:#957368;-moz-border-radius:10px 10px 10px 10px; min-height:35px; padding:5px; text-shadow:1px 1px 1px #FFFFFF;">';
							echo sprintf(__('There is no Information given in "About You" on your %1$s. The authorbox will not be shown until there is any information.', 'grey-opaque'),
									'<a href="' . $var_sProfileLink . '">' . __('Profile-Settings', 'grey-opaque') . '</a>'
								);
							echo '</div>';
							echo '</div>';
						}

						echo '<div id="show-authorbox-profiles" style="display:' . $var_sDisplayShowAuthorboxProfiles . '">';
						/**
						 * Show Profiles in Authorbox.
						 */
						echo '<div class="greyopaque-settings-line-wrapper_show-profiles-in-authorbox" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Show Profiles in Authorbox', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-authorbox[show-profiles-in-authorbox]" ' . $var_sShowProfilesInAuthorbox . ' />';
						echo '</div>';
						echo '</div>';

						/**
						 * Show Mailprofileicon in Authorbox.
						 * Seperated, because it is MAIL !!!
						 */
						echo '<div class="greyopaque-settings-line-wrapper_show-mailprofile-in-authorbox" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Show Mailicon in Authorbox', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-authorbox[show-mailprofile-in-authorbox]" ' . $var_sShowMailProfileInAuthorbox . ' />';
						echo '</div>';
						echo '</div>';

						/**
						 * Warning if mailicon shoul'd be displayed.
						 */
						if($var_sShowMailProfileInAuthorbox) {
							echo '<div class="greyopaque-settings-line-wrapper_show-authorbox" style="clear:both;">';
							echo '<div style="width:240px; -moz-border-radius:10px; -webkit-border-radius:10px; margin:0; -moz-box-shadow:3px 3px 11px 1px rgba(0, 0, 0, 0.1); -webkit-box-shadow:3px 3px 11px 1px rgba(0, 0, 0, 0.1); border:1px solid #E09B85; color:#957368;-moz-border-radius:10px 10px 10px 10px; min-height:35px; padding:5px; text-shadow:1px 1px 1px #FFFFFF;">';
							echo __('You are displaying the mailicons. The mailadresses will be scrambled in HTML-output, but it is not a 100% security for mailspam.', 'grey-opaque');
							echo '</div>';
							echo '</div>';
						}

						echo '</div> <!-- /Show Authorbox Profiles -->';

						/**
						 * Admin Bar Bottom.
						 *
						 * @deprecated since Grey Opaque 1.4 and WordPress 3.3 (because it doesn't work in WP 3.3)
						 */
						if((version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.1', '>=')) && (version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.3', '<'))) {
							?>
							<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393; padding-top:15px;"><?php _e('Admin Bar', 'grey-opaque'); ?></h3>
							<?php
							$array_AdminBarSettings = greyopaque_get_theme_options('greyopaque-adminbar');

							if(isset($array_AdminBarSettings['move-to-bottom'])) {
								$var_sAdminbarBottom = checked($array_AdminBarSettings['move-to-bottom'], 'on', 0);
								$var_sAdminBarChoiceStyle = 'block;';
							} else {
								$var_sAdminbarBottom ='';
								$var_sAdminBarChoiceStyle = 'none;';
							}

							$var_sAdminbarBottomFrontend = (isset($array_AdminBarSettings['in-frontend'])) ? checked($array_AdminBarSettings['in-frontend'], 'on', 0) : false;
							$var_sAdminbarBottomBackend = (isset($array_AdminBarSettings['in-backend'])) ? checked($array_AdminBarSettings['in-backend'], 'on', 0) : false;

							echo '<div class="greyopaque-settings-line-wrapper_adminbar-bottom" style="clear:both;">';
							echo '<div style="width:250px; float:left;">';
							echo __('Move Admin Bar to Bottom', 'grey-opaque');
							echo '</div>';
							echo '<div style="float:left;">';
							echo '<input type="checkbox" name="greyopaque-adminbar[move-to-bottom]" ' . $var_sAdminbarBottom . ' onchange="toggleVisibility(\'admin-bar-choice\')" />';
							echo '</div>';
							echo '</div>';

							echo '<div id="admin-bar-choice" style="display:' . $var_sAdminBarChoiceStyle . '">';
							/**
							 * Admin Bar Bottom.
							 * Frontend
							 */
							echo '<div class="greyopaque-settings-line-wrapper_adminbar-bottom-in-frontend" style="clear:both;">';
							echo '<div style="width:250px; float:left;">';
							echo __('Frontend', 'grey-opaque');
							echo '</div>';
							echo '<div style="float:left;">';
							echo '<input type="checkbox" name="greyopaque-adminbar[in-frontend]" ' . $var_sAdminbarBottomFrontend . ' />';
							echo '</div>';
							echo '</div>';

							/**
							 * Admin Bar Bottom.
							 * Backend
							 */
							echo '<div class="greyopaque-settings-line-wrapper_adminbar-bottom-in-backend" style="clear:both;">';
							echo '<div style="width:250px; float:left;">';
							echo __('Backend', 'grey-opaque');
							echo '</div>';
							echo '<div style="float:left;">';
							echo '<input type="checkbox" name="greyopaque-adminbar[in-backend]" ' . $var_sAdminbarBottomBackend . ' />';
							echo '</div>';
							echo '</div>';
							echo '</div>';
						}
						?>

					</div>
					<!-- /Authorbox and Adminbar -->

					<!-- Smilies -->
					<?php
					$array_SmilieOptions = greyopaque_get_theme_options('greyopaque-smilies');
					?>
					<div class="themeoptions" style="width:33%; float:left;">
						<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Smilies', 'grey-opaque'); ?></h3>
						<?php
						/*
						 * Show Smilies.
						 */
						if(isset($array_SmilieOptions['show-smilies'])) {
							$var_sShowSmilies = checked($array_SmilieOptions['show-smilies'], 'on', 0);
							$var_sDisplaySmiliesInStyle = 'block';
						} else {
							$var_sShowSmilies = '';
							$var_sDisplaySmiliesInStyle = 'none';
						}

						$var_sSmiliesInContent = (isset($array_SmilieOptions['replace-in-content'])) ? checked($array_SmilieOptions['replace-in-content'], 'on', 0) : false;
						$var_sSmiliesInComments = (isset($array_SmilieOptions['replace-in-comments'])) ? checked($array_SmilieOptions['replace-in-comments'], 'on', 0) : false;

						echo '<div class="greyopaque-settings-line-wrapper_smilies" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('Show Smilies', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-smilies[show-smilies]" ' . $var_sShowSmilies . ' onchange="toggleVisibility(\'show-smilies-in\')" />';
						echo '</div>';
						echo '</div>';

						/**
						 * Show Smilies in ...
						 */
						echo '<div id="show-smilies-in" style="display:' . $var_sDisplaySmiliesInStyle . ';">';
						// ... Content.
						echo '<div class="greyopaque-settings-line-wrapper_smilies" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('In content', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-smilies[replace-in-content]" ' . $var_sSmiliesInContent . ' />';
						echo '</div>';
						echo '</div>';

						// ... Comments.
						echo '<div class="greyopaque-settings-line-wrapper_show-authorbox" style="clear:both;">';
						echo '<div style="width:250px; float:left;">';
						echo __('In comments', 'grey-opaque');
						echo '</div>';
						echo '<div style="float:left;">';
						echo '<input type="checkbox" name="greyopaque-smilies[replace-in-comments]" ' . $var_sSmiliesInComments . ' />';
						echo '</div>';
						echo '</div>';
						echo '</div> <!-- /Show Smilies in -->';
					?>
					</div>
					<!-- /Smilies -->
				</div>

				<div class="themeconfig-wrapper" style="clear:both;">
					<!-- Favicon -->
					<div class="themeoptions" style="width:33%; float:left;">
						<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Favicon', 'grey-opaque'); ?></h3>
						<?php
						$var_sFaveIconLink = (isset($array_ThemeOptions['greyopaque-options']['favicon-link'])) ? $array_ThemeOptions['greyopaque-options']['favicon-link'] : '' ;

						echo '<div class="greyopaque-settings-line-wrapper_favicon" style="width:250px;clear:both;">';
						echo '<div>';
						echo __('Enter your favicons full url. Leave empty to unset.', 'grey-opaque');
						echo '</div>';
						echo '<div>';
						echo '<input type="text" name="greyopaque-options[favicon-link]" value="' . $var_sFaveIconLink . '" />';
						echo '</div>';
						echo '</div>';
						?>
					</div>
					<!-- /Favicon -->

					<!-- Commentform Text before -->
					<div class="themeoptions" style="width:33%; float:left;">
						<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Commenters guideline', 'grey-opaque'); ?></h3>
						<?php
						$var_sCommentformTextBefore = (isset($array_ThemeOptions['greyopaque-options']['commentform-text-before'])) ? stripslashes(wp_filter_post_kses($array_ThemeOptions['greyopaque-options']['commentform-text-before'])) : '';

						echo '<div class="greyopaque-settings-line-wrapper_commentform-text-before" style="width:250px;clear:both;">';
						echo '<div>';
						echo __('Here you can enter a little guideline for your commenters.', 'grey-opaque');
						echo '</div>';
						echo '<div>';
						echo '<textarea name="greyopaque-options[commentform-text-before]" style="width:250px; height:200px;">' . esc_textarea($var_sCommentformTextBefore) . '</textarea>';
						echo '</div>';
						echo '</div>';
						?>
					</div>
					<!-- /Commentform Text before -->

					<!-- Support the Developer -->
					<div class="themeoptions" style="width:33%; float:left;">
						<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Support the developer :-)', 'grey-opaque'); ?></h3>
						<p>
							<?php _e('This Theme ist completely free without any limitations. But, if you like my work, so please suppport it with a little click.', 'grey-opaque'); ?>
							<br />
							<!-- Flattr -->
							<a href="<?php echo GREY_OPAQUE_DONATE_LINK_FLATTR; ?>" target="_blank">
								<img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" />
							</a>
							<!-- Paypal -->
							<a href="<?php echo GREY_OPAQUE_DONATE_LINK_PAYPAL; ?>" target="_blank">
								<img src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online." title="Donate via PayPal" />
								<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/de_DE/i/scr/pixel.gif" width="1" height="1" />
							</a>
						</p>

						<p>
							<?php
							printf(__('If you have any ideas, suggestions, bugs or whatever, so feel free to write me a <a href="%1$s">%2$s</a>.', 'grey-opaque'),
								GREY_OPAQUE_THEME_URI,
								__('message', 'grey-opaque')
							);
							?>
						</p>

						<p>
							<?php _e('Thanks and greetings.', 'grey-opaque'); ?>
						</p>
					</div>
					<!-- /Support the Developer -->
				</div>
				<div style="clear:both;"></div>
				<p class="submit">
					<input name="submitted" type="hidden" value="yes" />
					<input type="submit" name="Submit" value="<?php _e('Update', 'grey-opaque'); ?> &raquo;" />
				</p>
			</form>
		</div>
		<?php
	}
}

/**
 * Updating options.
 *
 * @since Grey Opaque 1.0.3.5
 */
if(!function_exists('greyopaque_update_options')) {
	function greyopaque_update_options() {
			$array_OptionsGreyOpaqueTheme = greyopaque_sanitize_options();

			if(is_array($array_OptionsGreyOpaqueTheme)) {
				// Removing non needed data from options array.
				unset($array_OptionsGreyOpaqueTheme['submitted']);
				unset($array_OptionsGreyOpaqueTheme['Submit']);
				unset($array_OptionsGreyOpaqueTheme['page']);
				unset($array_OptionsGreyOpaqueTheme['_wpnonce']);
				unset($array_OptionsGreyOpaqueTheme['_wp_http_referer']);

				// Update options.
				update_option("greyopaque_theme_options", $array_OptionsGreyOpaqueTheme);
			}
	}
}

/**
 * Filtering options.
 *
 * @since Grey Opaque 1.0.3.5
 */
if(!function_exists('greyopaque_sanitize_options')) {
	function greyopaque_sanitize_options() {
		/**
		 * Check if we have the recommend php-version at least.
		 * It is needed by filter_input_array(), otherwise work with
		 * $_REQUEST to prevent blank screen after saving settings.
		 *
		 * @since Grey Opaque 1.3.0
		 */
		if(version_compare(GREY_OPAQUE_PHP_VERSION_RECOMMEND, GREY_OPAQUE_PHP_VERSION_RUNNING, '>')) {
			$array_Options = $_REQUEST;
		} else {
			$array_Options = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
		}

		return $array_Options;
	}
}

/**
 * Adding to dashboardmenu:
 *	Theme-Settings
 *	Theme-Documentation
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_theme_options')) {
	function greyopaque_theme_options() {
		get_template_part('documentation');

		$var_sSettingsLink = basename(__FILE__, '.php');
		$var_sDocumentationLink = basename(get_template_directory() . '/documentation.php', '.php');

		add_theme_page(__('Settings for Theme Grey Opaque', 'grey-opaque'), __('Settings', 'grey-opaque'), 'edit_theme_options', $var_sSettingsLink, 'greyopaque_theme_admin');
		add_theme_page(__('Documentation for Theme Grey Opaque', 'grey-opaque'), __('Documentation', 'grey-opaque'), 'edit_theme_options', $var_sDocumentationLink, 'greyopaque_theme_documentation');

// Coming soon
//		$var_sDocumentation = greyopaque_theme_documentation();
//		add_contextual_help('appearance_page_functions', $var_sDocumentation);
	}

	if(current_user_can('edit_theme_options')) {
		add_action('admin_menu', 'greyopaque_theme_options');
	}
}

/**
 * Splitting themes options.
 *
 * Array(									// $array_GreyOpaqueThemeSettings
 * 		[greyopaque-options] => Array(					// $array_GreyOpaqueOptions
 * 			[show-branding] => on
 * 			[show-headerimage] => on
 * 			[show-cursor] => on
 * 			[show-hovercards] => on
 * 			[get-postthumbnail-for-facebook] => on
 * 			[deactivate-hyperlinks-in-comments] => on
 * 			[remove-generator-metatag] => on
 * 			[favicon-link] =>
 * 			[commentform-text-before] =>
 * 		)
 * 		[greyopaque-authorbox] => Array(				// $array_GreyOpaqueAuthorbox
 * 			[show-authorbox] => on
 * 			[show-profiles-in-authorbox] => on
 * 		)
 * 		[greyopaque-adminbar] => Array(					// $array_GreyOpaqueAdminbar
 * 			[move-to-bottom] => on
 * 			[in-frontend] => on
 * 		)
 * 		[greyopaque-smilies] => Array(					// $array_GreyOpaqueSmilies
 * 			[show-smilies] => on
 * 			[replace-in-content] => on
 * 			[replace-in-comments] => on
 * 		)
 * 	)
 *
 * @since Grey Opaque 1.0.1
 */
$array_GreyOpaqueThemeSettings = greyopaque_get_theme_options();
$array_GreyOpaqueOptions = (isset($array_GreyOpaqueThemeSettings['greyopaque-options'])) ? $array_GreyOpaqueThemeSettings['greyopaque-options'] : '';
$array_GreyOpaqueAuthorbox = (isset($array_GreyOpaqueThemeSettings['greyopaque-authorbox'])) ? $array_GreyOpaqueThemeSettings['greyopaque-authorbox'] : '';;
$array_GreyOpaqueAdminbar = (isset($array_GreyOpaqueThemeSettings['greyopaque-adminbar'])) ? $array_GreyOpaqueThemeSettings['greyopaque-adminbar'] : '';
$array_GreyOpaqueSmilies = (isset($array_GreyOpaqueThemeSettings['greyopaque-smilies'])) ? $array_GreyOpaqueThemeSettings['greyopaque-smilies'] : '';

/**
 * Working with: $array_GreyOpaqueOptions
 */
/**
 * Branding..
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_set_branding')) {
	function greyopaque_set_branding() {
		$heading_tag = (is_home() || is_front_page()) ? 'h1' : 'div';

		echo '
		<div id="branding" role="banner">
			<' . $heading_tag . ' id="site-title">
				<span>
					<a href="' . home_url('/') . '" title="' . esc_attr(get_bloginfo('name', 'display')) . '" rel="home">' . get_bloginfo('name') . '</a>
				</span>
			</' . $heading_tag . '>
			<div id="site-description">' . get_bloginfo('description') . '</div>
		</div><!-- #branding -->';
	}

	if(isset($array_GreyOpaqueOptions['show-branding']) && $array_GreyOpaqueOptions['show-branding'] == 'on') {
		add_action('greyopaque_header', 'greyopaque_set_branding');
	}
}

/**
 * Headerimage.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_set_headerimage')) {
	function greyopaque_set_headerimage() {
		global $post;

		// Check if this is a post or page, if it has a thumbnail, and if it's a big one
// 		if(is_singular() && current_theme_supports('post-thumbnails') && has_post_thumbnail($post->ID) && ($image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'post-thumbnail')) && $image[1] >= HEADER_IMAGE_WIDTH) {
// 			// Houston, we have a new header image!
// 			echo get_the_post_thumbnail($post->ID);
// 		} elseif(get_header_image()) {
			echo '<img src="' . get_header_image() . '" width="' . HEADER_IMAGE_WIDTH . '" height="' . HEADER_IMAGE_HEIGHT . '" alt="" />';
// 		}
	}

	if(isset($array_GreyOpaqueOptions['show-headerimage']) && $array_GreyOpaqueOptions['show-headerimage'] == 'on') {
		add_action('greyopaque_header', 'greyopaque_set_headerimage');
	}
}

/**
 * Plugin Name: CSS-Cursor for Wordpress
 * Plugin URI: http://blog.ppfeufer.de/wordpress-eigene-cursor-im-blog-verwenden/
 * Description: Cursors for Wordpress.
 * Version: 1.0.0
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 * License: Free
 *
 * @since Grey Opaque 1.0.0
 */
if(!is_admin()) {
	if(isset($array_GreyOpaqueOptions['show-cursor']) && $array_GreyOpaqueOptions['show-cursor'] == 'on') {
		$var_sCSSCursorCSSUrl = get_template_directory_uri() . '/css-cursor.css';

		/**
		 * CSS.
		 */
		wp_register_style('css-cursor-for-wordpress', $var_sCSSCursorCSSUrl, array(), GREY_OPAQUE_THEME_VERSION, 'screen');
		wp_enqueue_style('css-cursor-for-wordpress');
	}
}

/**
 * Plugin Name: Gravatar Hovercards
 * Plugin URI:
 * Description: Displays a gravatar hovercard.
 * Version: 1.0
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_gravatar_hovercards_jquery')) {
	function greyopaque_gravatar_hovercards_jquery() {
		wp_enqueue_script('jquery');
	}
}

if(!function_exists('greyopaque_gravatar_hovercards')) {
	function greyopaque_gravatar_hovercards() {
		echo '<script type="text/javascript" src="http://s.gravatar.com/js/gprofiles.js"></script>';
	}
}

/**
 * Postthumbnail for Facebook.
 *
 * @author Sergej Müller (modified by: H.-Peter Pfeufer)
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_fb_like_thumbnails')) {
	function greyopaque_fb_like_thumbnails() {
		/* Nur Frontend */
		if(is_feed() || is_trackback() || !is_singular()) {
			return;
		}

		/* Source */
		$array_Image = wp_get_attachment_image_src(get_post_thumbnail_id($GLOBALS['post']->ID));

		if(is_array($array_Image)) {
			$var_sFaceBookThumbnail = $array_Image['0'];
		} else {
			$var_sDefaultThumbnail = get_template_directory_uri() . '';
			$var_sOutput = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $GLOBALS['post']->post_content, $array_Matches);

			if($var_sOutput > 0) {
				$var_sFaceBookThumbnail = $array_Matches[1][0];
			} else {
				return;
			}
		}

		/* Return */
		echo "\n" . '<!-- Facebook Like Thumbnail -->' . "\n";
		echo sprintf('<link href="%s" rel="image_src" />%s',
				esc_attr($var_sFaceBookThumbnail),
				"\n"
			);

		/**
		 * Open:Graph-Tags for FB-Like
		 */
// Commented out, because OpenGraph ist not valid yet
//		echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>' . "\n";
//		echo '<meta property="og:title" content="' . get_the_title() . '"/>' . "\n";
//		echo '<meta property="og:url" content="' . get_permalink() . '"/>' . "\n";
//		echo '<meta property="og:image" content="' . esc_attr($var_sFaceBookThumbnail) . '"/>' . "\n";
//		echo '<meta property="og:description" content="' . get_the_excerpt() . '"/>' . "\n";
		echo '<!-- Facebook Like Thumbnail -->' . "\n";
	}

	if(isset($array_GreyOpaqueOptions['get-postthumbnail-for-facebook']) && $array_GreyOpaqueOptions['get-postthumbnail-for-facebook'] == 'on') {
		add_action('wp_head', 'greyopaque_fb_like_thumbnails');
	}
}

/**
 * Deactivate hyperlinks in comments.
 *
 * @since Grey Opaque 1.0.0
 */
if(isset($array_GreyOpaqueOptions['deactivate-hyperlinks-in-comments']) && $array_GreyOpaqueOptions['deactivate-hyperlinks-in-comments'] == 'on') {
	remove_filter('comment_text', 'make_clickable', 9);
}

/**
 * Removing meta-generator-tag from HTML-header.
 * <meta name="generator" content="WordPress 3.2-bleeding" />
 *
 * @author Sergej Müller
 * @since Grey Opaque 1.0.1
 */
if(!function_exists('greyopaque_remove_wp_generator')) {
	function greyopaque_remove_wp_generator() {
		if(is_admin() || is_feed()) {
			return;
		}

		if(function_exists('the_generator')) {
			add_filter('the_generator', create_function('$x', 'return;'));
		}
	}
}

if(isset($array_GreyOpaqueOptions['remove-generator-metatag']) && $array_GreyOpaqueOptions['remove-generator-metatag'] == 'on') {
	add_action('init', 'greyopaque_remove_wp_generator');
}

/**
 * JavaScript for Textarea Autoresize.
 *
 * @since Grey Opaque 1.3.0
 */
if(!function_exists('greyopaque_textarea_autoresize')) {
	function greyopaque_textarea_autoresize() {
		echo '<script type="text/javascript" src="' . get_template_directory_uri() . '/javascript/jquery-textarea-autoresize.js"></script>';
		echo '<script type="text/javascript">
		/* <![CDATA[ */
		jQuery(\'textarea#comment\').autoResize({
			// On resize:
			onResize : function() {
				jQuery(this).css({opacity:0.8});
			},
			// After resize:
			animateCallback : function() {
				jQuery(this).css({opacity:1});
			},
			// Quite slow animation:
			animateDuration : 300,
			// More extra space:
			extraSpace : 20
		}).trigger(\'change\');
		/* ]]> */
		</script>';
	}

	wp_enqueue_script('jquery');
	add_action('wp_footer', 'greyopaque_textarea_autoresize');
}

/**
 * Adding Favicon.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_set_favicon')) {
	function greyopaque_set_favicon($var_sFavicon = '') {
		echo '<link rel="shortcut icon" href="' . $var_sFavicon . '" />';
	}

	if(isset($array_GreyOpaqueOptions['favicon-link']) && $array_GreyOpaqueOptions['favicon-link'] != '') {
		add_action('wp_head', create_function('', 'return greyopaque_set_favicon("' . $array_GreyOpaqueOptions['favicon-link'] . '");'));
	}
}

/**
 * Adding text before commentform.
 *
 * @since Grey Opaque 1.0.1
 */
if(!function_exists('greyopaque_set_commentform_text_before')) {
	function greyopaque_set_commentform_text_before($var_sText = '') {
		echo '<div class="commentform-text-before">' . wpautop($var_sText) . '</div>';
	}

	if(isset($array_GreyOpaqueOptions['commentform-text-before']) && $array_GreyOpaqueOptions['commentform-text-before'] != '') {
		add_action('comment_form_before_fields', create_function('', 'return greyopaque_set_commentform_text_before("' . (string) wp_filter_post_kses($array_GreyOpaqueOptions['commentform-text-before']) . '");'));
	}
}

/**
 * Working with: $array_GreyOpaqueAuthorbox
 */
/**
 * Authorbox.
 *
 * @uses greyopaque_scramble_email();
 * @uses get_the_author_meta();
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_the_authorbox')) {
	function greyopaque_the_authorbox() {
		/**
		 * Per 'global' klappt das hier leider nicht :-(
		 */
		$array_GreyOpaqueAuthorbox = greyopaque_get_theme_options('greyopaque-authorbox');
		$var_sAuthorBox = '';

		/**
		 * Show only if:
		 * => Post > is_single();
		 * => Authors page > is_author();
		 */
		if(is_single() || is_author()) {
			if(get_the_author_meta('description')) {
				if(isset($array_GreyOpaqueAuthorbox['show-authorbox']) && $array_GreyOpaqueAuthorbox['show-authorbox'] == 'on') {
					$var_sAuthorBox = '<div id="entry-author-info-wrapper">' . "\n";
					$var_sAuthorBox .= '<div id="entry-author-info">' . "\n";
					$var_sAuthorBox .= '<div id="author-profile-wrapper">' . "\n";
					$var_sAuthorBox .= '<div id="author-avatar">' . "\n";
					$var_sAuthorBox .= get_avatar(get_the_author_meta('user_email'), apply_filters('greyopaque_author_bio_avatar_size', 60)) . "\n";
					$var_sAuthorBox .= '</div>' . "\n";

					/**
					 * Getting profiles.
					 */
					if(isset($array_GreyOpaqueAuthorbox['show-profiles-in-authorbox']) && $array_GreyOpaqueAuthorbox['show-profiles-in-authorbox'] == 'on') {
						$var_sAuthorBox .= '<div id="author-profiles">' . "\n";
						$var_sAuthorBox .= '<ul class="author-profile-services">' . "\n";
						/**
						 * Webseite
						 */
						if(get_the_author_meta('user_url')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-website" href="' . get_the_author_meta('user_url') . '" title="' . __('Take a look to my website', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * RSS
						 */
						if(get_the_author_meta('rss')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-rss" href="' . get_the_author_meta('rss') . '" title="' . __('Get my RSS-Feed', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * E-Mail
						 * Only if in themes settings enabled.
						 */
						if(isset($array_GreyOpaqueAuthorbox['show-mailprofile-in-authorbox']) && $array_GreyOpaqueAuthorbox['show-mailprofile-in-authorbox'] == 'on') {
							if(get_the_author_meta('user_email')) {
								$var_sAuthorBox .= '<li>' . "\n";
								$var_sAuthorBox .= '<a class="account-email" href="mailto:' . greyopaque_scramble_email(get_the_author_meta('user_email')) . '" title="' . __('Write me a mail', 'grey-opaque') . '"></a>' . "\n";
								$var_sAuthorBox .= '</li>' . "\n";
							}
						}

						/**
						 * Twitter.
						 */
						if(get_the_author_meta('twitter')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-twitter" href="http://twitter.com/#!/' . greyopaque_sanitize_twittername(get_the_author_meta('twitter') . '" title="' . __('Follow me on Twitter', 'grey-opaque')) . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * Google+.
						 */
						if(get_the_author_meta('googleplus')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-googleplus" href="' . get_the_author_meta('googleplus') . '" title="' . __('Find me on Google+', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * Facebook.
						 */
						if(get_the_author_meta('facebook')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-facebook" href="' . get_the_author_meta('facebook') . '" title="' . __('Find me on Facebook', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * wordpress.org profile.
						 */
						if(get_the_author_meta('wordpress')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-wordpress" href="' . get_the_author_meta('wordpress') . '" title="' . __('See my WordPress-Plugins', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * Xing.
						 */
						if(get_the_author_meta('xing')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-xing" href="' . get_the_author_meta('xing') . '" title="' . __('Find me on XING', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * Youtube.
						 */
						if(get_the_author_meta('youtube')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-youtube" href="' . get_the_author_meta('youtube') . '" title="' . __('Find me on Youtube', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * Delicious.
						 */
						if(get_the_author_meta('delicious')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-delicious" href="' . get_the_author_meta('delicious') . '" title="' . __('Find me on Delicious', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						/**
						 * LinkedIn.
						 */
						if(get_the_author_meta('linkedin')) {
							$var_sAuthorBox .= '<li>' . "\n";
							$var_sAuthorBox .= '<a class="account-linkedin" href="' . get_the_author_meta('linkedin') . '" title="' . __('Find me on LinkedIn', 'grey-opaque') . '"></a>' . "\n";
							$var_sAuthorBox .= '</li>' . "\n";
						}

						$var_sAuthorBox .= '</ul>' . "\n";
						$var_sAuthorBox .= '</div>' . "\n";
					} // END if(isset($array_GreyOpaqueAuthorbox['show-profiles-in-authorbox']) && $array_GreyOpaqueAuthorbox['show-profiles-in-authorbox'] == 'on')

					$var_sAuthorBox .= '</div>' . "\n";
					$var_sAuthorBox .= '<div id="author-description">' . "\n";
					$var_sAuthorBox .= '<h2>' . sprintf(esc_attr__('About %s', 'grey-opaque'), get_the_author()) . '</h2>' . "\n";
					$var_sAuthorBox .= get_the_author_meta('description') . "\n";

					/**
					 * Only if we are NOT on an authors-page.
					 */
					if(!is_author()) {
						$var_sAuthorBox .= '<div id="author-link">' . "\n";
						$var_sAuthorBox .= '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . sprintf(__('View all posts by %s <span class="meta-nav">&rarr;</span>', 'grey-opaque'), get_the_author()) . '</a>' . "\n";
						$var_sAuthorBox .= '</div>' . "\n";
					}

					$var_sAuthorBox .= '</div>' . "\n";
					$var_sAuthorBox .= '</div>' . "\n";
					$var_sAuthorBox .= '</div>' . "\n";
					$var_sAuthorBox .= '<p class="greyopaque-clear-after-content"></p>' . "\n";
				} // END if($array_GreyOpaqueAuthorbox['show-authorbox'] == 'on')
			} // END if(get_the_author_meta('description'))
		} else {
			$var_sAuthorBox = '<p class="greyopaque-clear-after-content"></p>';
		} // END if(is_single() || is_author())

		return $var_sAuthorBox;
	} // END function()
}

/**
 * Working with: $array_GreyOpaqueSmilies
 */
/**
 * Defining Smilies.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_get_smilies_array')) {
	function greyopaque_get_smilies_array() {
		$array_Smilies = array(
			':)'			=> 'icon_smile.gif',
			':-)'			=> 'icon_smile.gif',
			':smile:'		=> 'icon_smile.gif',
			':D'			=> 'icon_biggrin.gif',
			':-D'			=> 'icon_biggrin.gif',
			':grin:'		=> 'icon_biggrin.gif',
			':('			=> 'icon_sad.gif',
			':-('			=> 'icon_sad.gif',
			':sad:'			=> 'icon_sad.gif',
			'8)'			=> 'icon_cool.gif',
			'8-)'			=> 'icon_cool.gif',
			':cool:'		=> 'icon_cool.gif',
			':x'			=> 'icon_mad.gif',
			':-x'			=> 'icon_mad.gif',
			':mad:'			=> 'icon_mad.gif',
			':P'			=> 'icon_razz.gif',
			':-P'			=> 'icon_razz.gif',
			':razz:'		=> 'icon_razz.gif',
			':|'			=> 'icon_neutral.gif',
			':-|'			=> 'icon_neutral.gif',
			':neutral:'		=> 'icon_neutral.gif',
			';)'			=> 'icon_wink.gif',
			';-)'			=> 'icon_wink.gif',
			':wink:'		=> 'icon_wink.gif',
			':lol:'			=> 'icon_lol.gif',
			':cry:'			=> 'icon_cry.gif',
			':evil:'		=> 'icon_evil.gif',
			':twisted:'		=> 'icon_twisted.gif',
			':roll:'		=> 'icon_rolleyes.gif',
			':!:'			=> 'icon_exclaim.gif',
			':idea:'		=> 'icon_idea.gif',
			':arrow:'		=> 'icon_arrow.gif',
			':?:'			=> 'icon_question.gif',
			':-o'			=> 'icon_surprised.gif',
			':oops:'		=> 'icon_redface.gif',
			':???:'			=> 'icon_confused.gif',
			':-?'			=> 'icon_confused.gif',
			'8O'			=> 'icon_eek.gif',
			'8-O'			=> 'icon_eek.gif',
			':eek:'			=> 'icon_eek.gif',
			':shock:'		=> 'icon_eek.gif',
			':mrgreen:'		=> 'icon_mrgreen.gif'
		);

		return $array_Smilies;
	}
}

/**
 * This function will return the smilie-url within the theme.
 *
 * @return string Smilie-URL with trailing /
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_get_smilie_url')) {
	function greyopaque_get_smilie_url() {
		return get_template_directory_uri() . '/images/smilies/';
	}
}

/**
 * Replace smilies in content.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_smilies_content')) {
	function greyopaque_smilies_content($var_sContent) {
		$array_Smilies = greyopaque_get_smilies_array();

		foreach($array_Smilies as $key => $value) {
			$var_sContent = str_replace($key, '<img src="' . greyopaque_get_smilie_url() . $value . '" alt="Smilie: ' . $key . '" title="Smilie: ' . $key . '" />', $var_sContent);
		}

		return $var_sContent;
	}
}

/**
 * Replace smilies in comments.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_smilies_comments')) {
	function greyopaque_smilies_comments($var_sComment) {
		$array_Smilies = greyopaque_get_smilies_array();

		foreach($array_Smilies as $key => $value) {
			$var_sComment = str_replace($key, '<img src="' . greyopaque_get_smilie_url() . $value . '" alt="Smilie: ' . $key . '" title="Smilie: ' . $key . '" />', $var_sComment);
		}

		return $var_sComment;
	}
}

/**
 * Filter for smilies.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_smiley_filters')) {
	function greyopaque_smiley_filters() {
		global $array_GreyOpaqueSmilies;
		update_option('use_smilies', 0);

		if(isset($array_GreyOpaqueSmilies['show-smilies']) && $array_GreyOpaqueSmilies['show-smilies'] == 'on') {
			if(isset($array_GreyOpaqueSmilies['replace-in-content']) && $array_GreyOpaqueSmilies['replace-in-content'] == 'on') {
				add_filter('the_content', 'greyopaque_smilies_content');
			}

			if(isset($array_GreyOpaqueSmilies['replace-in-comments']) && $array_GreyOpaqueSmilies['replace-in-comments'] == 'on') {
				add_filter('comment_text', 'greyopaque_smilies_comments');
			}
		}
	}
}

/**
 * Running filters.
 *
 * @since Grey Opaque 1.0.0
 */
if(function_exists('greyopaque_smiley_filters')) {
	greyopaque_smiley_filters();
}

/**
 * Unsetting variables.
 */
unset($array_GreyOpaqueThemeSettings);
unset($array_GreyOpaqueOptions);
unset($array_GreyOpaqueAuthorbox);
unset($array_GreyOpaqueAdminbar);
unset($array_GreyOpaqueSmilies);

/**
 * Themehooks bedienen.
 *
 * An dieser Stelle werden die themeeigenen Hooks angesprochen.
 * Diese gehören nicht zur Grundausstattung von WordPress und sind
 * nur in diesem Theme so vorzufinden.
 *
 * Folgende Hooks können angesprochen werden:
 * header.php
 * 		greyopaque_header
 * footer.php
 * 		greyopaque_footer_copyright
 * 		greyopaque_footer_statistics
 * 		greyopaque_theme_debug
 */
/**
 * Headernavigation.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_set_headernavigation')) {
	function greyopaque_set_headernavigation() {
		echo '<div id="access" role="navigation">';

		/**
		 * Allow screen readers / text browsers to skip the navigation menu
		 * and get right to the good stuff
		 */
		echo '<div class="skip-link screen-reader-text"><a href="#content" title="' . esc_attr__('Skip to content', 'grey-opaque') . '">' . __('Skip to content', 'grey-opaque') . '</a></div>';

		/**
		 * Our navigation menu.
		 * If one isn't filled out, wp_nav_menu falls back to wp_page_menu.
		 * The menu assiged to the primary position is the one used.
		 * If none is assigned, the menu with the lowest ID is used.
		 */
		wp_nav_menu(array(
			'container_class' => 'menu-header',
			'theme_location' => 'primary'
		));

		echo '</div> <!-- #access -->';
	}

	add_action('greyopaque_header', 'greyopaque_set_headernavigation');
}

/**
 * Copyright (Website).
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_blog_copyright')) {
	function greyopaque_blog_copyright () {
		global $wpdb;

		/**
		 * Date from first post.
		 *
		 * @since Grey Opaque 1.0.1
		 */
		$obj_PostDatetimes = $wpdb->get_row($wpdb->prepare("SELECT YEAR(min(post_date_gmt)) AS firstyear FROM $wpdb->posts WHERE post_date_gmt > 1970 AND post_status = 'publish';"));
		if ($obj_PostDatetimes) {
			$var_sYearFirstPost = $obj_PostDatetimes->firstyear;
		}

		if($var_sYearFirstPost == date('Y', time())) {
			$var_sCopyrightYear = $var_sYearFirstPost;
		} else {
			$var_sCopyrightYear = $var_sYearFirstPost . ' - ' . date('Y', time());
		}

		$var_sHTML = sprintf('<span class="footer-notice"><a href="%1$s" title="%2$s" rel="home">%3$s</a> %4$s %5$s %6$s</span>',
			home_url('/'),
			esc_attr(get_bloginfo('name', 'display')),
			get_bloginfo('name'),
			'<span onclick="javascript:location.href=\'' . admin_url() . '\'">&copy;</span>',
			$var_sCopyrightYear,
			__('all rights reserved', 'grey-opaque')
		);

		echo $var_sHTML;
	}

	add_action('greyopaque_footer_copyright', 'greyopaque_blog_copyright');
}

/**
 * Copyright (Themes).
 * Please, do not remove this.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_theme_copyright')) {
	function greyopaque_theme_copyright () {
		$var_sHTML = sprintf('<span class="footer-notice">%1$s "<a href="%3$s" title="WordPress-Theme %4$s (%5$s)">%4$s (%5$s)</a>" %2$s %6$s</span>',
			__('Theme', 'grey-opaque'),
			__('by:', 'grey-opaque'),
			GREY_OPAQUE_THEME_URI,
			GREY_OPAQUE_THEME_NAME,
			GREY_OPAQUE_THEME_VERSION,
			GREY_OPAQUE_THEME_AUTHOR
		);

		echo $var_sHTML;
	}

	add_action('greyopaque_footer_copyright', 'greyopaque_theme_copyright');
}

/**
 * Helper.
 */
/**
 * Theme-Options.
 *
 * @param string $var_sOption
 * @since Grey Opaque 1.0.0
 */
function greyopaque_get_theme_options($var_sOption = '') {
	$array_ThemeOptions = get_option('greyopaque_theme_options');

	if(!$var_sOption) {
		return $array_ThemeOptions;
	}

	if(isset($array_ThemeOptions[$var_sOption])) {
		return $array_ThemeOptions[$var_sOption];
	} else {
		return false;
	}
}

/**
 * Searching directory for given files.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_scan_directory')) {
	function greyopaque_scan_directory($var_sPath, $var_sWhat = 'all', $var_bRekursiv = true) {
		$var_sPath = preg_replace('~(.*)/$~', '\\1', $var_sPath); // removing last slash
		$array_list = array();
		$var_sDirectory = opendir($var_sPath);

		while($dir_entry = readdir($var_sDirectory)) {
			switch($var_sWhat) {
				case 'file':
					if(is_file("$var_sPath/$dir_entry")) {
						$array_list[] = $var_bRekursiv ? "$var_sPath/$dir_entry" : $dir_entry;
					}
					break;

				case 'dir':
					if(is_dir("$var_sPath/$dir_entry") && !preg_match('~^\.\.?$~', $dir_entry)) {
						$array_list[] = $var_bRekursiv ? "$var_sPath/$dir_entry" : $dir_entry;
					}
					break;

				default:
					if(!preg_match('~^\.\.?$~', $dir_entry)) {
						$array_list[] = $var_bRekursiv ? "$var_sPath/$dir_entry" : $dir_entry;
					}
			}

			// Subdirs?
			if(!preg_match('~^\.\.?$~', $dir_entry) && is_dir("$var_sPath/$dir_entry") && $var_bRekursiv) {
				$array_list2 = greyopaque_scan_directory("$var_sPath/$dir_entry", $var_sWhat, $var_bRekursiv);

				foreach($array_list2 as $dir_entry2) {
					$array_list[] = $dir_entry2;
				}
			}
		}

		closedir($var_sDirectory);
		sort($array_list);

		return $array_list;
	}
}

/**
 * Getting thumbnail by GUID
 *
 * @since Talos 1.0
 *
 * @param string $var_sGuid
 * @param string $var_sThumbnail Thumbnailtype
 */
if(!function_exists('greyopaque_get_thumbnail_by_guid')) {
	function greyopaque_get_thumbnail_by_guid($var_sGuid, $var_sThumbnail) {
		global $_wp_additional_image_sizes;
		/**
		 * Check if we have a thumbnailimage and not the original.
		 * If we do, remove the dimensions to get the original file.
		 *
		 * @var regex $var_sPattern
		 */
		$var_sPattern = '/-[0-9\/]+x[0-9\/]+/';
		if(preg_match($var_sPattern, $var_sGuid)) {
			$var_sGuid = preg_replace($var_sPattern, '', $var_sGuid);
		} // END if(preg_match($var_sPattern, $var_sGuid))

		/**
		 * Asking the DB
		 */
		global $wpdb;
		$var_qry = '
		SELECT
		' . $wpdb->postmeta . '.meta_value as post_meta_value
		FROM
		' . $wpdb->posts . ',
		' . $wpdb->postmeta . '
		WHERE
		' . $wpdb->posts . '.guid = "' . $var_sGuid . '"
		AND ' . $wpdb->postmeta . '.post_id = ' . $wpdb->posts . '.ID
		AND ' . $wpdb->postmeta . '.meta_key = "_wp_attachment_metadata";';
		$array_ImageMeta = unserialize($wpdb->get_var($var_qry));

		/**
		 * Check if the returned thumbnail has the right dimensions.
		 * If not, return false.
		 */
		if((isset($array_ImageMeta['sizes'][$var_sThumbnail]) && isset($_wp_additional_image_sizes[$var_sThumbnail])) && $_wp_additional_image_sizes[$var_sThumbnail]['width'] == $array_ImageMeta['sizes'][$var_sThumbnail]['width']) {
			$array_Logo = array(
				'url' => substr($var_sGuid, 0, strrpos($var_sGuid, '/')) . '/' . $array_ImageMeta['sizes'][$var_sThumbnail]['file'],
				'width' => $array_ImageMeta['sizes'][$var_sThumbnail]['width'],
				'height' => $array_ImageMeta['sizes'][$var_sThumbnail]['height']
			);
		} else {
			return false;
		}

		return $array_Logo;
	} // END function greyopaque_get_thumbnail_by_guid($var_sGuid)
} // END if(!function_exists('greyopaque_get_thumbnail_by_guid'))

/**
 * Adding our own defined imagesizes to the selection if sizes in the medialibrary.
 * This could be cool, if you are editing a post, so
 * you have also our defined sizes to add to your post.
 *
 * @since Grey Opaque 1.5
 */
if(!function_exists('greyopaque_wmu_image_sizes')) {
	function greyopaque_wmu_image_sizes($sizes) {
		$myimgsizes = array(
				'thumbnail-single' => __('Wide Thumbnail', 'grey-opaque'),
				'thumbnail-loop' => __('Small Thumbnail', 'grey-opaque'),
		);
		$newimgsizes = array_merge($sizes, $myimgsizes);

		return $newimgsizes;
	}

	add_filter('image_size_names_choose', 'greyopaque_wmu_image_sizes');
}

/**
 * Patch:
 * Falls doch der komplette Link eingegeben wurde
 * oder ein @ vor dem Twitternamen, nur den Twitternamen
 * herausfiltern.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_sanitize_twittername')) {
	function greyopaque_sanitize_twittername($var_sTwittername) {
		if(strstr( $var_sTwittername, 'http') || strstr($var_sTwittername, '/') || strstr($var_sTwittername, '@')) {
			$array_TwitterParts = explode('/', $var_sTwittername);

			if(is_array($array_TwitterParts)) {
				$var_sTwittername = str_replace('@', '', array_pop($array_TwitterParts));
			}
		}

		return $var_sTwittername;
	}
}

/**
 * Scrambling mailadresses.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_scramble_email')) {
	function greyopaque_scramble_email($var_sEmail) {
		$var_sScrambled_Mail = "";
		$array_Unpack = unpack("C*", $var_sEmail);

		foreach ($array_Unpack as $var_sUnpacked) {
			$var_sScrambled_Mail .= sprintf("%%%X", $var_sUnpacked);
		}

		return $var_sScrambled_Mail;
	}
}

/**
 * Helper: talos_get_ip()
 * Fetching the IP from User
 *
 * Needed as info for Contactmail.
 *
 * @since Grey Opaque 1.8
 * @author ppfeufer
 */
if(!function_exists('greyopaque_get_ip')) {
	function greyopaque_get_ip() {
		if(isset($_SERVER)) {
			$var_sIPAddress = '';

			if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$var_sIPAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif(isset( $_SERVER["HTTP_CLIENT_IP"])) {
				$var_sIPAddress = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$var_sIPAddress = $_SERVER["REMOTE_ADDR"];
			} // END if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		} else {
			if(getenv('HTTP_X_FORWARDED_FOR')) {
				$var_sIPAddress = getenv( 'HTTP_X_FORWARDED_FOR' );
			} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
				$var_sIPAddress = getenv( 'HTTP_CLIENT_IP' );
			} else {
				$var_sIPAddress = getenv( 'REMOTE_ADDR' );
			} // END if(getenv('HTTP_X_FORWARDED_FOR'))
		} // END if(isset($_SERVER))

		return $var_sIPAddress;
	} // END function talos_get_ip()
} // END if(!function_exists('talos_get_ip'))

/**
 * Transparent PNG Fix for IE
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_ie_png_fix')) {
	function greyopaque_ie_png_fix() {
		echo '<!--[if lt IE 7]><script type="text/javascript" src="http://bnote.googlecode.com/files/unitpngfix.js"></script><![endif]-->';
	}

	add_action('wp_head', 'greyopaque_ie_png_fix');
}

/**
 * Only for Debug.
 *
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_debug_loading_stats')) {
	function greyopaque_debug_loading_stats() {
		echo '<div class="theme-debug-block">';
		echo '<p class="theme-debug-block-head"><strong>Debugging: Loadingstats</strong></p>';
		printf(__('<p>Page loaded in %2$s seconds with %1$d database-queries.</p>', 'grey-opaque'),
			get_num_queries(),
			timer_stop(0, 3)
		);
		echo '</div>';
	}

	if(GREY_OPAQUE_DEBUG === true) {
		add_action('greyopaque_theme_debug', 'greyopaque_debug_loading_stats');
	}
}

/**
 * Shortcodes.
 */
get_template_part('functions', 'shortcodes');

/**
 * Loading deprecated functions..
 *
 * @since Grey Opaque 1.0.1
 */
get_template_part('functions', 'deprecated');

/**
 * Plugins
 *
 * @since Grey Opaque 1.4
 */
get_template_part('plugins/lazy-load/lazy-load');
get_template_part('plugins/color-admin-posts/color-admin-post');
?>