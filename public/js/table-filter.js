/**
 * jQuery Filters
 *
 */
( function( $ ) {
	"use strict";

	$( document ).ready( function() {

		/**
		 * Fix table heights
		 *
		 * Get starting table height for each table and fix table heights to prevent
		 * distracting page jumps when the filter is triggered.
		 */
		$( "table" ).each( function() {

			var tableHeight = $( this ).height();
			$( this ).parent().css( "height", tableHeight );

		} );

		/**
		 * Filter by resource, triggered by clicking a drop down menu
		 *
		 * The dropdown menu is populated with the custom taxonomy for the relevant resource type.
		 */

		$( "form .dropdown-menu > li > a" ).click( function( event ) {

			event.preventDefault();

			// Get the value of the containing elements class
			var valThis = $( this ).parent().attr( "class" );
			var catName = $( this ).text();

			// The target table
			var targetTable			= $( this ).attr( 'data-id' );

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
		$( ".showall" ).click( function( event ) {

			event.preventDefault();

			// The current form
			var currentForm		= $( this ).closest( "form" );
			var formID				= $( currentForm ).attr( "id" );

			// The target table
			var targetTable		= $( this ).attr( "data-id" );
			// Set a variable to target the table row elements
			var targetRow			= "#" + targetTable + " tbody tr";

			// Clear the search field for the current table
			$( "#" + formID + " .cw-staff-area-search" ).val( "" );

			// Show hidden rows
			if ( $( targetRow ).is( ":hidden" ) ) {

				$( targetRow ).show( 100 );

			}

			$( "#select-resource-category option:selected" ).prop( "selected", false );
			$( "#select-resource-category option:first" ).prop( "selected", "selected" );
			$( '#filter-message' ).html( "" );

		} );

		// Show NOT COMPLETED Resources
		$( ".not-completed" ).click( function( event ) {

			event.preventDefault();

			// The current form
			var currentForm		= $( this ).closest( "form" );

			// The form ID
			var formID				= $( currentForm ).attr( "id" );

			// The target table
			var targetTable		= $( this ).attr( "data-id" );

			// Set a variable to target the table row elements
			var targetRow			= "#" + targetTable + " tbody tr";

			// Search for this string in the row class
			var notComplete = "not-read";

			// Clear the search field for the current table
			$( "#" + formID + " .cw-staff-area-search" ).val( "" );

			$( targetRow ).each( function() {

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

	} );

} )( jQuery );
