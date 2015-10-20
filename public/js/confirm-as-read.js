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
      $( "#cw-result-message" ).hide();

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
            $( "#cw-result-message" ).empty();

            // Clear old user list/error messages
            $( "#cw-result-message-show" ).remove();

            // Message built in PHP and received as a string
            var output = response.message;
            $( "#cw-result-message" ).html( output );
            $( "#cw-result-message" ).fadeIn( 400 );

          } else {

            // Build a success message
            var staffReturn =
            "<p>" + response.message + "</p>";

            // Clear old user list/error messages
            $( "#cw-result-message" ).empty();

            //$( "#mark-as-read" ).hide();
            $( "#mark-as-read" ).fadeOut( 200, function() {
              $( "#cw-result-message" ).html( staffReturn );
              // Show results div
              $( "#cw-result-message" ).fadeIn( 200 );
            });


            //$( "#cw-result-message" ).html( staffReturn );
            // Show results div
            //$( "#cw-result-message" ).fadeIn( 400 );

          }

        }

      } );

    } );

  } );

} )( jQuery );
