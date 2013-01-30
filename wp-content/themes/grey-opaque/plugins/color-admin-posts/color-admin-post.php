<?php

/**
 * Plugin Name: Color Admin Posts
 * Plugin URI: http://www.geekpress.fr/wordpress/extension/color-admin-posts/
 * Description: Change the background colors of the post/page within the admin based on the current status : Draft, Pending, Published, Future, Private.
 * Version: 1.0.1
 * Author: GeekPress
 * Author URI: http://www.geekpress.fr/
 * 
 * Copyright 2011 Jonathan Buttigieg
 * 
 * 		This program is free software; you can redistribute it and/or modify
 * 		it under the terms of the GNU General Public License as published by
 * 		the Free Software Foundation; either version 2 of the License, or
 * 		(at your option) any later version.
 * 
 * 		This program is distributed in the hope that it will be useful,
 * 		but WITHOUT ANY WARRANTY; without even the implied warranty of
 * 		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * 		GNU General Public License for more details.
 * 
 * 		You should have received a copy of the GNU General Public License
 * 		along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Color_Admin_Posts {
	private $options = array(); // Set $options in array
	private $settings = array(); // Set $setting in array
	private $textdomain = 'grey-opaque';

	function Color_Admin_Posts() {
		// Add menu page
		add_action('admin_menu', array(
			&$this,
			'add_submenu'
		));

		// Settings API
		add_action('admin_init', array(
			&$this,
			'settings_api_init'
		));

		// load the values recorded
		$this->options = get_option('color_admin_posts');
		add_action('admin_print_styles-edit.php', array(
			&$this,
			'load_color'
		));

		add_action('admin_head', array(
			&$this,
			'load_farbtastic'
		));

		// write default settings to database
		if(!is_array(get_option('color_admin_posts'))) {
			$this->activate();
		}
	}

	/**
	 * method activate
	 *
	 * This function is called when plugin is activated.
	 *
	 * @since 1.0
	 **/
	function activate() {
		$options = array(
			'color_draft' => '#FFFF99',
			'color_pending' => '#87C5D6',
			'color_published' => '#',
			'color_future' => '#CCFF99',
			'color_private' => '#FFCC99'
		);
		add_option('color_admin_posts', $options);
	}

	/**
	 * method deactivate
	 *
	 * This function is called when plugin is desactivated.
	 *
	 * @since 1.0
	 **/
	function deactivate() {
		delete_option('color_admin_posts');
	}

	/**
	 * method load_update_notifications
	 *
	 * @since 1.0
	 */
	function load_color() {
		$options = $this->options;
		?>
		<style>
			.status-draft {
				background-color: <?php echo$options['color_draft']; ?> !important;
			}
			
			.status-future {
				background-color: <?php echo$options['color_future']; ?> !important;
			}
			
			.status-publish {
				background-color: <?php echo$options['color_published']; ?> !important;
			}
			
			.status-pending {
				background-color: <?php echo$options['color_pending']; ?> !important;
			}
			
			.status-private {
				background-color: <?php echo$options['color_private']; ?> !important;
			}
		</style>
		<?php
	}

	/**
	 * method load_farbtastic
	 *
	 * Insert JS and CSS file for Farbtastic
	 *
	 * @since 1.0
	 **/
	function load_farbtastic() {
		global $current_screen;

// 		if($current_screen->id == 'appearance_page_color-admin-post') {
			wp_enqueue_style('farbtastic');
			wp_enqueue_script('farbtastic');
// 		}
	}

	/*
	 * method get_color_admin_post_settings
	 *
	 * @since 1.0
	*/
	function get_color_admin_post_settings() {
		$this->settings['color_draft'] = array(
			'title' => __('Drafts Posts', $this->textdomain)
		);

		$this->settings['color_pending'] = array(
			'section' => 'general',
			'title' => __('Pendings Posts', $this->textdomain)
		);

		$this->settings['color_published'] = array(
			'title' => __('Published Posts', $this->textdomain)
		);

		$this->settings['color_future'] = array(
			'title' => __('Futures Posts', $this->textdomain)
		);

		$this->settings['color_private'] = array(
			'title' => __('Privates Posts', $this->textdomain)
		);
	}

	/*
	 * method create_setting
	 * $args : array
	 *
	 * @since 1.0
	*/
	function create_settings($args = array()) {
		extract($args);

		$field_args = array(
			'id' => $id,
			'label_for' => $id
		);

		add_settings_field($id, $title, array(
			$this,
			'display_settings'
		), __FILE__, 'general', $field_args);
	}

	/**
	 * method display_settings
	 *
	 * HTML output for text field
	 *
	 * @since 1.0
	 */
	public function display_settings($args = array()) {
		extract($args);

		$options = $this->options;

		echo '<input class="regular-text" type="text" maxlength="7" id="' . $id . '" name="color_admin_posts[' . $id . ']" value="' . esc_attr($options[$id]) . '" />
			  <br /><span class="description">' . $desc . '</span>
 			  <div id="farbtastic-' . $id . '" class="farbtastic"></div>';
	}

	/**
	 * method settings_api_init
	 *
	 * Register settings with the WP Settings API
	 *
	 * @since 1.0
	 */
	function settings_api_init() {
		register_setting('color_admin_posts', 'color_admin_posts', array(
			&$this,
			'validate_settings'
		));

		add_settings_section('general', '', array(
			&$this,
			'general_section_callback'
		), __FILE__);

		// Get the configuration of fields
		$this->get_color_admin_post_settings();

		// Generate fields
		foreach($this->settings as $id => $setting) {
			$setting['id'] = $id;
			$this->create_settings($setting);
		}
	}

	/**
	 * method general_section_callback
	 *
	 * @since 1.0
	 */
	function general_section_callback() {
		echo '<p>' . __('Leave "#" for the default color.', $this->textdomain) . '</p>';
	}

	/**
	 * method validate_settings
	 *
	 * @since 1.0
	 */
	function validate_settings($input) {
		$input['color_draft'] = (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $input['color_draft'])) ? $input['color_draft'] : '#';
		$input['color_pending'] = (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $input['color_pending'])) ? $input['color_pending'] : '#';
		$input['color_published'] = (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $input['color_published'])) ? $input['color_published'] : '#';
		$input['color_future'] = (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $input['color_future'])) ? $input['color_future'] : '#';
		$input['color_private'] = (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $input['color_private'])) ? $input['color_private'] : '#';

		return $input;
	}

	/**
	 * method add_submenu
	 *
	 * @since 1.0
	 */
	function add_submenu() {
		// Add submenu in menu "Apereance"
		add_theme_page(__('Color Admin Posts', 'grey-opaque'), __('Color Admin Posts', 'grey-opaque'), 'edit_theme_options', 'color-admin-post', array(
			$this,
			'display_page'
		));
	}

	/**
	 * method display_page
	 *
	 * @since 1.O
	 */
	function display_page() {
		// Check if user can access to the plugin
		if(!current_user_can('administrator')) {
			wp_die(__('You do not have sufficient permissions to access this page.', 'grey-opaque'));
		}
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>Color Admin Posts</h2>
			<form id="form-color-admin-posts" method="post" action="options.php">
				<?php
				settings_fields('color_admin_posts');
				do_settings_sections(__FILE__);
				submit_button(__('Save Changes', 'grey-opaque'));
				?>
			</form>
		</div>

		<script type="text/javascript">
			jQuery(function(){
				jQuery(document).ready(function() {
					jQuery('.regular-text').each(function() {
						jQuery('#farbtastic-'+this.id).hide();
						jQuery('#farbtastic-'+this.id).farbtastic(this);
						jQuery(this).click(function(){
							jQuery('#farbtastic-'+this.id).fadeIn();
						});

						jQuery(this).keyup(function() {
							if( jQuery(this).val() == '' ) {
								jQuery(this).val('#');
							}
						});
					});

					jQuery(document).mousedown(function() {
						jQuery('.farbtastic').each(function() {
							var display = jQuery('#'+this.id).css('display');
							if ( display == 'block' ) {
								jQuery('#'+this.id).fadeOut();
							}
						});
					});
				});
			});
		</script>
		<?php
	}
}

// Start this plugin once all other plugins are fully loaded
global $Color_Admin_Posts;
$Color_Admin_Posts = new Color_Admin_Posts();
?>