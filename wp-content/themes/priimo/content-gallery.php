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
    <?php if(is_search()): ?>
        <div class="entry-content">
            <?php the_excerpt(); ?>
        </div>
    <?php else: ?>
        <div class="entry-content">
            <?php if(post_password_required()): ?>
                <?php the_content(__('Continue reading', 'priimo')); ?>
            <?php else: ?>
                <?php
                    $images = get_children(array('post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999));
                    if($images):
                        $total_images = count($images);
                        $image = array_shift($images);
                        $image_img_tag = wp_get_attachment_image($image->ID, 'thumbnail');
                ?>
                <figure class="gallery-thumb">
                    <a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
                </figure><!-- .gallery-thumb -->
                <p><?php printf(_n('This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'priimo'), 'href="'.esc_url(get_permalink()).'" title="'.sprintf(esc_attr__('Permalink to %s', 'priimo'), the_title_attribute('echo=0')).'" rel="bookmark"', number_format_i18n($total_images)); ?></p>
                <?php endif; ?>
                <?php the_excerpt(); ?>
            <?php endif; ?>
            <?php wp_link_pages(array('before' => '<div class="page-link clearfix"><span class="pages-title">'.__('Pages:','priimo').'</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
        </div>
    <?php endif; ?>
</article>