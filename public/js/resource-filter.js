/**
 * jQuery Filters
 *
 */
( function( $ ) {
	"use strict";

	$( document ).ready( function() {

		/**
		 * Filter by resource, triggered by clicking a drop down menu
		 *
		 * The dropdown menu is populated with the custom taxonomy for the relevant resource type.
		 *
		 */
		$( "#select-resource-category .dropdown-menu > li > a" ).click( function( event ) {

			event.preventDefault();

			// Get the value of the containing elements class
			var valThis = $( this ).parent().attr( "class" );

			//alert( valThis );

			$( "#resources > div" ).each( function() {

				var productContent = $( this ).attr( "class" );

				//alert( productContent );

				// If search string isn't found, returns -1
				if ( productContent.indexOf( valThis ) == -1 ) {

					$( this ).hide( 200 );

				} else {

					$( this ).show( 200 );

				}

				//alert( "Index: " + productContent.indexOf( valThis ));

			} );

		} );

		// Resource Type filter
		$( "#resource-search" ).keyup( function() {

			var valThis = $( this ).val().toLowerCase();

			$( "#resources > div" ).each( function() {

				var productContent = $( this ).attr( "class" );

				if ( productContent.indexOf( valThis ) === 0 ) {

					$( this ) .show( 400 );

				} else {

					$( this ).hide( 400 );

				}

			} );

		} );

		// Show All Resources
		$( "#showall" ).click( function( event ) {

			event.preventDefault();

			$( "#resource-search" ).val( "" );

			if ( $( "#resources > div" ).is( ":hidden" ) ) {

				$( "#resources > div" ).show( 400 );

			}

			$( "#select-resource-category option:selected" ).prop( "selected", false );
			$( "#select-resource-category option:first" ).prop( "selected", "selected" );

		} );

		// Management Resource Type filter
		$( "#management-resource-search" ).keyup( function() {

			var valThis = $( this ).val().toLowerCase();

			$( "#management-resources > div" ).each( function() {

				var productContent = $( this ).attr( "class" );

				if ( productContent.indexOf( valThis ) === 0 ) {

					$( this ) .show( 400 );

				} else {

					$( this ).hide( 400 );

				}

			} );

		} );

		// Show All Management Resources
		$( "#management-showall" ).click( function( event ) {

			event.preventDefault();

			$( "#management-resource-search" ).val( "" );

			if ( $( "#management-resources > div" ).is( ":hidden" ) ) {

				$( "#management-resources > div" ).show( 400 );

			}

			$( "#select-management-resource-category option:selected" ).prop( "selected", false );
			$( "#select-management-resource-category option:first" ).prop( "selected", "selected" );

		} );

		// Filter by Management Resource
		$( "#select-management-resource-category .dropdown-menu > li > a" ).click(
			function( event ) {
				event.preventDefault();
				var valThis = $( this ).parent().attr( "class" );
				$( "#management-resources > div" ).each( function() {
					var productContent = $( this ).attr( "class" );

					// If search string isn't found, returns -1
					if ( productContent.indexOf( valThis ) == -1 ) {
						$( this ).hide( 400 );
					} else {
						$( this ).show( 400 );
					}
				} );
			} );

	} );

} )( jQuery );
