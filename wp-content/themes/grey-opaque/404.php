<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Grey_Opaque
 * @since Grey Opaque 1.0.0
 */

get_header(); ?>
	<div id="container">
		<div id="content" role="main">
			<?php greyopaque_the_infobox('404'); ?>
			<div id="post-0" class="post error404 not-found">
				<h1 class="entry-title"><?php _e('Not Found', 'grey-opaque'); ?></h1>
				<div class="entry-content clearfix">
					<p><?php _e('Apologies, but the page you requested could not be found. Perhaps searching will help.', 'grey-opaque'); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content clearfix -->
			</div><!-- #post-0 -->
		</div><!-- #content -->
	</div><!-- #container -->
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>
<?php get_footer(); ?>