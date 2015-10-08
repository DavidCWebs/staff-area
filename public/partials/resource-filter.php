<?php
/**
 * A partial with a form that triggers a jQuery filter on Staff Resources.
 *
 * Filtered items must be contained within a div id="resources".
 * Each div must have the resource category slugs as classes.
 * Each div must have the resource title slugified as the first class.
 *
 */
?>
<form role="form" class="form-inline resource-search">
  <div class="form-group">
    <div class="custom-input-group" style="position: relative;display: inline-block;">
      <input placeholder="Search Resources" id="<?php echo 'management_resource_category' == $filter_tax ? 'management-' : null;?>resource-search" type="text" class="input-lg form-control" />
    </div>
    <a href="#" id="<?php echo 'management_resource_category' == $filter_tax ? 'management-' : null;?>showall" class="btn btn-primary btn-lg grey">All Resources</a>
    <?php

    $terms = !empty( $filter_tax ) ? get_terms( $filter_tax ) : null;

    if ( ! empty( $terms ) ) {

      $sectors = '';

      foreach ( $terms as $term ) {

        $sectors .= '<li class="' . $term->slug . '"><a href="#">' . $term->name . '</a></li>';

      }

      ?>
      <div id="select-<?php echo 'management_resource_category' == $filter_tax ? 'management-' : null;?>resource-category" class="form-group dropdown">
      <a href="#" class="btn btn-primary btn-lg grey dropdown-toggle" type="button" data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-right"></i>&nbsp;Filter by Topic<!-- <span class="caret"></span>--></a>
      <ul class="dropdown-menu" role="menu" aria-labelledby="select-product">
      <?php echo $sectors; ?>
      <li class="<?php echo 'management_resource_category' == $filter_tax ? 'management-' : null;?>resource"><a href="#">Show All Resources</a></li>
      </ul>
      </div>
      <?php

    }

    ?>
  </div>
</form>
<?php
