<?php
namespace Staff_Area\Includes;

class Email {

  /**
  * User ID of the email recipient
  * @var string|int
  */
  private $recipient_ID;

  /**
  * Recipient's email address
  * @var string
  */
  private $user_email;

  /**
  * Recipient's first name
  * @var string
  */
  private $user_firstname;

  /**
  * Recipient's last name
  * @var string
  */
  private $user_lastname;

  /**
  * Site admin email address
  * @var string
  */
  private $admin_email;

  /**
  * Set up properties
  *
  * @param [type] $user_email     [description]
  * @param [type] $recipient_ID   [description]
  * @param [type] $user_firstname [description]
  * @param [type] $user_lastname  [description]
  */
  public function __construct( $user_email, $recipient_ID, $user_firstname, $user_lastname, $user_role = 'staff_member' ) {

    $this->recipient_ID = $recipient_ID;
    $this->admin_email  = get_option( 'admin_email' );
    $this->user_email   = $user_email;
    $this->firstname    = $user_firstname;
    $this->lastname     = $user_lastname;
    $this->user_role    = $user_role;

  }

  /**
  * Send email to the new user with instructions & password
  *
  * Email content is generated from the content of a specified 'email' CPT -
  * this will allow site admins to control the message.
  *
  * @param  string $password Auto generated password.
  * @return [type]           [description]
  *
  */
  public function email_new_user( $password ){

    // Email the user
    if ( 'staff_member' === $this->user_role ){

      $email_slug = 'staff-member-introduction';
      $login_url = esc_url( home_url('/student') );

    } elseif ( 'supervisor' === $this->user_role ){

      $email_slug = 'supervisor-introduction';
      $login_url = esc_url( home_url('/supervisor') );

    }

    // Allow html in email
    add_filter( 'wp_mail_content_type', function($content_type){

      return 'text/html';

    });

    $headers = 'From: ' . $this->admin_email . "\r\n";
    $welcome = 'Welcome, ' . $this->firstname . '!';

    // Target email CPT by slug.
    $args = array(
      'name' => $email_slug,
      'post_type' => 'email',
      'post_status' => 'publish',
      'showposts' => 1,
    );

    $my_posts = get_posts( $args );

    if( $my_posts ) {

      $post_id = $my_posts[0]->ID;

    }

    $post_object = get_post( $post_id );
    $mailmessage = apply_filters( 'the_content', $post_object->post_content ); // Build a mail message from the content of a post.
    $mailmessage .= "<hr><p>Your username is: $this->email (yes, your email address!)</p><p>Your password is: $password</p>";
    $mailmessage .= "<p><a href='$login_url'>Login to Student Studio.</a></p>";

    wp_mail( $this->email, $welcome, $mailmessage, $headers );

  }

}
