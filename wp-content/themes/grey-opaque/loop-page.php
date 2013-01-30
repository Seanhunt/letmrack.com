<?php
/**
 * The loop that displays a page.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-page.php.
 *
 * @package WordPress
 * @subpackage Grey_Opaque
 * @since Grey Opaque 1.0.0
 */
?>

<?php if(have_posts()) while(have_posts()) : the_post(); ?>

				<?php greyopaque_the_infobox(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if(is_front_page()) { ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php } else { ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php } ?>

					<div class="entry-content clearfix">

						<?php
						if(function_exists('has_post_thumbnail') && has_post_thumbnail()) {
							the_post_thumbnail('thumbnail-single', array(
								'class' => 'alignleft post_thumbnail'
							));
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
				</div><!-- #post-## -->

				<?php comments_template('', true); ?>

<?php endwhile; // end of the loop. ?>