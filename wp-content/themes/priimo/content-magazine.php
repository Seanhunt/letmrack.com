<?php
/**
 * @package Priimo
 */
?>
<?php global $priimo_options; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'priimo'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
    <div class="entry-meta">
        <?php priimo_posted_on(); ?>
        <?php if(comments_open() && ! post_password_required()) : ?>
        <?php comments_popup_link(__('Reply', 'priimo'), _x('1 Comment', 'comments number', 'priimo'), _x('% Comments', 'comments number', 'priimo'), 'entry-comments'); ?>
        <?php edit_post_link(__('Edit', 'priimo')); ?>
        <?php endif; ?>
    </div>
    <div class="clear"></div>
    <?php $entry_content_class = ''; ?>
    <?php if(trim(get_the_post_thumbnail($post->ID)) != '') {
        $entry_content_class = 'entry-magazine';
    ?>
    <div class="entry-thumb">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('priimo-featured-image'); ?></a>
    </div>
    <?php } ?>
    <div class="entry-content <?php echo $entry_content_class; ?>">
        <?php the_excerpt(); ?>
    </div>
</article>