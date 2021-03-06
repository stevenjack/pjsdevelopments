<?php
/**
 * The template for displaying the home page panel. Requires SiteOrigin page builder plugin.
 *
 * @package so-current
 * @since so-current 1.0
 * @see http://siteorigin.com/page-builder/
 * @license GPL 2.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
		<article class="entry">
			<div class="single-entry-content">
				<div class="entry-content">
					<?php if(function_exists('siteorigin_panels_render')) echo siteorigin_panels_render('home'); ?>
				</div>
			</div>
		</article>
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_footer(); ?>