<?php
// =============================== Klasik Portfolio Filter widget ======================================
class Klasik_PFilterWidget extends WP_Widget {
    /** constructor */

	function Klasik_PFilterWidget() {
		$widget_ops = array('classname' => 'widget_klasik_pfilter', 'description' => __('KlasikThemes Portfolio Filter','klasik') );
		$this->WP_Widget('klasik-theme-pfilter-widget', __('KlasikThemes Portfolio Filter','klasik'), $widget_ops);
	}


  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		$cats = apply_filters('widget_category', empty($instance['category']) ? array() : $instance['category']);
		$cols = apply_filters('widget_cols', empty($instance['cols']) ? '' : $instance['cols']);
		$showposts = apply_filters('widget_showpost', empty($instance['showpost']) ? '' : $instance['showpost']);
		$longdesc = apply_filters('widget_longdesc', empty($instance['longdesc']) ? '' : $instance['longdesc']);
		$customclass = apply_filters('widget_customclass', empty($instance['customclass']) ? '' : $instance['customclass']);
		$instance['category'] = isset($instance['category'])? $instance['category'] : "";
		global $wp_query;
        ?>
              <?php echo $before_widget; 
			  		if ( $title!='' )
                        echo $before_title . esc_html($title) . $after_title;
						
					$cols = intval($cols);
		
					if(!is_numeric($cols) || $cols < 1 || $cols > 6){
						$cols = 4;
					}
					
					$longdesc = (!is_numeric($longdesc) || empty($longdesc))? 0 : $longdesc;
					
					$showposts = (!is_numeric($showposts))? get_option('posts_per_page') : $showposts;
					$categories = $cats;
					
					echo '<div class="klasik-portfolio '.$customclass.'">';
					
						$approvedcat = array();
						$sideoutput = "";
						if( count($categories)!=0 ){
							foreach ($categories as $key) {
								$catname = get_term_by("slug",$key,"category");
								$approvedcat[] = $key;
							}
						}
			
						$approvedcatID = array();
						$isotopeclass = "";
						if( count($categories)>1 ){
							echo '<div class="frame-filter">';
								echo '<div class="filterlist">';
									echo '<ul id="filter" class="controlnav">';
										echo '<li class="segment-1 selected-1 current first"><a href="#" data-filter="*">'.__('All Categories','klasik').'</a></li>';
										foreach ($categories as $key) {
											$catname = get_term_by("slug",$key,"category");
											echo '<li class="segment-1"><a href="#" class="'.$catname->slug.'" data-filter="'.$catname->slug.'">'.$catname->name.'</a></li>';
											$approvedcatID[] = $key;
										}
									echo '</ul>';
								echo '</div>';
							echo '</div>';
							echo '<div class="clear"></div>';
							$isotopeclass = "isotope portfoliolist";
							$showposts = -1;
						}elseif( count($categories)==1 ){
							foreach ($categories as $key) {
								$catname = get_term_by("slug",$key,"category");
								$approvedcatID[] = $key;
							}
						}
					
						$temp = $wp_query;
						$wp_query= null;
						$wp_query = new WP_Query();
						
						$args = array(
							'post_type' => 'post',
							'showposts' => $showposts,
							'orderby' => 'date'
						);
			
						if( count($approvedcatID) ){
							$args['tax_query'] = array(
								array(
									'taxonomy' => 'category',
									'field' => 'slug',
									'terms' => $approvedcat
								)
							);
						}
						
						$wp_query->query($args);
						global $post;
						
						$tpl  = '<div data-id="id-%%ID%%" class="%%CLASS%%" data-type="%%KEY%%">';
						
							$tpl .= '<div class="klasik-pf-img"><div class="shadowBottom">';
								$tpl .= '<a class="pfzoom" href="%%FULLIMG%%" data-rel=prettyPhoto[mixed] title="%%FULLIMGTITLE%%"><span class="rollover"></span>%%THUMB%%</a>';
								$tpl .= '<div class="clear"></div>';
							$tpl .= '</div></div>';
							
							$tpl .= '<div class="klasik-pf-text">';
								$tpl .='<h3 class="pftitle"><a href="%%LINK%%" title="%%TITLE%%">';
									$tpl .='<span>%%TITLE%%</span>';
								$tpl .='</a></h3>';
								$tpl .='<div class="textcontainer">%%TEXT%%</div>';
								$tpl .='<div class="more-container"><a href="%%LINK%%" class="more-link">'.__('Read More','klasik').'</a></div>';
							$tpl .= '</div>';
							
							$tpl .= '<div class="clear"></div>';
						$tpl .= '</div>';
						$tpl = apply_filters( 'klasik_pfilter_item_template', $tpl );
						
						if ($wp_query->have_posts()) : 
							$x = 0;
							$output = "";
							$output .= '<div class="row '.$isotopeclass.'">';
							while ($wp_query->have_posts()) : $wp_query->the_post(); 
								
								$template = $tpl;
								
								$custom = get_post_custom($post->ID);
								
								$cf_customdesc 		= get_the_title() ;
								
								$x++;
				
								if($cols==1){
									$colclass = "twelve columns";
								}elseif($cols==2){
									$colclass = "one_half columns";
								}elseif($cols==3){
									$colclass = "one_third columns";
								}elseif($cols==4){	
									$colclass = "one_fourth columns";
								}elseif($cols==5){
									$colclass = "one_fifth columns";
								}elseif($cols==6){
									$colclass = "one_sixth columns";
								}
								
								if($x%$cols==0){
									$omega = "omega";
								}elseif($x%$cols==1){
									$omega = "alpha";
								}else{
									$omega = "";
								}				
								
								$itemclass = $colclass .' '. $omega;
								
								//get post-thumbnail attachment
								$attachments = get_children( array(
									'post_parent' => $post->ID,
									'post_type' => 'attachment',
									'orderby' => 'menu_order',
									'post_mime_type' => 'image')
								);
								
								$fullimageurl = '';
								$cf_thumb2 = '';
								foreach ( $attachments as $att_id => $attachment ) {
									$getimage = wp_get_attachment_image_src($att_id, 'widget-portfolio', true);
									$fullimage = wp_get_attachment_image_src($att_id, 'full', true);
									$portfolioimage = $getimage[0];
									$cf_thumb2 ='<img src="'.$portfolioimage.'" class="frame" alt="" />';
									$thethumblb = $portfolioimage;
									$fullimageurl = $fullimage[0];
								}
								 
								
								//thumb image
								if(has_post_thumbnail($post->ID)){
									$cf_thumb = get_the_post_thumbnail($post->ID, 'widget-portfolio');
									$thumb_id = get_post_thumbnail_id($post->ID);
									$args = array(
										'post_type' => 'attachment',
										'post_status' => null,
										'include' => $thumb_id
									);
									$fullimage = wp_get_attachment_image_src($thumb_id, 'full', true);
									$fullimageurl = $fullimage[0];
									
									$thumbnail_image = get_posts($args);
									if ($thumbnail_image && isset($thumbnail_image[0])) {
										$cf_customdesc = $thumbnail_image[0]->post_content;
									}
								}else{
									$cf_thumb = $cf_thumb2;
								}
								
								$ids = get_the_ID();
								
								$addclass="";
							
								$catinfos = get_the_terms($post->ID,'category');
								$key = "";
								if($catinfos){
									foreach($catinfos as $catinfo){
										$key .= " ".$catinfo->slug;
									}
									$key = trim($key);
								}
								
								//PORTFOLIOID
								$template = str_replace( '%%ID%%', $post->ID, $template );
								
								//PORTFOLIOCLASS
								$pfclass  = 'item ';
								$pfclass .= $itemclass.' ';
								$pfclass .= $key;
								$template = str_replace( '%%CLASS%%', $pfclass, $template );
								
								//PORTFOLIOKEY
								$pfkey = $key;
								$template = str_replace( '%%KEY%%', $pfkey, $template );
								
								//PORTFOLIOFULLIMAGE
								$pffullimg = $fullimageurl;
								$template = str_replace( '%%FULLIMG%%', $pffullimg, $template );
								
								//PORTFOLIOIMGTITLE
								$pffullimgtitle = $cf_customdesc;
								$template = str_replace( '%%FULLIMGTITLE%%', $pffullimgtitle, $template );
								
								//PORTFOLIOLINK
								$pflink = get_permalink();
								$template = str_replace( '%%LINK%%', $pflink, $template );
								
								//PORTFOLIOIMAGE
								$pfthumb = '';
								$pfthumb .= $cf_thumb;
								$template = str_replace( '%%THUMB%%', $pfthumb, $template );
	
								
								//PORTFOLIOTITLE
								$pftitle  = '';
								$pftitle .= get_the_title();
								$template = str_replace( '%%TITLE%%', $pftitle, $template );
	
								//PORTFOLIOTEXT
								$pftext = '';
								if($longdesc>0){
									$excerpt = klasik_string_limit_char(get_the_excerpt(), $longdesc);
								}else{
									$excerpt = get_the_excerpt();
								}
								$pftext .= $excerpt;
								$template = str_replace( '%%TEXT%%', $pftext, $template );
								
								//PORTFOLIOCATEGORY
								$pfcat = '';
								$categories = get_the_category();
								$separator = ', ';
								if($categories){
									foreach($categories as $category) {
										$pfcat .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", 'klasik' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
									}
								}
								$template = str_replace( '%%CATEGORY%%', trim($pfcat, $separator), $template );
							
							$output .= $template;
							endwhile;
							$output .= '</div>';
							$wp_query = null; $wp_query = $temp; wp_reset_query();
							
							echo $output;
						endif;
						$wp_query = null; $wp_query = $temp; wp_reset_query();
						echo '<div class="clear"></div>';
					echo '</div>';
				?>
			
              <?php echo $after_widget; ?>
			 
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				

        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		$instance['title'] = (isset($instance['title']))? $instance['title'] : "";
		$instance['category'] = (isset($instance['category']))? $instance['category'] : array();
		$instance['cols'] = (isset($instance['cols']))? $instance['cols'] : "";
		$instance['showpost'] = (isset($instance['showpost']))? $instance['showpost'] : "";
		$instance['longdesc'] = (isset($instance['longdesc']))? $instance['longdesc'] : "";
		$instance['customclass'] = (isset($instance['customclass']))? $instance['customclass'] : "";
		
        $title = esc_attr($instance['title']);
		$categories = $instance['category'];
		$cols = esc_attr($instance['cols']);
		$showpost = esc_attr($instance['showpost']);
		$customclass = esc_attr($instance['customclass']);
		$longdesc = esc_attr($instance['longdesc']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
            <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:', 'klasik'); ?><br />
			<?php 
			$chkvalue = $categories;
			
			$portcategories = get_categories();
			$returnstring = '';
			foreach($portcategories as $category){
				$checkedstr="";
				if(in_array($category->slug,$chkvalue)){
					$checkedstr = 'checked="checked"';
				}
				$returnstring .= '<div style="float:left;width:48%;">';
				$returnstring .= '<label for="'. $this->get_field_id('category')."-". $category->slug .'">';
					$returnstring .= '<input type="checkbox" value="'. $category->slug .'" name="'. $this->get_field_name('category'). '['.$category->slug.']" id="'. $this->get_field_id('category')."-". $category->slug . '" '.$checkedstr.' />&nbsp;&nbsp;'. $category->name;
				$returnstring .= '</label>';
				$returnstring .= '</div>';
			}
			$returnstring .= '<div style="clear:both;"></div>';
			
			echo $returnstring;
			?>
			</label></p>
			
            <p><label for="<?php echo $this->get_field_id('cols'); ?>"><?php _e('Number of Columns:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('cols'); ?>" name="<?php echo $this->get_field_name('cols'); ?>" type="text" value="<?php echo $cols; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('showpost'); ?>"><?php _e('Number of Post:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('showpost'); ?>" name="<?php echo $this->get_field_name('showpost'); ?>" type="text" value="<?php echo $showpost; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('longdesc'); ?>"><?php _e('Length of Description Text:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('longdesc'); ?>" name="<?php echo $this->get_field_name('longdesc'); ?>" type="text" value="<?php echo $longdesc; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('customclass'); ?>"><?php _e('Custom Class:', 'klasik'); ?> <input class="widefat" id="<?php echo $this->get_field_id('customclass'); ?>" name="<?php echo $this->get_field_name('customclass'); ?>" type="text" value="<?php echo $customclass; ?>" /></label></p>
            
        <?php
    }

} // class  Widget