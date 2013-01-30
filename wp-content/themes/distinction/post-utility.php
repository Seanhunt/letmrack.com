<?php if ( is_single() ) : ?>
<div class="term-lists">

	<?php if ( count( get_the_category() ) ) : ?>
					<span class="cat-links">
						<?php printf( __( '<span class="%1$s">Categories:</span> %2$s', 'wpnj_distinction' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
					</span>

				<?php endif; ?>
				<?php
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ):
				?>
					<span class="tag-links">
						<?php printf( __( '<span class="%1$s">Tags:</span> %2$s', 'wpnj_distinction' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
					</span>
                <?php endif; ?>

</div>
<?php endif; ?>

<div class="utility">
	<?php if(function_exists('has_post_format'))
	$post_id = get_the_ID();
	$format = get_post_format( $post_id );
	$format_link = get_post_format_link($format);
	?>
    
    <span class="comments-link">
    <?php if ($format == '') { ?>
    <span class="icon-default format-icons">post</span>
    <?php }else{ ?>
    <a class="icon-<?php echo $format; ?> format-icons" href="<?php echo $format_link; ?>"><?php echo $format; ?></a>
    <?php } ?>

    <span class="meta-sep">|</span>
    
    	<?php comments_popup_link( __( 'Leave a comment', 'distinction' ), __( '1 Comment', 'wpnj_distinction' ), __( '% Comments', 'distinction' ), __( 'Comments Off', 'wpnj_distinction' ) ); ?>
        <?php edit_post_link( __( 'Edit', 'wpnj_distinction' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>

    </span>
    
    <?php wpnj_distinction_posted_on(); ?>
</div><!-- .utility -->