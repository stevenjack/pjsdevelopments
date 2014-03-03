jQuery(document).ready(function(){
	
	//=================================== FADE EFFECT ===================================//
	
	jQuery('.klasik-pf-img a').hover(
		function() {
			jQuery(this).find('.rollover').stop().fadeTo(500, 0.6);
		},
		function() {
			jQuery(this).find('.rollover').stop().fadeTo(500, 0);
		}
	
	);
	
	runcamera();

});

function runflexslider(){
	//=================================== FLEXSLIDER ===================================//
	jQuery('.flexslider').flexslider({
		animation: "fade",
		touch:true,
		animationDuration: 6000,
		directionNav: true,
		controlNav: false
	});
}

function runcamera(){
	if(jQuery('#slideritems').length){
		jQuery('#slideritems').camera({
			height: '44%',
			pagination: false,
			navigation:true,
			playPause: false,
			thumbnails: false,
			imagePath: '../images/'
		});
	}
}