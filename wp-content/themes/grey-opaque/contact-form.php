<?php
/**
 * Simple contact form template for WordPress
 *
 * Use it in a template for pages in WordPress,
 *   include it easily via get_template_part( 'contact', 'form' );
 * See the action and filter hooks for include or change output for your requirements
 *
 * @author   Frank Bueltge <frank@bueltge.de>
 * @version  07/27/2012
 *
 *
 * -----------------------------------------------------------------------------
 * Settings
 * -----------------------------------------------------------------------------
 *
 * Text domain string from theme for translation in theme language files
 *   or you use the language files inside the folder /contact-form-languages/
 *   and copy this folder include the files in your theme
 */

// form processing if the input field has been set
if(isset($_REQUEST['submit']) && wp_verify_nonce($_REQUEST['grey-opaque-contact-form-fields'],'grey-opaque-contact')) {
	// Controlvariable
	$grey_opaque_contactform_has_error = false;

	// define markup for error messages
	$grey_opaque_contactform_error_tag = apply_filters('grey-opaque-contact-form-template-error-tag', 'p');

	// output form values for debugging
	if(defined('WP_DEBUG') && WP_DEBUG) {
		var_dump($_REQUEST);
	} // END if(defined('WP_DEBUG') && WP_DEBUG)

	$grey_opaque_contactform_spamcheck = stripslashes(wp_filter_nohtml_kses(filter_var(trim($_REQUEST['spamcheck']))));
	$grey_opaque_contactform_from = stripslashes(wp_filter_nohtml_kses(filter_var(trim(strip_tags($_REQUEST['from'])))));
	$grey_opaque_contactform_email = stripslashes(wp_filter_nohtml_kses(filter_var(trim($_REQUEST['email']), FILTER_VALIDATE_EMAIL)));
	$grey_opaque_contactform_website = stripslashes(wp_filter_nohtml_kses(filter_var(trim($_REQUEST['website']), FILTER_VALIDATE_URL)));
	$grey_opaque_contactform_subject_form = stripslashes(wp_filter_nohtml_kses(filter_var(trim($_REQUEST['subject']))));
	$grey_opaque_contactform_message = stripslashes(wp_filter_nohtml_kses(filter_var(trim($_REQUEST['text']))));

	if(isset($_REQUEST['cc'])) {
		$grey_opaque_contactform_cc = intval($_REQUEST['cc']);
	} else {
		$grey_opaque_contactform_cc = false;
	} // END if(isset($_REQUEST['cc']))

	// check for spam input field
	if(!empty($grey_opaque_contactform_spamcheck)) {
		$grey_opaque_contactform_spam_error = __('Spammer? The spam protection field needs to be empty.', 'grey-opaque');
		$grey_opaque_contactform_has_error = true;
	} // END if(!empty($grey_opaque_contactform_spamcheck))

	// check sender name, string
	if(empty($grey_opaque_contactform_from)) {
		$grey_opaque_contactform_from_error = __( 'Please enter your name.', 'grey-opaque');
		$grey_opaque_contactform_has_error = true;
	} // END if(empty($grey_opaque_contactform_from))

	// check for mail and filter the mail
	if(empty($grey_opaque_contactform_email)) {
		$grey_opaque_contactform_email_error = __('Please enter your e-mail adress.', 'grey-opaque');
		$grey_opaque_contactform_has_error   = true;
	} elseif(!preg_match("/^([a-z0-9äöü]+[-_\\.a-z0-9äöü]*)@[a-z0-9äöü]+([-_\.]?[a-z0-9äöü])+\.[a-z]{2,4}$/i", $grey_opaque_contactform_email)) {
		$grey_opaque_contactform_email_error = __('Please enter a valid e-mail adress.', 'grey-opaque');
		$grey_opaque_contactform_has_error = true;
	} // END if(empty($grey_opaque_contactform_email))

	if(empty($grey_opaque_contactform_subject_form)) {
		$grey_opaque_contactform_subject_error = __('Please enter a subject.', 'grey-opaque');
		$grey_opaque_contactform_has_error = true;
	} // END if(empty($grey_opaque_contactform_subject_form))

	if(empty($grey_opaque_contactform_message)) {
		$grey_opaque_contactform_message_error = __('Please enter a message.', 'grey-opaque');
		$grey_opaque_contactform_has_error = true;
	}

	if($grey_opaque_contactform_has_error === false) {
		// get IP
		$grey_opaque_contactform_ip_addr = filter_var(greyopaque_get_ip(), FILTER_VALIDATE_IP);

		// use mail adress from WP Admin
		$grey_opaque_contactform_email_to = get_option('admin_email');
		$grey_opaque_contactform_mailsubject = __('Contact request from', 'grey-opaque') . ' ' . $grey_opaque_contactform_from . ' / ' . $grey_opaque_contactform_subject_form;
		$grey_opaque_contactform_mailbody = __('Name:', 'grey-opaque') . ' ' . $grey_opaque_contactform_from . "\n" .
				__('E-mail:', 'grey-opaque') . ' ' . $grey_opaque_contactform_email . "\n" .
				__('Website:', 'grey-opaque') . ' ' . $grey_opaque_contactform_website . "\n" .
				__('IP:', 'grey-opaque') . ' ' . $grey_opaque_contactform_ip_addr . "\n\n" .
				__('Message:', 'grey-opaque') . ' ' . $grey_opaque_contactform_message;
		$grey_opaque_contactform_mailheaders = 'From: ' . $grey_opaque_contactform_from . ' <' . $grey_opaque_contactform_email . '>' . "\r\n";

		// check for cc and include sender mail to reply
		if($grey_opaque_contactform_cc) {
			$grey_opaque_contactform_mailheaders .= 'Reply-To: ' . $grey_opaque_contactform_email;
		} // END if($grey_opaque_contactform_cc)

		// Filter hooks for enhance the mail; sorry for long strings ;)
		$grey_opaque_contactform_email_to = apply_filters('grey-opaque-contact-form-template-mail-email-to', $grey_opaque_contactform_email_to);
		$grey_opaque_contactform_mailsubject = apply_filters('grey-opaque-contact-form-template-mail-subject', $grey_opaque_contactform_mailsubject);
		$grey_opaque_contactform_mailbody = apply_filters('grey-opaque-contact-form-template-mail-body', $grey_opaque_contactform_mailbody);

		// send mail via wp mail function
		wp_mail($grey_opaque_contactform_email_to, $grey_opaque_contactform_mailsubject, $grey_opaque_contactform_mailbody, $grey_opaque_contactform_mailheaders);

		// check for cc and send to sender
		if($grey_opaque_contactform_cc) {
			wp_mail(
				$grey_opaque_contactform_email,
				__('CC:', 'grey-opaque') . ' ' . $grey_opaque_contactform_mailsubject,
				$grey_opaque_contactform_mailbody,
				$grey_opaque_contactform_mailheaders
			);
		} // END if($grey_opaque_contactform_cc)

		// successfully mail shipping
		$grey_opaque_contactform_mail_sent = true;
	} // END if(!isset($grey_opaque_contactform_has_error))
} // END if(isset($_REQUEST['submit']))

do_action('grey-opaque-contact-form-template-form-before');
?>

<form action="<?php the_permalink(); ?>" method="post">
	<fieldset class="greyopaque-contact-form">
		<?php
		do_action('grey-opaque-contact-form-template-form-top');

		if(isset($grey_opaque_contactform_spam_error)) {
			echo apply_filters('grey-opaque-contact-form-template-spam-message', '<' . $grey_opaque_contactform_error_tag . ' class="alert">' . $grey_opaque_contactform_spam_error . '</' . $grey_opaque_contactform_error_tag . '>');
		}

		if(isset($grey_opaque_contactform_mail_sent)) {
			echo apply_filters('grey-opaque-contact-form-template-thanks-message', '<' . $grey_opaque_contactform_error_tag . ' class="alert">' . __('Thank you for leaving a message.', 'grey-opaque') . '</' . $grey_opaque_contactform_error_tag . '>');
		}

		do_action('grey-opaque-contact-form-template-form-before-fields');
		?>
		<div class="field clearfix">
			<label for="name"><?php _e('Name', 'grey-opaque'); ?> <small class="help-inline"><?php _e('*required', 'grey-opaque'); ?></small></label>
			<input type="text" id="from" name="from" placeholder="<?php _e('Your name', 'grey-opaque' ); ?>" value="<?php if(isset($grey_opaque_contactform_from)) {echo esc_textarea($grey_opaque_contactform_from);} ?>" />
			<?php
			if(isset($grey_opaque_contactform_from_error)) {
				echo '<' . $grey_opaque_contactform_error_tag . ' class="alert">' . $grey_opaque_contactform_from_error . '</' . $grey_opaque_contactform_error_tag . '>';
			}
			?>
		</div>

		<div class="field clearfix">
			<label for="email"><?php _e('E-mail address', 'grey-opaque'); ?> <small class="help-inline"><?php _e('*required', 'grey-opaque'); ?></small></label>
			<input type="text" placeholder="<?php _e('you@yourdomain.net', 'grey-opaque'); ?>" id="email" name="email" value="<?php if(isset($grey_opaque_contactform_email)) {echo esc_textarea($grey_opaque_contactform_email);} ?>" />
			<?php
			if(isset($grey_opaque_contactform_email_error)) {
				echo '<' . $grey_opaque_contactform_error_tag . ' class="alert">' . $grey_opaque_contactform_email_error . '</' . $grey_opaque_contactform_error_tag . '>';
			}
			?>
		</div>

		<div class="field clearfix">
			<label for="website"><?php _e('Website', 'grey-opaque'); ?></label>
			<input type="text" placeholder="<?php _e('http://yourdomain.net', 'grey-opaque'); ?>" id="website" name="website" value="<?php if(isset($grey_opaque_contactform_website)) {echo esc_textarea($grey_opaque_contactform_website);} ?>" />
		</div>

		<div class="field clearfix">
			<label for="subject"><?php _e('Subject', 'grey-opaque'); ?> <small class="help-inline"><?php _e('*required', 'grey-opaque'); ?></small></label>
			<input type="text" placeholder="<?php _e('Your concern', 'grey-opaque'); ?>" id="subject" name="subject" value="<?php if(isset($grey_opaque_contactform_subject_form)) {echo esc_textarea($grey_opaque_contactform_subject_form);} ?>" />
			<?php
			if(isset($grey_opaque_contactform_subject_error)) {
				echo '<' . $grey_opaque_contactform_error_tag . ' class="alert">' . $grey_opaque_contactform_subject_error . '</' . $grey_opaque_contactform_error_tag . '>';
			}
			?>
		</div>

		<?php do_action('grey-opaque-contact-form-template-form-after-fields'); ?>

		<div class="field clearfix">
			<label for="text"><?php _e('Message', 'grey-opaque'); ?> <small class="help-inline"><?php _e('*required', 'grey-opaque'); ?></small></label>
			<textarea id="text" name="text" placeholder="<?php _e('Your message &#x0085;', 'grey-opaque'); ?>"><?php if(isset($grey_opaque_contactform_message)) {echo esc_textarea($grey_opaque_contactform_message);} ?></textarea>
			<?php
			if(isset($grey_opaque_contactform_message_error)) {
				echo '<' . $grey_opaque_contactform_error_tag . ' class="alert">' . $grey_opaque_contactform_message_error . '</' . $grey_opaque_contactform_error_tag . '>';
			}
			?>
		</div>

		<div class="field clearfix">
			<input type="checkbox" id="cc" name="cc" value="1" <?php if(isset($grey_opaque_contactform_cc)) {checked('1', $grey_opaque_contactform_cc );} ?> />
			<label for="cc" style="display: inline;"><?php _e('Receive a copy of this message?', 'grey-opaque'); ?></label>
		</div>

		<div class="field clearfix" style="display: none !important;">
			<label for="text"><?php _e('Spam protection', 'grey-opaque'); ?></label>
			<input name="spamcheck" class="spamcheck" type="text" />
		</div>

		 <?php wp_nonce_field('grey-opaque-contact','grey-opaque-contact-form-fields'); ?>

		<p class="form-submit">
			<input class="submit" type="submit" name="submit" value="<?php _e('Send e-mail &rarr;', 'grey-opaque'); ?>" />
		</p>

		<?php do_action('grey-opaque-contact-form-template-form'); ?>

	</fieldset>
</form>

<?php do_action('grey-opaque-contact-form-template-form-after'); ?>