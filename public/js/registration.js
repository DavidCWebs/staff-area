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
      var regUserRole = $( "#cw_user_role" ).val();
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
