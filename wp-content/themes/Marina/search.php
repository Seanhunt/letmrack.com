<?php get_header(); ?>

<div id="content" >
<h2 class="pagetitle">Search Results</h2>
<?php if (have_posts()) : ?>

<?php while (have_posts()) : the_post(); ?>
		
<div class="post" id="post-<?php the_ID(); ?>">
<div class="title">

<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
<div class="titlemeta">
		<span class="author"> Posted by <?php the_author(); ?> on <?php the_time('F - j - Y'); ?></span> 
</div>
<div class="clear"></div>
</div>

<div class="cover">
<div class="entry">


	<?php the_excerpt(); ?> 


<div class="clear"></div>
	
</div>

</div>

<div class="postmeta">
		
		<span class="comm"><?php comments_popup_link('0 Comment', '1 Comment', '% Comments'); ?></span>
		<span class="morer"><a href="<?php the_permalink() ?>"> Read Full Post </a></span>
</div>


</div>

		<?php endwhile; ?>

 <div id="navigation">
<?php

if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
?>
</div>

	<?php else : ?>


<h1>Not Found</h1>


<div class="cover">
<div class="entry">
		<p>Sorry, no post matched your criteria. Try a different search?</p>
				
</div>

</div>
<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>