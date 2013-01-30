<?php /* Template Name: Sidebar Two Left */ ?>
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
<div id="lsidebar-sidebar">
<?php if ( is_active_sidebar("lsidebar-widget-area") ) : ?>
<div id="lsidebar" class="widget-area">
<ul class="sid">
<?php dynamic_sidebar("lsidebar-widget-area"); ?>
</ul>
<div class="clear"></div>
</div>
<?php endif; ?>
</div>
<div id="rsidebar-sidebar">
<?php if ( is_active_sidebar("rsidebar-widget-area") ) : ?>
<div id="rsidebar" class="widget-area">
<ul class="sid">
<?php dynamic_sidebar("rsidebar-widget-area"); ?>
</ul>
<div class="clear"></div>
</div>
<?php endif; ?>
</div>
<?php get_footer(); ?>