<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Distinction
 * @since Distinction 1.0
 */
?>

<div id="post-wrapper" class="wrap">
<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
    	<div class="post-inner">
            <h1 class="title"><?php _e( 'Not Found', 'wpnj_distinction' ); ?></h1>
            <div class="entry">
                <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'wpnj_distinction' ); ?></p>
                <?php get_search_form(); ?>
            </div><!-- .entry-content -->
        </div>
	</div><!-- #post-0 -->
<?php endif; ?>

<?php
	/* Start the Loop.
	 *
	 * In Distinction we use the same loop in multiple contexts.
	 * It is broken into several parts: when we're displaying
	 * posts for a particular post fomrat or that are in the
	 * gallery or asides category, and finally all other posts.
	 *
	 * This loop is used for the index, search, and all archive based pages.
	 */ ?>
<?php while ( have_posts() ) : the_post(); ?>

<?php /* How to display VIDEO posts. */ ?>

<?php if ( ( function_exists( 'get_post_format' ) && 'video' == get_post_format( $post->ID ) ) ) : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<div class="post-inner">
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>
                
        		</div><!-- .entry -->
                
                <h2 class="title"><?php the_title(); ?></h2>
                
                    <?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->         
            
		</div><!-- #post-## -->

<?php /* End VIDEO posts. */ ?>

<?php /* How to display QUOTE posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'quote' == get_post_format( $post->ID ) ) ) : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
        	<div class="post-inner">
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                     <?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->          
            
		</div><!-- #post-## -->

<?php /* End QUOTE posts. */ ?>

<?php /* How to display LINK posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'link' == get_post_format( $post->ID ) ) ) : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<div class="post-inner">
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->         
            
		</div><!-- #post-## -->

<?php /* End LINK posts. */ ?>

<?php /* How to display AUDIO posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'audio' == get_post_format( $post->ID ) ) ) : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<div class="post-inner">
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->        
            
		</div><!-- #post-## -->

<?php /* End AUDIO posts. */ ?>

<?php /* How to display IMAGE posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'image' == get_post_format( $post->ID ) ) ) : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<div class="post-inner">
                <div class="entry">
                
      				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>
					
        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->        
            
		</div><!-- #post-## -->

<?php /* End IMAGE posts. */ ?>

<?php /* How to display GALLERY posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) || in_category( _x( 'gallery', 'gallery category slug', 'wpnj_distinction' ) )  ) : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-inner">

			<div class="entry">
	
				<?php 
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
				?>
						<div class="gallery-thumb">
							<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
						</div><!-- .gallery-thumb -->
                        <h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'distinction' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
						<p><em><?php printf( __( 'This gallery contains <a %1$s>%2$s photos</a>.', 'wpnj_distinction' ),
								'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'distinction' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
								$total_images
							); ?></em></p>

				<?php endif; ?>
                
						<?php the_excerpt(); ?>

			</div><!-- .entry -->

				<?php get_template_part( 'post', 'utility' ); ?>
            
        	</div><!-- .post-inner -->
		</div><!-- #post-## -->

<?php /* End GALLERY posts. */ ?>

<?php /* How to display STATUS posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'status' == get_post_format( $post->ID ) ) ) : ?>


		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<div class="post-inner">
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->
                   
		</div><!-- #post-## -->

<?php /* End STATUS posts. */ ?>

<?php /* How to display ASIDE posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'aside' == get_post_format( $post->ID ) ) || in_category( _x( 'asides', 'asides category slug', 'wpnj_distinction' ) )  ) : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
        	<div class="post-inner">
            
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
               	 	<?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->
            
            
		</div><!-- #post-## -->

<?php /* End ASIDE posts. */ ?>

<?php /* How to display CHAT posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'chat' == get_post_format( $post->ID ) ) ) : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<div class="post-inner">
            <h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpnj_distinction' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->
            
		</div><!-- #post-## -->

<?php /* End CHAT posts. */ ?>

<?php else : ?>


		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<div class="post-inner">
                <h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpnj_distinction' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
    
                <div class="entry">
                
                    <?php the_post_thumbnail('thumbnail', array('class'=>'alignleft') ); ?>
					<?php the_excerpt(); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>
    
        		</div><!-- .entry -->
                     
                     <?php get_template_part( 'post', 'utility' ); ?>
    
				</div><!-- .post-inner -->    
            
		</div><!-- #post-## -->

<?php endif; // This was the if statement that broke the loop into several parts based on post formats and categories. ?>
<?php endwhile; // End the loop. ?>

</div>

<?php /* Display pagination to various pages when applicable */ ?>
<?php wpnj_distinction_pagination(); ?>