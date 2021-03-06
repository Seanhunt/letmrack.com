<?php
/**
 * @package Priimo
 */
?>
<div id="comments">
    <?php if(post_password_required()): ?>
        <p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'priimo'); ?></p>
    </div>
    <?php return; endif; ?>

    <?php if(have_comments()): ?>
        <table class="comment-title-container tablayout"><tr>
        <td style="width:50%; text-align:left;">
            <h2 class="comments-title">
                <?php printf(_n('One comment', '%1$s comments', get_comments_number(), 'priimo'), number_format_i18n(get_comments_number())); ?>
            </h2>
        </td>
        <?php if(comments_open()): ?>
        <td style="width:50%; text-align:right;">
            <h4 class="comments-write-link"><a href="#respond"><?php _e('Leave a comment','priimo'); ?></a></h4>
        </td>
        <?php endif; ?>
        </tr></table>

        <?php if(get_comment_pages_count() > 1 && get_option('page_comments')): ?>
        <div class="comment-navigation single-navigation">
            <span class="nav-previous"><?php previous_comments_link(__('Older Comments', 'priimo')); ?></span>
            <span class="nav-next"><?php next_comments_link(__('Newer Comments', 'priimo')); ?></span>
        </div>
        <?php endif; ?>

        <ul class="commentlist">
            <?php wp_list_comments(array('callback' => 'priimo_comment')); ?>
        </ul>
    <?php elseif(!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')): ?>
        <p class="nocomments"><?php _e('Comments are closed.', 'priimo'); ?></p>
    <?php endif; ?>

    <?php comment_form(); ?>
</div>