<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after.
 * Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Grey_Opaque
 * @since Grey Opaque 1.0.0
 */
?>
	</div><!-- #main -->
	<div id="footer" role="contentinfo">
		<div id="colophon">
			<?php
				/**
				 * A sidebar in the footer? Yep. You can can customize
				 * your footer with four columns of widgets.
				 */
				get_sidebar('footer');
			?>
			<div id="site-info"></div><!-- #site-info -->

			<div id="theme-credits">
				<p id="footer-copyright">
					<?php do_action('greyopaque_footer_copyright'); ?>
				</p>
				<p id="footer-generator">
					<a href="<?php get_permalink()?>#wrapper"><?php echo __('Back to top', 'grey-opaque'); ?></a><br />
					<a id="sidegenerator" href="<?php echo esc_url(__('http://wordpress.org/', 'grey-opaque')); ?>" title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'grey-opaque'); ?>" rel="generator"><?php printf(__('Proudly powered by %s.', 'grey-opaque'), 'WordPress' ); ?></a>
				</p>
			</div><!-- #theme-credits -->
		</div><!-- #colophon -->
		<?php do_action('greyopaque_footer_statistics'); ?>
	</div><!-- #footer -->
</div><!-- #wrapper -->

<?php
	/**
	 * Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */
	wp_footer();
?>
</body>
</html>