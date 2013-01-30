<?php /* Template Name: Sidebar None */ ?>
<?php get_header(); ?>
<article id="content">
<?php the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<h1 class="entry-title"><?php the_title(); ?></h1>
<div class="entry-content">
<?php 
if ( has_post_thumbnail() ) {
the_post_thumbnail();
} 
?>
<?php the_content(); ?>
<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'startup' ) . '&after=</div>') ?>
<?php edit_post_link( __( 'Edit', 'startup' ), '<span class="edit-link">', '</span>' ) ?>
</div>
</div>
<?php comments_template( '', true ); ?>
</article>
<?php get_footer(); ?>