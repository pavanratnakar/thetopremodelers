var herve_home = {
	review : function(){
		$("#review-container .carousel").rcarousel({
			visible: 1,
			step: 1,
			speed: 700,
			auto: {enabled: false},
			width: 840,
			height: 240
		});
		$("#ui-carousel-next")
			.add( "#ui-carousel-prev")
			.add( ".bullet" )
			.hover(
				function() {
					$(this).css("opacity", 0.7 );
				},
				function() {
					$(this).css("opacity", 1.0 );
				}
			);
	}
}
$(document).ready(function(){
	herve_home.review();
});