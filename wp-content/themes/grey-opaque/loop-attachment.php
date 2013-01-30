<?php
/**
 * The loop that displays an attachment.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-attachment.php.
 *
 * @package WordPress
 * @subpackage Grey_Opaque
 * @since Grey Opaque 1.0.0
 */
?>

<?php if(have_posts()) while(have_posts()) : the_post(); ?>

				<?php greyopaque_the_infobox(); ?>

				<?php if(!empty($post->post_parent)) : ?>
					<p class="page-title"><a href="<?php echo get_permalink($post->post_parent); ?>" title="<?php esc_attr(printf(__('Return to %s', 'grey-opaque'), get_the_title($post->post_parent))); ?>" rel="gallery"><?php
						/* translators: %s - title of parent post */
						printf(__('<span class="meta-nav">&larr;</span> %s', 'grey-opaque'), get_the_title($post->post_parent));
					?></a></p>
				<?php endif; ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2 class="entry-title"><?php the_title(); ?></h2>
					<div class="entry-content clearfix">
						<div class="entry-attachment">
<?php if(wp_attachment_is_image()) :
	$attachments = array_values(get_children(array(
		'post_parent' => $post->post_parent,
		'post_status' => 'inherit',
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'order' => 'ASC',
		'orderby' => 'menu_order ID'
	)));

	foreach($attachments as $k => $attachment) {
		if ($attachment->ID == $post->ID)
			break;
	}

	$k++;
	// If there is more than 1 image attachment in a gallery
	if(count($attachments) > 1) {
		if(isset($attachments[ $k ])) {
			// get the URL of the next image attachment
			$next_attachment_url = get_attachment_link($attachments[ $k ]->ID);
		} else {
			// or get the URL of the first image attachment
			$next_attachment_url = get_attachment_link($attachments[ 0 ]->ID);
		}
	} else {
		// or, if there's only 1 image attachment, get the URL of the image
		$next_attachment_url = wp_get_attachment_url();
	}
?>
						<p class="attachment"><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr(get_the_title()); ?>" rel="attachment"><?php
							$attachment_width  = apply_filters('greyopaque_attachment_size', 900);
							$attachment_height = apply_filters('greyopaque_attachment_height', 900);
							echo wp_get_attachment_image($post->ID, array(
								$attachment_width,
								$attachment_height
							)); // filterable image width with, essentially, no limit for image height.
						?></a></p>

						<div id="nav-below" class="navigation attachment-navigation">
							<div class="nav-previous"><?php previous_image_link(false); ?></div>
							<div class="nav-next"><?php next_image_link(false); ?></div>
						</div><!-- #nav-below -->
<?php else : ?>
						<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr(get_the_title()); ?>" rel="attachment"><?php echo basename(get_permalink()); ?></a>
<?php endif; ?>
						</div><!-- .entry-attachment -->
						<div class="entry-caption"><?php if(!empty($post->post_excerpt)) {the_excerpt();} ?></div>

<?php
if(function_exists('greyopaque_the_content')) {
	greyopaque_the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'grey-opaque'));
} else {
	the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'grey-opaque'));
}

wp_link_pages(array(
	'before' => '<div class="page-link">' . __('Pages:', 'grey-opaque'),
	'after' => '</div>'
));
?>

					</div><!-- .entry-content clearfix -->

					<div class="entry-utility">
						<?php greyopaque_posted_in(); ?>
					</div><!-- .entry-utility -->
				</div><!-- #post-## -->

<?php comments_template(); ?>

<?php endwhile; // end of the loop. ?>