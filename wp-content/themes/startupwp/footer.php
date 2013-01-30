<?php $options = get_option('startup_options'); ?>
<div class="clear"></div>
</div>
<footer>
<div id="copyright">
<?php echo sprintf( __( '%1$s %2$s %3$s. All Rights Reserved.', 'startup' ), '&copy;', date('Y'), esc_html(get_bloginfo('name')) ); echo sprintf( __( ' Proudly Built with %1$s and %2$s.', 'startup' ), '<a href="https://startupwp.com/">StartupWP</a>', '<a href="http://wordpress.org/">WordPress</a>' ); ?>
</div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>