<?php

namespace Staff_Area\Includes;

/**
 * Abstract class contains core methods for retrun of Data stored by means of ACF
 *
 * @since      1.0.0
 * @package    StudentStudio
 * @subpackage Fetch
 * @author     David Egan <david@carawebs.com>
 * @link:      http://www.advancedcustomfields.com/
 */
abstract class Data_Filter {

  /**
   * Filter/Sanitize data according by type
   *
   * @since 1.0.0
   * @uses esc_html()
   * @uses wp_kses_post()
   * @param  string $content Data to be filtered
   * @param  string $type    Type of data - denotes the filter to use
   * @return string          Filtered data
   */
  static public function filter( $content, $type ){

    $output = '';

    switch( $type ){

      case "OEmbed":

        $output = $content;

        break;

      case "esc_html":

        $output = esc_html( $content );

        break;

      case "the_content":

        $output = apply_filters( 'the_content', $content );

        break;

      case "text":

        $output = esc_html( $content );

        break;

    }

    return wp_kses_post( $output );

  }

  /**
   * Filter and return an image.
   *
   * This static method will return an array of necessary attributes to enable
   * construction of HTML for an image.
   *
   * @since 1.0.0
   * @uses wp_prepare_attachment_for_js()
   * @param  string|integer $image_ID Post ID of the image to be returned
   * @param  array  $meta             An array containing image size
   * @return array  Necessary data to build an image (ID, src, title, height, width, alt)
   */
  static public function image_filter( $image_ID, array $meta ) {

    $image_object = wp_prepare_attachment_for_js( $image_ID );
    $image_size = $meta[1];

    $output = [
      'ID'      => $image_ID,
      'url'     => $image_object['sizes'][$image_size]['url'],
      'title'   => $image_object['title'],
      'height'  => $image_object['sizes'][$image_size]['height'],
      'width'   => $image_object['sizes'][$image_size]['width'],
      'alt'     => $image_object['alt'],
    ];

    return $output;

  }

  /**
   * Return image markup
   *
   * carawebs_class_autoloader('Data');
   * StudentStudio\Fetch\Data::image( 2301, 'thumbnail' );
   *
   * @param  [type] $image_ID   [description]
   * @param  string $image_size [description]
   * @return [type]             [description]
   */
  static public function image( $image_ID, $image_size = 'full' ) {

    $image_object = wp_prepare_attachment_for_js( $image_ID );
    $src          = $image_object['sizes'][$image_size]['url'];
    $title        = $image_object['title'];
    $height       = $image_object['sizes'][$image_size]['height'];
    $width        = $image_object['sizes'][$image_size]['width'];
    $alt          = $image_object['alt'];

    $image ="<img src='$src' width='$width' height='$height' title='$title' class='img-responsive'/>";

    echo $image;

  }

}
