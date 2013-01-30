<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 *
 * @package WordPress
 * @subpackage Distinction
 * @since Distinction 1.0
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<div class="post-inner">
<?php /* How to display VIDEO posts. */ ?>

<?php if ( ( function_exists( 'get_post_format' ) && 'video' == get_post_format( $post->ID ) ) ) : ?>

		
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>
                
        		</div><!-- .entry -->
                
                <h1 class="title"><?php the_title(); ?></h1>
                
                    <?php get_template_part( 'post', 'utility' ); ?>

<?php /* End VIDEO posts. */ ?>

<?php /* How to display QUOTE posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'quote' == get_post_format( $post->ID ) ) ) : ?>

                <div class="entry">
                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>
        		</div><!-- .entry -->
                
                     <?php get_template_part( 'post', 'utility' ); ?>

<?php /* End QUOTE posts. */ ?>

<?php /* How to display LINK posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'link' == get_post_format( $post->ID ) ) ) : ?>

                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>

<?php /* End LINK posts. */ ?>

<?php /* How to display AUDIO posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'audio' == get_post_format( $post->ID ) ) ) : ?>

				<h1 class="title"><?php the_title(); ?></h1>

                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>

<?php /* End AUDIO posts. */ ?>

<?php /* How to display IMAGE posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'image' == get_post_format( $post->ID ) ) ) : ?>

                <div class="entry">
                
      				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>
					
        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>

<?php /* End IMAGE posts. */ ?>

<?php /* How to display GALLERY posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) || in_category( _x( 'gallery', 'gallery category slug', 'wpnj_distinction' ) )  ) : ?>
        
        	<h1 class="title"><?php the_title(); ?></h1>

			<div class="entry">
                
						<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

			</div><!-- .entry -->

				<?php get_template_part( 'post', 'utility' ); ?>

<?php /* End GALLERY posts. */ ?>

<?php /* How to display STATUS posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'status' == get_post_format( $post->ID ) ) ) : ?>

                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>

<?php /* End STATUS posts. */ ?>

<?php /* How to display ASIDE posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'aside' == get_post_format( $post->ID ) ) || in_category( _x( 'asides', 'asides category slug', 'wpnj_distinction' ) )  ) : ?>
            
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
               	 	<?php get_template_part( 'post', 'utility' ); ?>

<?php /* End ASIDE posts. */ ?>

<?php /* How to display CHAT posts. */ ?>
<?php elseif ( ( function_exists( 'get_post_format' ) && 'chat' == get_post_format( $post->ID ) ) ) : ?>

            <h1 class="title"><?php the_title(); ?></h1>
                <div class="entry">

                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>

        		</div><!-- .entry -->
                
                	<?php get_template_part( 'post', 'utility' ); ?>

<?php /* End CHAT posts. */ ?>

<?php else : ?>

                <h1 class="title"><?php the_title(); ?></h1>
    
                <div class="entry">
                
                    <?php the_post_thumbnail('thumbnail', array('class'=>'alignleft') ); ?>
					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpnj_distinction' ), 'after' => '</div>' ) ); ?>
    
        		</div><!-- .entry -->
                     
                     <?php get_template_part( 'post', 'utility' ); ?>


<?php endif; // This was the if statement that broke the loop into various parts for post_formats and caegories ?>
				        
 <?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					<div id="entry-author-info">
                    	<div class="post-inner">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2><?php printf( esc_attr__( 'About %s', 'wpnj_distinction' ), get_the_author() ); ?></h2>
							<p><?php the_author_meta( 'description' ); ?></p>
							<div id="author-link">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'wpnj_distinction' ), get_the_author() ); ?>
								</a>
							</div><!-- #author-link	-->
						</div><!-- #author-description -->
                        </div>
					</div><!-- #entry-author-info -->
<?php endif; ?>           
</div><!-- .post-inner -->    		</div><!-- #post-## -->           
                


<?php /* Display navigation to next/previous pages when applicable */ ?>

				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'wpnj_distinction' ) . '</span> %title' ); ?></div>
					<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'wpnj_distinction' ) . '</span>' ); ?></div>
				</div><!-- #nav-below -->

<?php comments_template( '', true ); ?>
<?php endwhile; // end of the loop. ?>