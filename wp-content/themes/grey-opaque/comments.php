<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to greyopaque_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Grey_Opaque
 * @since Grey Opaque 1.0.0
 */
?>

			<div id="comments">
<?php if(post_password_required()) : ?>
				<p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'grey-opaque'); ?></p>
			</div><!-- #comments -->
<?php
		/**
		 * Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php if(have_comments()) : ?>

<?php if(get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link(__('<span class="meta-nav">&larr;</span> Older Comments', 'grey-opaque')); ?></div>
				<div class="nav-next"><?php next_comments_link(__('Newer Comments <span class="meta-nav">&rarr;</span>', 'grey-opaque')); ?></div>
			</div> <!-- .navigation -->
<?php endif; // check for comment navigation ?>

			<?php if(greyopaque_get_comment_count()) :?>
				<h3 id="comments-title"><?php
				printf(_n('One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'grey-opaque'),
				number_format_i18n(get_comments_number()), '<em>' . get_the_title() . '</em>');
				?></h3>
				<ol class="commentlist">
					<?php
					/**
					 * Loop through and list the comments. Tell wp_list_comments()
					 * to use greyopaque_comment() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define greyopaque_comment() and that will be used instead.
					 * See greyopaque_comment() in grey-opaque/functions.php for more.
					 */

					/**
					 * Erst die "normalen" Kommentare.
					 */
					wp_list_comments(array(
						'type' => 'comment',
						'callback' => 'greyopaque_comment'
					));
					?>
				</ol>
			<?php endif; ?>

			<?php if(greyopaque_get_ping_count()) : ?>
				<div id="pings">
				<h3 id="ping-trackback-title"><?php
				printf(_n('One Response in another blog/article', '%1$s Responses in other blogs/articles', greyopaque_get_ping_count(), 'grey-opaque'),
					number_format_i18n(greyopaque_get_ping_count())
				);
				?></h3>

				<ol class="pinglist">
					<?php
					/**
					 * Nun die Trackbacks und Pingbacks
					 */
					wp_list_comments(array(
						'type' => 'pings',
						'callback' => 'greyopaque_comment'
					));
					?>
				</ol>
				</div>
			<?php endif; ?>

<?php if(get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link(__('<span class="meta-nav">&larr;</span> Older Comments', 'grey-opaque')); ?></div>
				<div class="nav-next"><?php next_comments_link(__('Newer Comments <span class="meta-nav">&rarr;</span>', 'grey-opaque')); ?></div>
			</div><!-- .navigation -->
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

	/**
	 * If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if(!comments_open()) :
?>
	<p class="nocomments"><?php _e('Comments are closed.', 'grey-opaque'); ?></p>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>

<?php comment_form(); ?>

</div><!-- #comments -->