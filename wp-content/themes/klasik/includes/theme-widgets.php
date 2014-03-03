<?php
/**
 * Loads up all the widgets defined by this theme. Note that this function will not work for versions of WordPress 2.7 or lower
 *
 */

$path_pfilterwidget = get_template_directory() . '/includes/widgets/klasik-pfilter-widget.php';
if(file_exists($path_pfilterwidget)) include_once ($path_pfilterwidget);

$path_featureswidget = get_template_directory() . '/includes/widgets/klasik-features-widget.php';
if(file_exists($path_featureswidget)) include_once ($path_featureswidget);

$path_recentwidget = get_template_directory() . '/includes/widgets/klasik-recentposts-widget.php';
if(file_exists($path_recentwidget)) include_once ($path_recentwidget);

$path_advancedwidget = get_template_directory() . '/includes/widgets/klasik-advancedpost-widget.php';
if(file_exists($path_advancedwidget)) include_once ($path_advancedwidget);

$path_testiwidget = get_template_directory() . '/includes/widgets/klasik-testimonial-widget.php';
if(file_exists($path_testiwidget)) include_once ($path_testiwidget);

$path_imgcarouselwidget = get_template_directory() . '/includes/widgets/klasik-imagecarousel-widget.php';
if(file_exists($path_imgcarouselwidget)) include_once ($path_imgcarouselwidget);

$path_posttabwidget = get_template_directory() . '/includes/widgets/klasik-populartabs-widget.php';
if(file_exists($path_posttabwidget)) include_once ($path_posttabwidget);

if( function_exists('is_woocommerce')){
	$path_woofpwidget = get_template_directory() . '/includes/widgets/klasik-product-widget.php';
	if(file_exists($path_woofpwidget)) include_once ($path_woofpwidget);
}

add_action("widgets_init", "klasik_theme_widgets");

function klasik_theme_widgets() {
	register_widget("Klasik_PFilterWidget");
	register_widget("Klasik_FeaturesWidget");
	register_widget("Klasik_RecentPostsWidget");
	register_widget("Klasik_AdvancedPostsWidget");
	register_widget("Klasik_TestimonialWidget");
	register_widget("Klasik_PCarouselWidget");
	register_widget("Klasik_PopularTabsWidget");
	
	if( function_exists('is_woocommerce')){
		register_widget("Klasik_WooProductWidget");
	}
}