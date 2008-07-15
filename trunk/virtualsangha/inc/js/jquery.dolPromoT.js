// jQuery plugin - Dolphin Promo Images
(function($){
	$.fn.dolPromo = function( iInterval, fRatio ) {
		function resizeMyImage($Img) {
			if( $Img.width() > $promo.width() ) {
				var fImgRatio =  $Img.height() / $Img.width();
				$Img.width( $promo.width() ).height( Math.round( $promo.width() * fImgRatio ) );
			}
			
			if( $Img.height() > $promo.height() ) {
				var fImgRatio = $Img.width() / $Img.height();
				$Img.width( Math.round( $promo.height() * fImgRatio ) ).height( $promo.height() );
			}
			
			if( $Img.width() < $promo.width() ) {
				var left = Math.round( ( $promo.width() - $Img.width() ) / 2 );
				$Img.css( 'left', left );
			}
			
			/*if( $Img.height() < $promo.height() ) {
				var top = Math.round( ( $promo.height() - $Img.height() ) / 2 );
				$Img.css( 'top', top );
			}*/
		}
		
		function runFlashing() {
			function switchThem() {
				if( typeof ePrev != 'undefined' )
					ePrev.fadeOut( 1000 );
				
				eNext.fadeIn( 1000 );
				
				ePrev = eNext;
				eNext = eNext.next( 'img' );
				
				if( !eNext.length )
					eNext = $( 'img:first', $promo );
				
				setTimeout( switchThem, iInterval );
			}
			
			var eNext = $( 'img:first', $promo );
			var ePrev;
			
			switchThem();
		}
		
		function resetPromoSize() {
			if( !$promo.width() )
				//get size from parent
				$promo.width( $promo.parent().width() - parseInt( $promo.css( 'margin-left' ) ) - parseInt( $promo.css( 'margin-right' ) ) );
			else
				$promo.css( 'width', 'auto' );
			
			$promo.height( Math.round( $promo.width() * fRatio ) );
		}
		
		//default parameters
		var iInterval = iInterval || 3000; //switching interval in milliseconds
		var fRatio = fRatio || 0.28125; //main div size proportion (height/width)
		
		var $promo = this;
		
		resetPromoSize();
		
		$( '> img', $promo ) //get all images
		.css( 'display', 'none' ) //hide all images
		.each( function() { //for each image
			var $Img = $(this); //get current image
			$Img.ready( function() { //when the image is loaded
				resizeMyImage( $Img );
			} );
		} )
		.ready( function() { //when all images loaded
			//fire up behavior
			$promo.css( 'visibility', 'visible' );
			runFlashing();
		} );
		
		var iOldWidth = $promo.width()
		//attach event on window resize
		$(window).resize( function() {
			resetPromoSize();
			var iNewWidth = $promo.width();
			
			if( iOldWidth != iNewWidth ) { //if the main width is changed
				iOldWidth = $promo.width();
				
				$( '> img', $promo ).each( function() {
					var $Img = $(this);
					$Img.css( { width: 'auto', height: 'auto', left: 0, top: 0 } );
					resizeMyImage( $Img );
				} );
			}
		} );
	};
})(jQuery);
