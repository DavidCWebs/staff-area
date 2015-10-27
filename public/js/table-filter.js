/**
 * jQuery Filters
 *
 */
( function( $ ) {
	"use strict";

	$( document ).ready( function() {

		// Get starting table height and fix table height to prevent distracting page jumps
		var resourceHeight = $( "#compulsory-staff-resource-table" ).height();
		var managementResourceHeight = $( "#management-resources-table" ).height();
		$( "#resources-table-container" ).css( "height", resourceHeight );
		$( "#management-resources-table-container" ).css( "height", managementResourceHeight );

		/**
		 * Filter by resource, triggered by clicking a drop down menu
		 *
		 * The dropdown menu is populated with the custom taxonomy for the relevant resource type.
		 *
		 */

		function fixTableHeight( tableContainer, targetHeight ) {

			// Set height on the table's containing element
			$( tableContainer ).css( "height", targetHeight );

		}

		var flag = false;
		$( ".dropdown-menu > li > a" ).click( function( event ) {

			event.preventDefault();

			// Get the value of the containing elements class
			var valThis = $( this ).parent().attr( "class" );
			var catName = $( this ).text();

			// The target table
			var targetTable			= $( this ).attr( 'data-id' );
			// Get height of the target table
			var targetHeight		= $( "#" + targetTable ).height();
			// The containing element
			var tableContainer	= $( "#" + targetTable + "-container" )

			if ( false == flag ) {

				fixTableHeight( tableContainer, targetHeight );

			}

			flag = true;

			// Set height on the table's containing element
			//$( "#" + targetTable + "-container" ).css( "height", targetHeight );
			// Set a variable to target the table row elements
			var targetRow			= "#" + targetTable + " tbody tr";

			$( targetRow ).each( function() {

				var productContent = $( this ).attr( "class" );

				// If search string isn't found, returns -1
				if ( productContent.indexOf( valThis ) == -1 ) {

					$( this ).hide( 200 );

				} else {

					$( this ).show( 100 );

				}

			} );

			$( '#filter-message' ).html( " in the category: " + catName  );

		} );

		// Resource Type filter
		$( ".cw-staff-area-search" ).keyup( function() {

			var targetTable	= $( this ).attr( 'data-id' );
			var targetRow		= "#" + targetTable + " tbody tr";
			var valThis = $( this ).val().toLowerCase();

			$( targetRow ).each( function() {

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
			$( '#filter-message' ).html( "" );

		} );

		// Show NOT COMPLETED Resources
		$( "#not-completed" ).click( function( event ) {

			event.preventDefault();

			// Search for this string in the row class
			var notComplete = "not-read";

			// Clear the search input
			$( "#resource-search" ).val( "" );

			$( "#resources-table tbody tr" ).each( function() {

				var readStatus = $( this ).attr( "class" );

				// If search string isn't found, returns -1
				if ( readStatus.indexOf( notComplete ) == -1 ) {

					$( this ).hide( 200 );

				} else {

					$( this ).show( 100 );

				}

			} );

			$( '#filter-message' ).html( ": Not Completed" );

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
