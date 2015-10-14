/**
* Register new users on the front end by AJAX.
*
*
*/
( function( $ ){

  "use strict";

  $( function() {

    $( "#btn-new-user" ).click( function( event ) {

      //alert('clicked');

      // Prevent default action
      // -----------------------------------------------------------------------
      event.preventDefault();

      var $form = $( "#user-reg-form" );

      // check if the input is valid - if not, no submission
      //if ( !$form.valid() ) { return false; }

      // Show "Please wait" loader to user
      $( ".indicator" ).animate( { opacity: 1 }, 200 );
      $( "#result-message" ).hide();

      // Collect data from inputs, and slot into variables
      var regNonce = $( "#cw_new_user_nonce" ).val();
      var regUserRole = $( "#cw_user_role:checked" ).val();
      var regEmail  = $( "#cw_email" ).val();
      var regFirstname  = $( "#cw_firstname" ).val();
      var regLastname  = $( "#cw_lastname" ).val();
      var regAjax = true;

      // AJAX URL: where to send data (set in the localize_script function)
      var ajaxURL = carawebsRegVars.carawebsAjaxURL;
      var coordinatorID = carawebsRegVars.carawebsCoordinatorID;

      // Data to send
      var data = {
        action: "register_new_user",
        cwCoordID: coordinatorID,
        cwUserRole: regUserRole,
        cwNewUserNonce: regNonce,
        cwEmail: regEmail,
        cwFirstname: regFirstname,
        cwLastname: regLastname,
        cwAjax: true
      };

      console.log(data);

      // Do AJAX request
      // -----------------------------------------------------------------------
      $.post( ajaxURL, data, function( response ) {

        if ( response ) {

          // Hide "Please wait" indicator
          $( ".indicator" ).animate( { opacity: 0 }, 500 );

          var status = response.status;

          if ( "error" === response.status ) {

            // Clear old user list/error messages
            $( "#result-message" ).empty();

            // Clear old user list/error messages
            $( "#result-message-show" ).remove();

            // Message built in PHP and received as a string
            var output = response.message;
            $( "#result-message" ).html( output );
            $( "#result-message" ).fadeIn( 400 );

          } else {

            // Clear the form, so that another user can be easily registered
            $( "#user-reg-form" )[0].reset();

            // Clear old user list/error messages
            $( "#result-message" ).empty();

            // Build a success message
            var studentReturn =
            "<p>" + response.message + "</p>";

            $( "#result-message" ).html( studentReturn );

            // Show results div
            $( "#result-message" ).fadeIn( 400 );

          }

        }

      } );

    } );

  } );

} )( jQuery );
;/**
 * jQuery Filters
 *
 */
( function( $ ) {
	"use strict";

	$( document ).ready( function() {

		// Filter by Resource
		$( "#select-resource-category .dropdown-menu > li > a" ).click( function( event ) {

			event.preventDefault();

			var valThis = $( this ).parent().attr( "class" );

			$( "#resources > div" ).each( function() {

				var productContent = $( this ).attr( "class" );

				// If search string isn't found, returns -1
				if ( productContent.indexOf( valThis ) == -1 ) {

					$( this ).hide( 400 );

				} else {

					$( this ).show( 400 );

				}

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
