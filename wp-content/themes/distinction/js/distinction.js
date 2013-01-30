jQuery(document).ready(function(){
		jQuery(window).load(function () {
			jQuery('#post-wrapper').masonry({
				singleMode: false,
				itemSelector: '.hentry',
				columnWidth: 450
			});
		});
	});