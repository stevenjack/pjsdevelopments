<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Klasik
 * @since Klasik 1.0
 */
 
 global $post, $more;
 $more = 0;
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    	<div class="articlecontainer">

            <h2 class="posttitle"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'klasik' ), the_title_attribute( 'echo=0' ) ); ?>" data-rel="bookmark"><?php the_title(); ?></a></h2>
            <div class="entry-utility">
            	<div class="date"><?php the_time(get_option('date_format')); ?></div> 
                <div class="user"><?php _e('by','klasik'); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) );?>"><?php the_author();?></a></div>
                <div class="tag"> <?php _e('in','klasik'); ?> <?php the_category(', '); ?></div>
                <div class="clear"></div>  
            </div> 
            
            <div class="entry-content">
                <?php the_content(''); ?>
                <a href="<?php the_permalink(); ?>" class="more"><?php _e('Continue Reading','klasik'); ?></a>
            </div>
        
            <div class="clear"></div>
        </div>
	</article><!-- end post -->
    
    
