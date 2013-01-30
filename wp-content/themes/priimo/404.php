<?php
/**
 * @package Priimo
 */
?>
<?php global $priimo_options; ?>
<?php get_header(); ?>
<div class="full-container">
    <article id="post-0" <?php post_class(); ?>>
        <h2 class="entry-title"><?php _e('Error! Page not found', 'priimo'); ?></h2>
        <div class="entry-content">
            <p><?php _e('Apologies, but this page does not exist anymore. Try using the search below. All archived entries have been listed below, try looking through them', 'priimo'); ?></p>
            <p><?php get_search_form(); ?></p>
            <p>
                <h3><?php _e('Archive','priimo'); ?></h3>
                <ul><?php wp_get_archives('type=postbypost'); ?></ul>
            </p>
        </div>
    </article>
</div>
<?php get_footer(); ?>