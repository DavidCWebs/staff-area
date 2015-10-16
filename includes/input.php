<?php

namespace Staff_Area\Includes;

class Input {

  public $form_type;

  public function __construct( $type ) {

    $this->set_form_type( $type );

  }

  public function set_form_type( $type ) {

    $this->form_type = $type;

  }

}
