/**
* Register new users on the front end by AJAX.
*
*
*/
( function( $ ){

  "use strict";

  $( function() {

    $( "#submit-confirmation" ).click( function( event ) {

      // Prevent default action
      // -----------------------------------------------------------------------
      event.preventDefault();

      var $form = $( "#mark-as-read" );

      // check if the input is valid - if not, no submission
      //if ( !$form.valid() ) { return false; }

      // Show "Please wait" loader to user
      $( ".indicator" ).animate( { opacity: 1 }, 200 );
      $( "#result-message" ).hide();

      // Collect data from inputs, and slot into variables
      var regNonce  = $( "#staff_area_cw_read_confirm" ).val();
      var regMarked = $( "#confirmation-checkbox:checked" ).val();
      var regSubmit = $( "#submit-confirmation").val();
      var regUserID = $( "#cwUserID").val();
      var regPostID = $( "#cwPostID").val();
      var regAjax = true;

      // AJAX URL: where to send data (set in the localize_script function)
      var ajaxURL = carawebsRegVars.carawebsAjaxURL;

      // Data to send
      var data = {
        action:       "mark_as_read",
        cwMarkRead:   regMarked,
        cwSubmitted:  regSubmit,
        cwMarkNonce:  regNonce,
        cwPostID:     regPostID,
        cwUserID:     regUserID,
        cwAjax: true
      };

      console.log(data);

      // Do AJAX request
      // -----------------------------------------------------------------------
      $.post( ajaxURL, data, function( response ) {

        if ( response ) {

          console.log( response );

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

            $( "#mark-as-read" ).hide();

            // Clear old user list/error messages
            $( "#result-message" ).empty();

            // Build a success message
            var staffReturn =
            "<p>" + response.message + "</p>";

            $( "#result-message" ).html( staffReturn );

            // Show results div
            $( "#result-message" ).fadeIn( 400 );

          }

        }

      } );

    } );

  } );

} )( jQuery );
