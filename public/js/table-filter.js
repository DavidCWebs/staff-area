/**
 * jQuery Filters
 *
 */
( function( $ ) {
	"use strict";

	$( document ).ready( function() {

		// Get starting table height and fix table height to prevent distracting page jumps
		var resourceHeight = $( "#resources-table" ).height();
		var managementResourceHeight = $( "#management-resources-table" ).height();
		$( "#resources-table" ).css( "height", resourceHeight );
		$( "#management-resources-table" ).css( "height", managementResourceHeight );

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

			$( "#resources-table tbody tr" ).each( function() {

				var productContent = $( this ).attr( "class" );

				// If search string isn't found, returns -1
				if ( productContent.indexOf( valThis ) == -1 ) {

					$( this ).hide( 200 );

				} else {

					$( this ).show( 100 );

				}

			} );

		} );

		// Resource Type filter
		$( "#resource-search" ).keyup( function() {

			var valThis = $( this ).val().toLowerCase();

			$( "#resources-table tbody tr" ).each( function() {

				var productContent = $( this ).attr( "class" );

				if ( productContent.indexOf( valThis ) === 0 ) {

					$( this ) .show( 100 );

				} else {

					$( this ).hide( 200 );

				}

			} );

		} );

		// Show All Resources
		$( "#showall" ).click( function( event ) {

			event.preventDefault();

			$( "#resource-search" ).val( "" );

			if ( $( "#resources-table tbody tr" ).is( ":hidden" ) ) {

				$( "#resources-table tbody tr" ).show( 100 );

			}

			$( "#select-resource-category option:selected" ).prop( "selected", false );
			$( "#select-resource-category option:first" ).prop( "selected", "selected" );

		} );

		// Management Resource Type filter
		$( "#management-resource-search" ).keyup( function() {

			var valThis = $( this ).val().toLowerCase();

			$( "#management-resources-table tbody tr" ).each( function() {

				var productContent = $( this ).attr( "class" );

				if ( productContent.indexOf( valThis ) === 0 ) {

					$( this ) .show( 100 );

				} else {

					$( this ).hide( 200 );

				}

			} );

		} );

		// Show All Management Resources
		$( "#management-showall" ).click( function( event ) {

			event.preventDefault();

			$( "#management-resource-search" ).val( "" );

			if ( $( "#management-resources-table tbody tr" ).is( ":hidden" ) ) {

				$( "#management-resources-table tbody tr" ).show( 100 );

			}

			$( "#select-management-resource-category option:selected" ).prop( "selected", false );
			$( "#select-management-resource-category option:first" ).prop( "selected", "selected" );

		} );

		// Filter by Management Resource
		$( "#select-management-resource-category .dropdown-menu > li > a" ).click(
			function( event ) {
				event.preventDefault();
				var valThis = $( this ).parent().attr( "class" );
				$( "#management-resources-table tbody tr" ).each( function() {
					var productContent = $( this ).attr( "class" );

					// If search string isn't found, returns -1
					if ( productContent.indexOf( valThis ) == -1 ) {
						$( this ).hide( 200 );
					} else {
						$( this ).show( 100 );
					}
				} );
			} );

	} );

} )( jQuery );
