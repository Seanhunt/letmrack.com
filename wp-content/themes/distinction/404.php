<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Distinction
 * @since Distinction 1.0
 */

get_header(); ?>

	<div id="container">
		<div id="content" role="main">

			<div id="post-0" class="post error404 not-found">
            	<div class="post-inner">
					<h1 class="title"><?php _e( 'Not Found', 'wpnj_distinction' ); ?></h1>
					<div class="entry">
						<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'distinction' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
                </div>
			</div><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>