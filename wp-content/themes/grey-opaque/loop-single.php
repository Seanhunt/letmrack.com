<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 *
 * @package WordPress
 * @subpackage Grey_Opaque
 * @since Grey Opaque 1.0.0
 */
?>

<?php if(have_posts()) while(have_posts()) : the_post(); ?>

				<?php greyopaque_the_infobox(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-content clearfix">
						<?php
						if(function_exists('has_post_thumbnail') && has_post_thumbnail()) {
							$array_ImageData = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail-single');
							$array_ImageMeta = greyopaque_get_thumbnail_by_guid($array_ImageData['0'], 'thumbnail-single');

							if($array_ImageMeta) {
								the_post_thumbnail('thumbnail-single', array("class" => "aligncenter post_thumbnail"));
							} else {
								the_post_thumbnail('thumbnail-loop', array("class" => "alignleft post_thumbnail"));
							}
						}

						if(function_exists('greyopaque_the_content')) {
							greyopaque_the_content();
						} else {
							the_content();
						}

						wp_link_pages(array(
							'before' => '<div class="page-link">' . __('Pages:', 'grey-opaque'),
							'after' => '</div>'
						));
						?>
					</div><!-- .entry-content clearfix -->

<?php if (!function_exists('greyopaque_the_content') && get_the_author_meta('description')) : // If a user has filled out their description, show a bio on their entries  ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('greyopaque_author_bio_avatar_size', 60)); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2><?php printf(esc_attr__('About %s', 'grey-opaque'), get_the_author()); ?></h2>
							<?php the_author_meta('description'); ?>
							<div id="author-link">
								<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
									<?php printf(__('View all posts by %s <span class="meta-nav">&rarr;</span>', 'grey-opaque'), get_the_author()); ?>
								</a>
							</div><!-- #author-link	-->
						</div><!-- #author-description -->
					</div><!-- #entry-author-info -->
<?php endif; ?>

					<div class="entry-utility">
						<?php greyopaque_posted_in(); ?>
					</div><!-- .entry-utility -->
				</div><!-- #post-## -->

				<div id="nav-below" class="navigation article-navigation">
					<div class="nav-previous"><?php previous_post_link('%link', '%title'); ?></div>
					<div class="nav-next"><?php next_post_link('%link', '%title'); ?></div>
				</div><!-- #nav-below -->

				<?php comments_template('', true); ?>

<?php endwhile; // end of the loop. ?>