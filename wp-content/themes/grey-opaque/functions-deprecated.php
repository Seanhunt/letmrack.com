<?php
/**
 * This file contains a list of deprecated functions which will be deleted in future versions.
 */

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 * @deprecated since WordPress 3.1
 * @return string The gallery style filter, with the styles themselves removed.
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_remove_gallery_css')) {
	function greyopaque_remove_gallery_css($css) {
		return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
	}

	// Backwards compatibility with WordPress 3.0.
	if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.1', '<')) {
		add_filter('gallery_style', 'greyopaque_remove_gallery_css');
	}
}

/**
 * Infobox links neben den Beiträgen anzeigen.
 * Der Typ der Infobox kann über die Option definiert werden.
 * Zulässige Optionen:
 * tagcloud		=> Zeigt nur die Tagcloud an.
 *
 * @var string $var_sType
 * @deprecated since Grey Opaque 1.0.1
 * @see greyopaque_the_infobox($var_sType = '')
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_entry_box')) {
	function greyopaque_entry_box($var_sType = '') {
		echo '<div class="entry-actions">';

		switch ($var_sType) {
			/**
			 * Nur die Tagcloud anzeigen.
			 * => Bei erfolgloser Suche.
			 * => Auf 404-Seite.
			 */
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
			 * Die komplette Infobox anzeigen.
			 * Aufbau je nach Bedarf.
			 */
			default:
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
						}
						?>
						<?php edit_post_link(__('Edit', 'grey-opaque'), '<span class="edit-link">', '</span>'); ?>
					</p>
				</div>
				<div class="actions">
					<ul>
						<?php if(comments_open()) : ?>
							<li>
								<a class="comment" href="<?php the_permalink(); ?>#comments"><?php echo number_format_i18n(get_comments_number()); ?> <?php _e('comment(s)', 'grey-opaque'); ?></a>
							</li>

							<?php if(greyopaque_get_ping_count()) :?>
								<li>
									<a class="comment" href="<?php the_permalink(); ?>#pings"><?php echo number_format_i18n(greyopaque_get_ping_count()); ?> <?php _e('pingback(s)', 'grey-opaque'); ?></a>
								</li>
							<?php endif; ?>
						<?php endif; ?>

						<?php if(is_singular()) : ?>
							<li>
								<div class="share">
									<span><?php _e('share this post', 'grey-opaque'); ?></span>
									<ul class="sharing">
										<li class="first">
											<a rel="nofollow" title="<?php _e('Share on BlinkList', 'grey-opaque'); ?>" id="share_blinklist" href="http://blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=<?php the_permalink(); ?>&amp;Title=<?php rawurlencode(get_the_title()); ?>">BlinkList</a>
										</li>
										<li>
											<a rel="nofollow" title="<?php _e('Add to del.icio.us', 'grey-opaque'); ?>" id="share_delicious" href="http://del.icio.us/post?url=<?php the_permalink(); ?>&amp;title=<?php rawurlencode(get_the_title()); ?>">del.icio.us</a>
										</li>
										<li>
											<a rel="nofollow" title="<?php _e('Digg This!', 'grey-opaque'); ?>" id="share_digg" href="http://digg.com/submit?url=<?php the_permalink(); ?>">Digg</a>
										</li>
										<li>
											<a rel="nofollow" title="<?php _e('Share on Facebook', 'grey-opaque'); ?>" id="share_facebook" href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>">Facebook</a>
										</li>
										<li>
											<a rel="nofollow" title="<?php _e('Share on Reddit', 'grey-opaque'); ?>" id="share_reddit" href="http://reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php rawurlencode(get_the_title()); ?>">Reddit</a>
										</li>
										<li>
											<a rel="nofollow" title="<?php _e('Share on StumbleUpon', 'grey-opaque'); ?>" id="share_stumbleupon" href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php rawurlencode(get_the_title()); ?>">StumbleUpon</a>
										</li>
										<li>
											<a rel="nofollow" title="<?php _e('Tweet this!', 'grey-opaque'); ?>" id="share_twitter" href="http://twitter.com/home?status=<?php echo rawurlencode(get_the_title()); ?>%20<?php the_permalink(); ?>">Twitter</a>
										</li>
										<li class="last">
											<a rel="nofollow" title="<?php _e('Favourite on Technorati', 'grey-opaque'); ?>" id="share_technorati" href="http://www.technorati.com/faves?add=<?php the_permalink(); ?>">Technorati</a>
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
							<a class="permalink" href="<?php the_permalink(); ?>"><?php _e('permalink', 'grey-opaque'); ?></a>
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
 * Admin bar.
 *
 * @deprecated since Grey Opaque 1.4 and WordPress 3.3
 * @since Grey Opaque 1.0.0
 */
if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.1', '>=')) {
	if(!function_exists('greyopaque_extend_admin_bar')) {
		function greyopaque_extend_admin_bar() {
			global $wp_admin_bar;

			/**
			 * Usercheck.
			 */
			if(!is_super_admin() || !is_admin_bar_showing()) {
				return;
			}

			/**
			 * WordPress 3.3
			 * Do nothing.
			 *
			 * @since Grey Opaque 1.4
			 */
			if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.3', '>=')) {
				return;
			}

			$current_object = get_queried_object();

			/**
			 * Checking if we have WordPress 3.2 or higher.
			 * It has the most changes made here and only the themesettings
			 * we have to add to appearance right now.
			 *
			 * @since Grey Opaque 1.3.0
			 */
			if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.2', '>=')) {
				/**
				 * Appearance.
				 *
				 * @since Grey Opaque 1.0.0
				 */
				if(current_user_can('edit_theme_options')) {
					$wp_admin_bar->add_menu(array(
							'parent' => 'appearance',
							'id' => 'theme-settings',
							'title' => __('Settings', 'grey-opaque'),
							'href' => admin_url('themes.php?page=functions'),
							'meta' => array(
								'title'=>__('Open Theme-Settings Page', 'grey-opaque')
							)
					));
				}
			} else {
				/**
				 * Clear the admin bar.
				 * We will built it new.
				 *
				 * @since Grey opaque 1.0.0
				 */
				$wp_admin_bar->remove_menu('new-content');		// Neu erstellen
				$wp_admin_bar->remove_menu('edit');				// Artikel bearbeiten
				$wp_admin_bar->remove_menu('comments');			// Kommentare
				$wp_admin_bar->remove_menu('appearance');		// Design
				$wp_admin_bar->remove_menu('get-shortlink');	// Kurzlink

				/**
				 * New-Content.
				 *
				 * @since Grey opaque 1.0.0
				 */
				$actions = array();
				foreach((array) get_post_types(array('show_ui' => true), 'objects') as $ptype_obj) {
					if(true !== $ptype_obj->show_in_menu || ! current_user_can( $ptype_obj->cap->edit_posts)) {
						continue;
					}

					$actions['post-new.php?post_type=' . $ptype_obj->name] = array(
							$ptype_obj->labels->singular_name,
							$ptype_obj->cap->edit_posts, 'new-' . $ptype_obj->name
					);
				}

				if(empty($actions)) {
					return;
				}

				$wp_admin_bar->add_menu(array(
						'id' => 'new-content',
						'title' => _x('Add New', 'admin bar menu group label', 'grey-opaque'),
						'href' => admin_url(array_shift(array_keys($actions)))
				));

				foreach($actions as $link => $action) {
					$wp_admin_bar->add_menu(array(
							'parent' => 'new-content',
							'id' => $action[2],
							'title' => $action[0],
							'href' => admin_url($link)
					));
				}

				/**
				 * Posts and Pages.
				 *
				 * @since Grey Opaque 1.0.0
				 */
				if(!empty($current_object->post_type) && ($post_type_object = get_post_type_object($current_object->post_type)) && current_user_can($post_type_object->cap->edit_post, $current_object->ID)) {
					/**
					 * Mainmenu: "Article".
					 */
					$wp_admin_bar->add_menu(array(
							'id' => 'article',
							'title' => _x('Article', 'admin bar menu group label', 'grey-opaque')
					));

					/**
					 * Submenu: "Edit".
					 */
					$wp_admin_bar->add_menu(array(
							'parent' => 'article',
							'id' => 'edit',
							'title' => __('Edit', 'grey-opaque'),
							'href' => get_edit_post_link($current_object->ID)
					));

					/**
					 * Submenu: "Move to trash".
					 */
					$wp_admin_bar->add_menu(array(
							'parent' => 'article',
							'id' => 'delete',
							'title' => __('Move to Trash', 'grey-opaque'), // alternative for other titles is the $post_type_object->labels
							'href' => get_delete_post_link($current_object->ID)
					));
				}

				/**
				 * Comments.
				 * @since Grey Opaque 1.0.0
				 */
				if(!current_user_can('edit_posts')) {
					return;
				}

				$awaiting_mod = wp_count_comments();
				$awaiting_mod = $awaiting_mod->moderated;
				$awaiting_mod = ($awaiting_mod) ? "<span id='ab-awaiting-mod' class='pending-count'>" . number_format_i18n($awaiting_mod) . "</span>" : '';
				$wp_admin_bar->add_menu(array(
						'id' => 'comments',
						'title' => sprintf(__('Comments %s', 'grey-opaque'),
								$awaiting_mod
						),
						'href' => admin_url('edit-comments.php')
				));

				/**
				 * Appearance.
				 *
				 * @since Grey Opaque 1.0.0
				 */
				if(!current_user_can('switch_themes')) {
					return;
				}

				$wp_admin_bar->add_menu(array(
						'id' => 'appearance',
						'title' => _x('Appearance', 'admin bar menu group label', 'grey-opaque'),
						'href' => admin_url('themes.php')
				));

				if(!current_user_can('edit_theme_options')) {
					return;
				}

				if(current_theme_supports('widgets')) {
					$wp_admin_bar->add_menu(array(
							'parent' => 'appearance',
							'id' => 'widgets',
							'title' => __('Widgets', 'grey-opaque'),
							'href' => admin_url('widgets.php')
					));
				}

				if(current_theme_supports('menus') || current_theme_supports('widgets')) {
					$wp_admin_bar->add_menu(array(
							'parent' => 'appearance',
							'id' => 'menus',
							'title' => __('Menus', 'grey-opaque'),
							'href' => admin_url('nav-menus.php')
					));
				}

				if(current_user_can('edit_theme_options')) {
					$wp_admin_bar->add_menu(array(
							'parent' => 'appearance',
							'id' => 'theme-settings',
							'title' => __('Settings', 'grey-opaque'),
							'href' => admin_url('themes.php?page=functions.php'),
							'meta' => array(
									'title'=>__('Open Theme-Settings Page', 'grey-opaque')
							)
					));
				}

				/**
				 * Shortlinks.
				 *
				 * @since Grey Opaque 1.0.0
				 */
				$short = wp_get_shortlink( 0, 'query' );
				$id = 'get-shortlink';

				if(empty( $short)) {
					return;
				}

				$html = '<input class="shortlink-input" type="text" readonly="readonly" value="' . esc_attr( $short ) . '" />';

				$wp_admin_bar->add_menu(array(
						'id' => $id,
						'title' => __('Shortlink', 'grey-opaque'),
						'href' => $short,
						'meta' => array(
								'html' => $html
						)
				));
			} // END if(version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.2', '<='))
		} // END function greyopaque_extend_admin_bar()

		/**
		 * Change admin bar
		 */
		if (function_exists('is_admin_bar_showing') && is_admin_bar_showing()) {
			add_action('admin_bar_menu', 'greyopaque_extend_admin_bar', 90);
		}
	} // END if(!function_exists('greyopaque_extend_admin_bar'))
}

/**
 * Working with: $array_GreyOpaqueAdminbar
 */
/**
 * Move admin bar to bottom.
 *
 * @author Frank Bültge via http://wpengineer.com/2190/move-wordpress-admin-bar-to-the-bottom/
 * @deprecated since Grey Opaque 1.4 and WordPress 3.3 (because it doesn't work in WP 3.3)
 * @since Grey Opaque 1.0.1
 */
if((version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.1', '>=')) && (version_compare(GREY_OPAQUE_WP_VERSION_RUNNING, '3.3', '<'))) {
	if(!function_exists('greyopqaue_move_admin_bar')) {
		function greyopqaue_move_admin_bar() {
			echo '
			<style type="text/css">
			body.admin-bar #wphead {padding-top:0;}
			body.admin-bar #footer {padding-bottom:28px;}
			#wpadminbar {top:auto !important; bottom:0;}
			#wpadminbar .quicklinks .menupop ul {bottom:28px;}
			</style>';
		}

		$array_GreyOpaqueAdminbar = greyopaque_get_theme_options('greyopaque-adminbar');
		if(isset($array_GreyOpaqueAdminbar['move-to-bottom']) && $array_GreyOpaqueAdminbar['move-to-bottom'] == 'on') {
			// Frontend
			if(isset($array_GreyOpaqueAdminbar['in-frontend']) && $array_GreyOpaqueAdminbar['in-frontend'] == 'on') {
				add_action('wp_head', 'greyopqaue_move_admin_bar');
			}

			// Backend
			if(isset($array_GreyOpaqueAdminbar['in-backend']) && $array_GreyOpaqueAdminbar['in-backend'] == 'on') {
				add_action('admin_head', 'greyopqaue_move_admin_bar');
			}
		}
	}
}
?>