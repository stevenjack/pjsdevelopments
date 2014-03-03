<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Klasik
 * @since Klasik 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    	<div class="articlecontainer">
            <div class="entry-gallery">
				<?php
				
				//get post-thumbnail attachment
				$attachments = get_children( array(
					'post_parent' => get_the_ID(),
					'post_type' => 'attachment',
					'orderby' => 'menu_order',
					'post_mime_type' => 'image')
				);
				 $thethumblb = '';
				 foreach ( $attachments as $att_id => $attachment ) {
					$getimage = wp_get_attachment_image_src($att_id, 'thumbnail', true);
					$thumbimage  = $getimage[0];
					$thumbwidth  = $getimage[1];
					$thumbheight = $getimage[2];
					$cf_thumb2 ='<a href="'.get_attachment_link($att_id).'"><img src="'.$thumbimage.'" class="frame" width="'. $thumbwidth .'" height="'. $thumbheight .'" alt="" /></a>';
					$thethumblb .= '<div class="klasik-gallery-thumb">'.$cf_thumb2.'</div>';
				 }
				 
				 if($thethumblb!=''){
				 	$thethumblb = '<div class="klasik-gallery-container"><div class="row">'. $thethumblb .'<div class="clear"></div></div></div>';
				 }
				?>
                <h2 class="posttitle"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'klasik' ), the_title_attribute( 'echo=0' ) ); ?>" data-rel="bookmark"><?php the_title(); ?></a></h2>
                <div class="entry-utility">
                    <div class="date"><?php the_time(get_option('date_format')); ?></div> 
                    <div class="user"><?php _e('by','klasik'); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) );?>"><?php the_author();?></a></div>
                    <div class="tag"> <?php _e('in','klasik'); ?> <?php the_category(', '); ?></div>
                    <div class="clear"></div>  
                </div> 
                
                <?php echo $thethumblb; ?>
                <div class="entry-content">
                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink(); ?>" class="more"><?php _e('Continue Reading','klasik'); ?></a>
                </div>
            </div>
            <div class="clear"></div>
        </div>
		<div class="clear"></div>
	</article><!-- end post -->
    
    
