<?php
/**
 * This file contains the documentation for the theme Grey Opaque.
 *
 * @since Grey Opaque 1.0.3.4
 */

if(!function_exists('greyopaque_theme_documentation')) {
	function greyopaque_theme_documentation() {
		?>
		<div class="wrap">
			<h2><?php _e('Grey Opaque Documentation', 'grey-opaque'); ?></h2>
			<p>
				<?php
				echo __('Here now a little documatation for your new theme Gray Opaque.<br />This file contains the description of the themesettings and the license for all used images.', 'grey-opaque');
				?>
			</p>
			<!-- General Settings -->
			<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Theme Options', 'grey-opaque'); ?></h3>
			<p>
				<?php
				echo __('In this part of themes setting you\'ll find the main options.', 'grey-opaque');
				?>
			</p>
			<p>
				<strong><?php echo __('Show Branding', 'grey-opaque'); ?></strong><br />
				<?php
				echo sprintf(__('If this option is checked, the branding will be displayed on frontend. The branding contains the blogs name (Site Title) and slogan (Tagline), which can be entered %1$s.', 'grey-opaque'),
					'<a href="' . admin_url('options-general.php') . '">' . __('here', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<strong><?php echo __('Show Headerimage', 'grey-opaque'); ?></strong><br />
				<?php
				echo sprintf(__('If this option is checked, a headerimage will be displayed on frontend. You can select a headerimage under %1$s. Here you can choose from the given images or upload new. There are two ways to uplaod new headerimages.', 'grey-opaque'),
					'<a href="' . admin_url('themes.php?page=custom-header') . '">' . __('Appearance -> Header', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<em><?php echo __('Using FTP', 'grey-opaque'); ?></em><br />
				<?php
				echo sprintf(__('The headerimages are located in "images/headers/" inside the themes directory.<br />If you want to add new images to the list you have to do it here. For each headerimage you\'ll find here two images. The headerimage itself (960 x 200 pixel) and its thumbnailimage (230 x 48 pixel). The filenames of this two images are important here. Only letters, numbers and the \'-\' are allowed.<br />e.g.: header.jpg and header-thumbnail.jpg<br />The thumbnail will be found by "-thumbnail". These images here will be shown in the list under %1$s.', 'grey-opaque'),
					'<a href="' . admin_url('themes.php?page=custom-header') . '">' . __('Appearance -> Header', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<em><?php echo __('Using WordPress', 'grey-opaque'); ?></em><br />
				<?php
				echo sprintf(__('You alse are able to upload a headerimage directly using your WordPress. This function can also be found under %1$s.<br />This will not affect the list of default images.', 'grey-opaque'),
					'<a href="' . admin_url('themes.php?page=custom-header') . '">' . __('Appearance -> Header', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<strong><?php echo __('Show Themecursor', 'grey-opaque'); ?></strong><br />
				<?php echo __('If this option is checked, the default curser will be replaced with the themes cursor.', 'grey-opaque'); ?>
			</p>
			<p>
				<strong><?php echo __('Get Postthumbnail for Facebook', 'grey-opaque'); ?></strong><br />
				<?php
				$var_sUriOriginal = 'http://playground.ebiene.de/2640/wordpress-facebook-miniatur/';
				$var_sUriModified = 'http://blog.ppfeufer.de/wordpress-artikelbilder-fuer-facebook/';
				echo sprintf(__('If this option is checked, the post thumbnail or the first image of an article is used for interaction with facebook. This if for facebooks like/recommend and share-buttons. The idea for this function is from %1$s with a little modification by %2$s.', 'grey-opaque'),
					'<a href="' . $var_sUriOriginal . '">' . __('Sergej M&uuml;ller', 'grey-opaque') . '</a>',
					'<a href="' . $var_sUriModified . '">' . __('myself', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<strong><?php echo __('Remove automatic Hyperlinks in Comments', 'grey-opaque'); ?></strong><br />
				<?php echo __('By using this option you can stop WordPress from automatically creating hyperlinks from links in your visitors comments. There may be several reasons for doing so but the main purpose is stop all outgoing links you could not control.', 'grey-opaque'); ?>
			</p>
			<p>
				<strong><?php echo __('Remove Generator-Tag', 'grey-opaque'); ?></strong><br />
				<?php echo __('Use this option to hide your WordPress version from others. If you don\'t update WordPress directly every time a new version is released, you\'re not able to brag about it. To prevent hackers hackers from finding out you\'re on an older WordPress version too easily, you might as well use this option. That way the generator meta tag with your WordPress version won\'t be shown.', 'grey-opaque'); ?>
			</p>
			<!-- Authorbox -->
			<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Authorbox', 'grey-opaque'); ?></h3>
			<p>
				<?php
				echo sprintf(__('Here you can define the settings of your authorbox. WordPress displays the authorbox if you have entered a biographical info in your %1$s. Grey Opaque overrides this settings and the authorbox must be activated seperatly here. If you haven\'t made any information in your profile, the authorbox will not be shown, whether you checked here.', 'grey-opaque'),
					'<a href="' . admin_url('profile.php') . '">' . __('profile settings', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<strong><?php echo __('Show Authorbox', 'grey-opaque'); ?></strong><br />
				<?php echo __('With this option, you can activate the authorbox. Remember to enter a biographical info in your profile.', 'grey-opaque'); ?>
			</p>
			<p>
				<strong><?php echo __('Show Profiles in Authorbox', 'grey-opaque'); ?></strong><br />
				<?php
				echo sprintf(__('If you have enabled this option, profileicons will be displayed beneath your Gravatar in the authorbox. To can enter the links to this profiles under "Contact Info" in your %1$s.', 'grey-opaque'),
					'<a href="' . admin_url('profile.php') . '">' . __('profile settings', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<strong><?php echo __('Show Mailicon in Authorbox', 'grey-opaque'); ?></strong><br />
				<?php echo __('This option will add a mailicon to the profiles in your authorbox. It is seperated because it is MAIL. The mailadress will be scrambles in HTML-output, but it is not a 100% security for mailspam. So think twice about it. If you have more authors in your blog, so it is recommend to ask your team before activating this option.', 'grey-opaque'); ?>
			</p>
			<!-- Admin Bar -->
			<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Admin Bar', 'grey-opaque'); ?></h3>
			<p>
				<?php
				echo sprintf(__('This options affecting the behaviour of the admin bar if enabled. To enable or disable the admin bar take a look in your %1$s under "Personal Options".', 'grey-opaque'),
					'<a href="' . admin_url('profile.php') . '">' . __('profile settings', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<strong><?php echo __('Move Admin Bar to Bottom', 'grey-opaque'); ?></strong><br />
				<?php echo __('With the following settings you can move the admin bar from the default position at the top of the browser to its bottom. It is seperated in frontend and backend.', 'grey-opaque'); ?>
			</p>
			<!-- Smilies -->
			<h3 style="color:#027393; width:250px; border-bottom:1px solid #027393;"><?php _e('Smilies', 'grey-opaque'); ?></h3>
			<p>
				<?php
				$var_sUriSmilieSet = 'http://www.bohncore.de/2011/01/15/freebie-simple-smiley-set-by-bohncore/';
				$var_sUriSmilieSetAuthor = 'http://www.bohncore.de/';
				echo sprintf(__('WordPress can use Smilies, but the default ones are not really pretty. So, Grey Opaque has its own smilie-set. For this I choose the free %1$s from %2$s.', 'grey-opaque'),
					'<a href="' . $var_sUriSmilieSet . '">' . __('Simple Smilie-Set', 'grey-opaque') . '</a>',
					'<a href="' . $var_sUriSmilieSetAuthor . '">' . __('Ralf Bohnert', 'grey-opaque') . '</a>'
				);
				?>
			</p>
			<p>
				<strong><?php echo __('Show Smilies', 'grey-opaque'); ?></strong><br />
				<?php echo __('Here you can activate the themes own smilie-set. You have the option to show smilies in your blogs content (articles and pages) and/or in comments.', 'grey-opaque'); ?>
			</p>
			<!-- License of graphics and images -->
			<h2><?php _e('License of used images and graphics', 'grey-opaque'); ?></h2>
			<p>
				<?php echo __('The theme Grey Opaque uses several graphics, images and social media icons.<br />The default headerimages are taken from the WordPress default theme TwentyTen, Grey Opaque is based on. All other are made for Grey Opaque and free to use.', 'grey-opaque'); ?>
			</p>
		</div>
		<?php
	}
}
?>