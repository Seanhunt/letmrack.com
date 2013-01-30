<?php
/**
 * @package Priimo
 */
?>
<div id="primary" class="widget-area" role="complementary">
    <?php if(!dynamic_sidebar('sidebar-1')): ?>
    <aside id="priimo-archives" class="widget widget_archives">
        <h3 class="widget-title"><?php _e('Archives','priimo'); ?></h3>
        <ul id="recentarchives">
            <?php wp_get_archives(); ?>
        </ul>
    </aside>
    <aside id="priimo-recent-posts" class="widget widget_recent_posts">
        <h3 class="widget-title"><?php _e('Recent Posts','priimo'); ?></h3>
        <ul id="recentposts">
            <?php wp_get_archives('type=postbypost&limit=8'); ?>
        </ul>
    </aside>
    <?php endif; ?>
</div>