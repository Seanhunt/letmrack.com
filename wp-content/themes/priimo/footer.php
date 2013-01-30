<?php
/**
 * @package Priimo
 */
?>
    </div> <!-- End of content -->
    <div id="footer">
        <?php if(!is_404()) get_sidebar('footer'); ?>
        <div id="footer-credits">
            <?php _e('Copyright &copy;','priimo'); ?> <?php bloginfo('name'); ?>
        </div>
    </div>
</div> <!-- End of wrapper -->
<?php wp_footer(); ?>
</body>
</html>