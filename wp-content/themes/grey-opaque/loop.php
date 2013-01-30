<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Grey_Opaque
 * @since Grey Opaque 1.0.0
 */
?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if(!have_posts()) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'grey-opaque' ); ?></h1>
		<div class="entry-content clearfix">
			<p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'grey-opaque'); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content clearfix -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php
/**
 * Start the Loop.
 *
 * In Grey Opaque we use the same loop in multiple contexts.
 * It is broken into three main parts: when we're displaying
 * posts that are in the gallery category, when we're displaying
 * posts in the asides category, and finally all other posts.
 *
 * Additionally, we sometimes check for whether we are on an
 * archive page, a search page, etc., allowing for small differences
 * in the loop on each template without actually duplicating
 * the rest of the loop that is shared.
 *
 * Without further ado, the loop:
 */
?>
<?php $count_i = 1; ?>
<?php while(have_posts()) : the_post(); ?>
<?php $var_sClassFirst = ''; ?>
<?php
/**
 * Ersten Eintrag markieren.
 */
if($count_i == '1') {
	$var_sClassFirst = 'first-in-loop';
}
?>
<?php /* How to display posts of the Gallery format. The gallery category is the old way. */ ?>

	<?php if((function_exists('get_post_format') && 'gallery' == get_post_format($post->ID)) || in_category(_x('gallery', 'gallery category slug', 'grey-opaque'))) : ?>
		<?php greyopaque_the_infobox(); ?>

		<div id="post-<?php the_ID(); ?>" class="<?php echo join(' ', get_post_class($var_sClassFirst)); ?>">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'grey-opaque'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<div class="entry-content clearfix">
<?php if(post_password_required()) : ?>
				<?php
				if(function_exists('has_post_thumbnail') && has_post_thumbnail()) {
					the_post_thumbnail('thumbnail-loop', array(
						'class' => 'alignleft post_thumbnail'
					));
				}

				if(function_exists('greyopaque_the_content')) {
					greyopaque_the_content();
				} else {
					the_content();
				}
				?>
<?php else : ?>
				<?php
					$images = get_children(array(
						'post_parent' => $post->ID,
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'numberposts' => 999
					));

					if($images) :
						$total_images = count($images);
						$image = array_shift($images);
						$image_img_tag = wp_get_attachment_image($image->ID, 'thumbnail');
				?>
						<div class="gallery-thumb">
							<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
						</div><!-- .gallery-thumb -->
						<p>
							<em>
								<?php
								printf(_n('This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'grey-opaque'),
									'href="' . get_permalink() . '" title="' . sprintf(esc_attr__('Permalink to %s', 'grey-opaque'), the_title_attribute('echo=0')) . '" rel="bookmark"',
									number_format_i18n($total_images)
								);
								?>
							</em>
						</p>
				<?php endif; ?>
						<?php
						if(function_exists('has_post_thumbnail') && has_post_thumbnail()) {
							the_post_thumbnail('thumbnail-loop', array(
								'class' => 'alignleft post_thumbnail'
							));
						}

						the_excerpt();
						?>
<?php endif; ?>
			</div><!-- .entry-content clearfix -->

			<div class="entry-utility">
			<?php if(function_exists('get_post_format') && 'gallery' == get_post_format($post->ID)) : ?>
				<a href="<?php echo get_post_format_link('gallery'); ?>" title="<?php esc_attr_e('View Galleries', 'grey-opaque'); ?>"><?php _e('More Galleries', 'grey-opaque'); ?></a>
			<?php elseif(in_category(_x('gallery', 'gallery category slug', 'grey-opaque'))) : ?>
				<a href="<?php echo get_term_link(_x('gallery', 'gallery category slug', 'grey-opaque'), 'category'); ?>" title="<?php esc_attr_e('View posts in the Gallery category', 'grey-opaque'); ?>"><?php _e('More Galleries', 'grey-opaque'); ?></a>
			<?php endif; ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->
		<div class="hr"></div>

<?php /* How to display posts of the Aside format. The asides category is the old way. */ ?>

	<?php elseif((function_exists('get_post_format') && 'aside' == get_post_format($post->ID)) || in_category(_x('asides', 'asides category slug', 'grey-opaque'))) : ?>
		<?php greyopaque_the_infobox(); ?>

		<div id="post-<?php the_ID(); ?>" class="<?php echo join(' ', get_post_class($var_sClassFirst)); ?>">

		<?php if(is_archive() || is_search()) : // Display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php
				if(function_exists('has_post_thumbnail') && has_post_thumbnail()) {
					the_post_thumbnail('thumbnail-loop', array(
						'class' => 'alignleft post_thumbnail'
					));
				}

				the_excerpt();
				?>
			</div><!-- .entry-summary -->
		<?php else : ?>
			<div class="entry-content clearfix">
				<?php
				if(function_exists('has_post_thumbnail') && has_post_thumbnail()) {
					the_post_thumbnail('thumbnail-loop', array(
						'class' => 'alignleft post_thumbnail'
					));
				}

				if(function_exists('greyopaque_the_content')) {
					greyopaque_the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'grey-opaque'));
				} else {
					the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'grey-opaque'));
				}
				?>
			</div><!-- .entry-content clearfix -->
		<?php endif; ?>
		</div><!-- #post-## -->
		<div class="hr"></div>

	<?php
	/**
	 * How to display all other posts.
	 *
	 * Mainloop.
	 * called by: get_template_part('loop', 'index');
	 */
	?>
	<?php else : ?>
		<?php greyopaque_the_infobox(); ?>

		<div id="post-<?php the_ID(); ?>" class="<?php echo join(' ', get_post_class($var_sClassFirst)); ?>">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'grey-opaque'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

	<?php if(is_archive() || is_search()) : // Only display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php
				if(function_exists('has_post_thumbnail') && has_post_thumbnail()) {
					the_post_thumbnail('thumbnail-loop', array(
						'class' => 'alignleft post_thumbnail'
					));
				}

				the_excerpt();
				?>
			</div><!-- .entry-summary -->
	<?php else : ?>
			<div class="entry-content clearfix">
				<?php
				if(function_exists('has_post_thumbnail') && has_post_thumbnail()) {
					the_post_thumbnail('thumbnail-loop', array(
						'class' => 'alignleft post_thumbnail'
					));
				}

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
	<?php endif; ?>

			<div class="entry-utility">
				<?php if(count(get_the_category())) : ?>
					<span class="cat-links">
						<?php printf(__('<span class="%1$s">Posted in</span> %2$s', 'grey-opaque'), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list(', ')); ?>
					</span>
				<?php endif; ?>
				<?php
					$tags_list = get_the_tag_list('', ', ');
					if($tags_list) :
				?>
					<span class="tag-links">
						<?php printf(__('<span class="%1$s">Tagged</span> %2$s', 'grey-opaque'), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list); ?>
					</span>
				<?php endif; ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->
		<div class="hr"></div>

		<?php comments_template('', true); ?>

	<?php endif; // This was the if statement that broke the loop into three parts based on categories. ?>

<?php $count_i++; ?>
<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if($wp_query->max_num_pages > 1) : ?>
				<div id="nav-below" class="navigation">
					<?php if(function_exists('wp_pagenavi')) : ?>
						<?php wp_pagenavi() ?>
					<?php else : ?>
						<div class="pagenavi">
							<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'grey-opaque')); ?></div>
							<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'grey-opaque')); ?></div>
						</div>
					<?php endif; ?>
				</div><!-- #nav-below -->
<?php endif; ?>
