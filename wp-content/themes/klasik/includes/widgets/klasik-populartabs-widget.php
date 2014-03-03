<?php
// =============================== Klasik Tabs widget ======================================

class Klasik_PopularTabsWidget extends WP_Widget {
    /** constructor */

	function Klasik_PopularTabsWidget() {
		$widget_ops = array('classname' => 'widget_klasik_tabs', 'description' => __('KlasikThemes Popular Tabs','klasik') );
		$this->WP_Widget('klasik-tabs', __('KlasikThemes Popular Tabs','klasik'), $widget_ops);
	}


  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
		$showpopular = apply_filters('widget_showpopular', $instance['showpopular']);
		$showlatest = apply_filters('widget_showlatest', $instance['showlatest']);
		$showcomment = apply_filters('widget_showcomment', $instance['showcomment']);

		
		global $wp_query;
        ?>
              <?php echo $before_widget; ?>
              <div class="tabcontainer">
                  	
                    <ul class="tabs">
                        <li class="tab0"><a href="#tab0"><?php _e('Popular','klasik'); ?></a></li>
                        <li class="tab1"><a href="#tab1"><?php _e('Latest','klasik'); ?></a></li>
                        <li class="tab2"><a href="#tab2"><?php _e('Comments','klasik'); ?></a></li>
                    </ul> 
                    <div class="clear"></div>
					<div id="tab-body">	
                    		<div id="tab0" class="tab-content klasik-recentpost-widget">
                                    <?php 
                                        if($showpopular==""){$showpopular=4;}
                                        $wp_query->query('showposts='.$showpopular.'&orderby=comment_count&ignore_sticky_posts=1');
                                        global $post;
                                    ?>
                                    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                                    <div class="recent-item">
                                       
                                        
                                        <?php
                                        if(has_post_thumbnail($post->ID) ){
                                            $thumb = get_the_post_thumbnail($post->ID, 'widget-post', array('class' => 'frame'));
                                        }else{
                                            $thumb ="";
                                        }
										
										if($thumb!=""){
                                        	echo '<div class="recent-thumb">'.$thumb.'</div>';
										}
                                        ?>
                                        
                                        <h3 class="recent-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'klasik' ), the_title_attribute( 'echo=0' ) ); ?>" data-rel="bookmark"><?php the_title(); ?></a></h3>
                                        
                                        <span class="smalldate"><?php  the_time('F j, Y') ?></span>
                                        
                                        <span class="clear"></span>
                                    </div>
                                    <?php endwhile; ?>
                                <?php  wp_reset_query();?>
                            </div>
                            <div id="tab1" class="tab-content klasik-recentpost-widget">
                                    <?php 
                                        if($showlatest==""){$showlatest=4;}
                                        $wp_query->query("showposts=".$showlatest);
                                        global $post;
                                    ?>
                                    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                                    <div class="recent-item">
                                        <?php
                                        if(has_post_thumbnail($post->ID) ){
                                            $thumb = get_the_post_thumbnail($post->ID, 'widget-post', array('class' => 'frame'));
                                        }else{
                                            $thumb ="";
                                        }
                                        
										if($thumb!=""){
                                        	echo '<div class="recent-thumb">'.$thumb.'</div>';
										}
                                        ?>
                                       
                                        <h3 class="recent-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'klasik' ), the_title_attribute( 'echo=0' ) ); ?>" data-rel="bookmark"><?php the_title(); ?></a></h3>
                                        
                                        <span class="smalldate"><?php  the_time('F j, Y') ?></span>
                                        <span class="clear"></span>
                                    </div>
                                    <?php endwhile; ?>
                                <?php  wp_reset_query();?>
                            </div>
                    		<div id="tab2" class="tab-content">
								<?php
                                    global $wpdb, $comments, $comment;
                                    extract($args, EXTR_SKIP);
                                    if ( !$number = (int) $instance['showcomment'] )
                                        $number = 4;
                                    else if ( $number < 1 )
                                        $number = 1;
                                    else if ( $number > 15 )
                                        $number = 15;
                                        
                                    $comment_len = 30;
                            
                                    if ( !$comments = wp_cache_get( 'recent_comments', 'widget' ) ) {
                                        $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' and comment_type not in ('pingback','trackback') ORDER BY comment_date_gmt DESC LIMIT 15");
                                        wp_cache_add( 'recent_comments', $comments, 'widget' );
                                    }
                            
                                    $comments = array_slice( (array) $comments, 0, $number );
                                ?>
                                <ul>
                                    <?php if ( $comments ) : foreach ( (array) $comments as $comment) :?>
                                    <li>
                                    <?php
                                    echo get_avatar( $comment, 55 ).'<h3 class="name">';
                                    printf( __( '%s ', 'klasik' ), sprintf( '%s', get_comment_author_link() ) ); 
                                    echo '</h3><a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">';
                                    echo strip_tags(substr(apply_filters('get_comment_text', $comment->comment_content), 0, $comment_len)); if (strlen($comment->comment_content) > $comment_len) echo '...'; ;
                                    echo '</a>';
                                    ?>
                                    <span class="clear"></span>
                                    
                                    </li>
                                    
                                    <?php endforeach; endif;?>
                                </ul>
                            </div>
						</div>		
					</div>			
              <?php echo $after_widget; ?>
			 
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		$instance['showpopular'] = (isset($instance['showpopular']))? $instance['showpopular'] : "";
		$instance['showlatest'] = (isset($instance['showlatest']))? $instance['showlatest'] : "";
		$instance['showcomment'] = (isset($instance['showcomment']))? $instance['showcomment'] : "";

					

        $showpopular = esc_attr($instance['showpopular']);
		$showlatest = esc_attr($instance['showlatest']);
		$showcomment = esc_attr($instance['showcomment']);

        ?>
        
        
            <p><label for="<?php echo $this->get_field_id('showpopular'); ?>"><?php _e('Number of Popular:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('showpopular'); ?>" name="<?php echo $this->get_field_name('showpopular'); ?>" type="text" value="<?php echo $showpopular; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('showlatest'); ?>"><?php _e('Number of Latest:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('showlatest'); ?>" name="<?php echo $this->get_field_name('showlatest'); ?>" type="text" value="<?php echo $showlatest; ?>" /></label></p>
        
            <p><label for="<?php echo $this->get_field_id('showcomment'); ?>"><?php _e('Number of Comment:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('showcomment'); ?>" name="<?php echo $this->get_field_name('showcomment'); ?>" type="text" value="<?php echo $showcomment; ?>" /></label></p>
                 
        <?php 
    }

} // class  Widget