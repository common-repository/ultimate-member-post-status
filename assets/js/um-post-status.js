/* Display post on your wall */
jQuery(document).on('click', '.um-post-wall-btn',function(e){
	e.preventDefault();
	
	jQuery(this).addClass('active');
	
	prepare_Modal();
	
	jQuery.ajax({
		url: um_scripts.ajaxurl,
		type: 'post',
		data: { action: 'um_post_wall_modal' },
		success: function(data){
			if ( data ) {
				show_Modal( data );
				responsive_Modal();
				autosize( jQuery('.um-message-textarea textarea:visible') );
			} else {
				remove_Modal();
			}
		}
	});
	
	return false;
});