<?php
// =============================== Klasik Advanced Posts widget ======================================
class Klasik_AdvancedPostsWidget extends WP_Widget {
    /** constructor */

	function Klasik_AdvancedPostsWidget() {
		$widget_ops = array('classname' => 'widget_klasik_advancedposts', 'description' => __('KlasikThemes Advanced Posts','klasik') );
		$this->WP_Widget('klasik-advancedposts-widget', __('KlasikThemes Advanced Posts','klasik'), $widget_ops);
	}


  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		$category = apply_filters('widget_category', $instance['category']);
		$cols = apply_filters('widget_cols', empty($instance['cols']) ? '' : $instance['cols']);
		$showposts = apply_filters('widget_showpost', empty($instance['showpost']) ? '' : $instance['showpost']);
		$longdesc = apply_filters('widget_longdesc', empty($instance['longdesc']) ? '' : $instance['longdesc']);
		$customclass = apply_filters('widget_customclass', empty($instance['customclass']) ? '' : $instance['customclass']);
		
		$instance['category'] = esc_attr(isset($instance['category'])? $instance['category'] : "");
		global $wp_query;
		
		$longdesc = (!is_numeric($longdesc) || empty($longdesc))? 0 : $longdesc;
		$showposts = (!is_numeric($showposts))? get_option('posts_per_page') : $showposts;
		
		$cols = intval($cols);
		
		if(!is_numeric($cols) || $cols < 1 || $cols > 6){
			$cols = 4;
		}
		
        			 echo $before_widget; 
			  		if ( $title!='' )
                        echo $before_title . esc_html($title) . $after_title;
						
					$output = "";

					$output .='<div class="klasik-advancedpost-widget '.$customclass.'">';
						$output .='<div class="row">';

							if($cols==1){
								$colclass = "twelve";
							}elseif($cols==2){
								$colclass = "one_half";
							}elseif($cols==3){
								$colclass = "one_third";
							}elseif($cols==4){	
								$colclass = "one_fourth";
							}elseif($cols==5){
								$colclass = "one_fifth";
							}elseif($cols==6){
								$colclass = "one_sixth";
							}
							
							$temp = $wp_query;
							$wp_query= null;
							$wp_query = new WP_Query();
							$args = array(
								"post_type" => "post",
								"showposts" => $showposts
							);
							
							if( $category!="" ){
								$args['tax_query'] = array(
									array(
										'taxonomy' => 'category',
										'field' => 'id',
										'terms' => $category
									)
								);
							}
							
							$wp_query->query($args);
							global $post;
							
							$tpl ='<div id="advpost-%%ID%%" class="tpl1 %%CLASS%%">';
								$tpl .='<div class="recent-item">';
								$tpl .= '<div class="recent-thumb">%%THUMB%%</div>';
								
								$tpl .= '<h3 class="recent-title"><a href="%%LINK%%">%%TITLE%%</a></h3>';
								$tpl .= '<span class="smalldate">%%DATE%%</span>';
								$tpl .= '<div class="sep"></div>';

								$tpl .='<div class="recent-text">%%TEXT%%</div>';
								
								$tpl .='<div class="recent-link">';
									$tpl .= '<a href="%%LINK%%" class="more-link">'.__('Read More','klasik').'</a>';
								$tpl .='</div>';
								$tpl .='<div class="clear"></div>';
								
								$tpl .='</div>';
							$tpl .='</div>';
							$tpl = apply_filters( 'klasik_advancedpost_item_template1', $tpl );
							
							$tpl2 ='<div id="advpost-%%ID%%" class="tpl2 %%CLASS%%">';
								$tpl2 .='<div class="recent-item">';
								$tpl2 .= '<div class="recent-thumb">%%THUMB%%</div>';
								
								$tpl2 .= '<h3 class="recent-title"><a href="%%LINK%%">%%TITLE%%</a></h3>';
								$tpl2 .= '<span class="smalldate">%%DATE%%</span>';
								$tpl2 .='<div class="clear"></div>';
								
								$tpl2 .='</div>';
							$tpl2 .='</div>';
							$tpl2 = apply_filters( 'klasik_advancedpost_item_template2', $tpl2 );
							
							if ($wp_query->have_posts()) : 
								$x = $y = 0;
								while ($wp_query->have_posts()) : $wp_query->the_post(); 
								
									$custom = get_post_custom($post->ID);
									$cf_thumb = get_the_post_thumbnail($post->ID, 'thumbnail', array('class' => 'alignleft'));
									$cf_extlink = (isset($custom['klasik_link'][0]))? $custom['klasik_link'][0] : "";
				
									$x++;
									$y++;
									
									if($y<=$cols){
										$template = $tpl;
										$x = 0;
										$noclass = 'post'.$y;
									}else{
										$template = $tpl2;
										$noclass = 'post'.$x;
									}
									
									if($x%$cols==0 && $x!=0){
										$omega = "omega";
									}elseif($x%$cols==1 && $x!=0){
										$omega = "alpha";
									}else{
										$omega = "";
									}
									
									if(has_post_thumbnail($post->ID) ){
										$thumb = get_the_post_thumbnail($post->ID, 'widget-advancedpost', array('class' => 'advpostimg'));
										$thumb2 = get_the_post_thumbnail($post->ID, 'thumbnail', array('class' => 'advpostimg2'));
									}else{
										$thumb = $thumb2 = "";
									}
				
									//POSTID
									$template = str_replace( '%%ID%%', $post->ID, $template );
									
									//POSTCLASS
									$postclass  = 'columns ';
									$postclass .= $colclass.' ';
									$postclass .= $noclass.' ';
									$postclass .= $omega;
									$template = str_replace( '%%CLASS%%', $postclass, $template );
									
									//POSTTITLE
									$posttitle  = '';
									$posttitle .= get_the_title();
									$template   = str_replace( '%%TITLE%%', $posttitle, $template );
									
									//POSTDATE
									$postdate  = '';
									$postdate .= get_the_time( get_option('date_format') );
									$template   = str_replace( '%%DATE%%', $postdate, $template );
									
									//POSTLINK
									$postlink = get_permalink();
									$template = str_replace( '%%LINK%%', $postlink, $template );
									
									//POSTCATEGORY
									$categories = get_the_category();
									$separator = apply_filters( 'klasik_advancedpost_cat_separator', ', ' );
									$atitle = apply_filters( 'klasik_advancedpost_cat_linktitle', __( "View all posts in %s" ) );
									$postcat = '';
									if($categories){
										foreach($categories as $category) {
											$postcat .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( $atitle, $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
										}
									}
									$template = str_replace( '%%CATEGORY%%', $postcat, $template );
									
									//POSTAUTHOR
									$postauthor = get_the_author();
									$template = str_replace( '%%AUTHOR%%', $postauthor, $template );
									
									//POSTAUTHORLINK
									$postauthorlink = get_author_posts_url( get_the_author_meta( 'ID' ) );
									$template = str_replace( '%%AUTHOR%%', $postauthorlink, $template );
									
									//POSTTHUMB
									$postthumb = '';
									$postthumb .= $thumb;
									$template = str_replace( '%%THUMB%%', $postthumb, $template );
									
									//POSTTHUMB2
									$postthumb2 = '';
									$postthumb2 .= $thumb2;
									$template = str_replace( '%%THUMB2%%', $postthumb2, $template );
									
									//POSTTEXT
									$posttext = '';
									if($longdesc>0){
										$excerpt = klasik_string_limit_char(get_the_excerpt(), $longdesc);
									}else{
										$excerpt = get_the_excerpt();
									}
									$posttext .= $excerpt;
									$template = str_replace( '%%TEXT%%', $posttext, $template );
									
									$output .= $template;
									
								endwhile;
						
							endif;
							$wp_query = null; $wp_query = $temp; wp_reset_query();
						$output .='</div>';
						$output.='<div class="clear"></div>';
					$output .='</div>';
						 
					echo do_shortcode($output);
					echo $after_widget; 

    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				

        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		$instance['title'] = (isset($instance['title']))? $instance['title'] : "";
		$instance['category'] = (isset($instance['category']))? $instance['category'] : "";
		$instance['cols'] = (isset($instance['cols']))? $instance['cols'] : "";
		$instance['showpost'] = (isset($instance['showpost']))? $instance['showpost'] : "";
		$instance['longdesc'] = (isset($instance['longdesc']))? $instance['longdesc'] : "";
		$instance['customclass'] = (isset($instance['customclass']))? $instance['customclass'] : "";
					
        $title = esc_attr($instance['title']);
		$category = esc_attr($instance['category']);
		$cols = esc_attr($instance['cols']);
		$longdesc = esc_attr($instance['longdesc']);
		$customclass = esc_attr($instance['customclass']);
		$showpost = esc_attr($instance['showpost']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
            <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:', 'klasik'); ?><br />
			<?php 
			$args = array(
			'selected'         => $category,
			'echo'             => 1,
			'name'             =>$this->get_field_name('category')
			);
			wp_dropdown_categories( $args );
			?>
			</label></p>
            
            <p><label for="<?php echo $this->get_field_id('cols'); ?>"><?php _e('Number of Columns:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('cols'); ?>" name="<?php echo $this->get_field_name('cols'); ?>" type="text" value="<?php echo $cols; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('showpost'); ?>"><?php _e('Number of Post:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('showpost'); ?>" name="<?php echo $this->get_field_name('showpost'); ?>" type="text" value="<?php echo $showpost; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('longdesc'); ?>"><?php _e('Length of Description Text:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('longdesc'); ?>" name="<?php echo $this->get_field_name('longdesc'); ?>" type="text" value="<?php echo $longdesc; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('customclass'); ?>"><?php _e('Custom Class:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('customclass'); ?>" name="<?php echo $this->get_field_name('customclass'); ?>" type="text" value="<?php echo $customclass; ?>" /></label></p>

        <?php
    }

} // class  Widget