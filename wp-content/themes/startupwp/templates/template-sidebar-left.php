<?php /* Template Name: Sidebar Left */ ?>
<?php get_header(); ?>
<article id="content">
<?php the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<h1 class="entry-title"><?php the_title(); ?></h1>
<?php get_template_part( 'entry', 'content' ); ?>
<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'startup' ) . '&after=</div>') ?>
<?php edit_post_link( __( 'Edit', 'startup' ), '<div class="edit-link">', '</div>' ) ?>
</div>
<?php comments_template( '', true ); ?>
</article>
<?php get_sidebar(); ?>
<?php get_footer(); ?>