(function($) {
	 $(document).ready(function(){
		 $( '#result_ajaxp' ).on( 'click',' ul.pagination a', function( e ) {
			 e.preventDefault();
			 var data_page = $(this).attr( 'data-page' );
			 var posts_per_page = $('.ajax_pagination').attr( 'posts_per_page' );
			 var post_type = $('.ajax_pagination').attr( 'post_type' );
			 var post_not_in = $('.recipes-video-relation').data('post-not-in');
			 $.ajax({
				 cache: false,
				 timeout: 8000,
				 url: svl_array_ajaxp.admin_ajax,
				 type: "POST",
				 data: ({ 
					 action			:	'LoadPostPagination', 
					 data_page		:	data_page,
					 posts_per_page	:	posts_per_page,
					 post_type		:	post_type,
					 post_not_in	:   post_not_in
				 }),
				 beforeSend: function() {
				 $( '.loading_ajaxp' ).css( 'display','block' );
				 },
				 success: function( data, textStatus, jqXHR ){					
				 	$( '#result_ajaxp' ).html( data );
				 },
				 error: function( jqXHR, textStatus, errorThrown ){
					 console.log( 'The following error occured: ' + textStatus, errorThrown );
				 },
				 complete: function( jqXHR, textStatus ){
				 }
			 });
		 });
	 });
})(jQuery);