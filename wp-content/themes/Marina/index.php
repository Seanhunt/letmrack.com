<?php get_header(); ?>

<div id="content">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="box  <?php if (++$counter % 2 == 0) { echo "posteven"; }?> " id="post-<?php the_ID(); ?>">
<div class="boxtitle">
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
<div class="titlemeta">
		<span class="author"> Posted by <?php the_author(); ?> on <?php the_time('F - j - Y'); ?></span> 
</div>
</div>
<div class="entry">
<a href="<?php the_permalink() ?>">
<?php marina_post_image() ?>
</a>
	<?php the_excerpt(); ?> 
<div class="clear"></div>
</div>
<div class="postmeta">
		
		<span class="comm"><?php comments_popup_link('0 Comment', '1 Comment', '% Comments'); ?></span>
		<span class="morer"><a href="<?php the_permalink() ?>"> Read Full Post </a></span>
</div>

</div>

<?php endwhile; ?>
<div class="clear"></div>
<div id="navigation">
<?php if(function_exists('wp_pagenavi')) : ?>
<?php wp_pagenavi() ?>
<?php else : ?>
        <div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','arclite')) ?></div>
        <div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','arclite')) ?></div>
        <div class="clear"></div>
<?php endif; ?>

</div>

<?php else : ?>
		<h1 class="title">Not Found</h1>
		<p>Sorry, but you are looking for something that isn't here.</p>
<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>