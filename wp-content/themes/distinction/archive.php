<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Distinction
 * @since Distinction 1.0
 */

get_header(); ?>

		<div id="container" class="one-column">
			<div id="content" role="main">

<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>
<?php $post_id = get_the_ID();
	$format = get_post_format( $post_id );
	?>

		
<?php if ( is_day() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Daily Archives: <span>%s</span>', 'wpnj_distinction' ), get_the_date() ); ?></h1>
<?php elseif ( is_month() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Monthly Archives: <span>%s</span>', 'wpnj_distinction' ), get_the_date('F Y') ); ?></h1>
<?php elseif ( is_year() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Yearly Archives: <span>%s</span>', 'wpnj_distinction' ), get_the_date('Y') ); ?></h1>
<?php elseif ( is_tax( 'post_format' ) ) : ?>
				<h1 class="page-title"><?php printf( __( 'Type Archive: <span>%s</span>', 'wpnj_distinction' ), $format = get_post_format( $post_id ) ); ?></h1>
<?php endif; ?>
			

<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();

	/* Run the loop for the archives page to output the posts.
	 * If you want to overload this in a child theme then include a file
	 * called loop-archives.php and that will be used instead.
	 */
	 get_template_part( 'loop', 'archive' );
?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
