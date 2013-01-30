<?php
/**
 * @package Priimo
 */
?>
<?php global $priimo_options; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-meta">
        <?php priimo_posted_on(); ?>
        <?php if(comments_open() && ! post_password_required()) : ?>
        <?php comments_popup_link(__('Reply', 'priimo'), _x('1 Comment', 'comments number', 'priimo'), _x('% Comments', 'comments number', 'priimo'), 'entry-comments'); ?>
        <?php edit_post_link(__('Edit', 'priimo')); ?>
        <?php endif; ?>
    </div>
    <div class="entry-content">
        <?php the_content(__('Continue reading', 'priimo')); ?>
        <?php wp_link_pages(array('before' => '<div class="page-link clearfix"><span class="pages-title">'.__('Pages:','priimo').'</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
    </div>
</article>