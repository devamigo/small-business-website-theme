(function ($) {
	
	$(document).ready( function() {
		// fitvids
		$( '.entry-content' ).fitVids(  
			{ customSelector: "iframe[src^='www.slideshare.net']" }
		);
		
		// match height
		$( '.row div[class^="col"]' ).matchHeight();
		
		// primary navigation toggle
		$( '.primary-nav .toggle' ).click( function( e ) {
			$( '.primary-nav .menu' ).slideToggle();
			e.preventDefault();
		} );
		
		// window resize
		$( window ).resize( function() {
			if( $( window ).width() >= 960 ) {
				$( '.primary-nav .toggle' ).hide();
				$( '.primary-nav .menu' ).show();
			}
			else {
				$( '.primary-nav .toggle' ).show();
				$( '.primary-nav .menu' ).hide();
			}
		} );
	} );
	
}(jQuery));