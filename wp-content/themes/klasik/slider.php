<!-- SLIDER -->
<div id="outerslider">
    <div class="container">
    	<div class="row">
        <div id="slidercontainer" class="twelve columns">
            
            	<section id="slider">
                	
                    <div id="slideritems">
                    
						<?php
						$nivocaption = "";
						$output="";
						
						$defaultbg = get_stylesheet_directory_uri().'/images/bgslider.jpg';
						
                        global $wp_query, $post;
                        while ($wp_query->have_posts()) : $wp_query->the_post(); 
                        
                        $custom = get_post_custom($post->ID);
                       	$cf_slideurl = (isset($custom["slider-link"][0]))?$custom["slider-link"][0] : "";
						$cf_disablelink = (isset($custom["disable-slider-link"][0]))? $custom["disable-slider-link"][0] : "";
						$cf_bgslider = (isset($custom["slider-background"][0]))? $custom["slider-background"][0] : $defaultbg;
						$cf_subtitle = (isset($custom["slider-subtitle"][0]))? $custom["slider-subtitle"][0] : "";
						$cf_video = (isset($custom["slider-video"][0]))?$custom["slider-video"][0] : "";
                        
                        $output="";
						
						//slider images
						if(has_post_thumbnail( get_the_ID()) || $cf_thumb!=""){
							if($cf_thumb!=""){
								$cf_bgslider = '<img src="'.$cf_thumb.'" alt="" />';
							}else{
								$postthumbnailid = get_post_thumbnail_id($post->ID);
								$sliderimgsrc = wp_get_attachment_image_src($postthumbnailid,'image-slider');
								$cf_bgslider = $sliderimgsrc[0];
							}
						}elseif($cf_video!=''){
							$cf_bgslider = get_template_directory_uri().'/images/trans.gif';
						}else{
							$cf_bgslider = 'http://placehold.it/1140x502"';
						}
						
                        $output .='<div class="slider-img" data-src="'.$cf_bgslider.'">';
							
							if($cf_video!=""){
								$output .= '<iframe id="slider-'.$post->ID.'" width="100%" height="100%" src="'.$cf_video.'" frameborder="0" allowfullscreen></iframe>';
							}else{
							
								if($cf_slideurl==""){
									$cf_slideurl = get_permalink();
								}
								
							   //slider text
							   $output .='<div class="flex-caption moveFromBottom">';
								
								if($cf_slideurl!="" && !$cf_disablelink){
									$output .='<div class="slider-title"><a href="'.$cf_slideurl.'">' . get_the_title() . '</a></div>';
								}else{
									$output .='<div class="slider-title">' . get_the_title() . '</div>';
								}
								
								if(get_the_content()!=""){
									$output .='<div class="slidertext">';
									$output .= get_the_content();
									$output .='</div>';
								}
								
								$output .='</div>';
							}
                        
						$output .= '</div>';
                        echo $output;
                        
                        endwhile;
                        wp_reset_query();
                        ?>
                    </div>
                    <div class="clear"></div>
                </section>
                
            </div>
    	</div>
    </div>
</div>
<!-- END SLIDER -->