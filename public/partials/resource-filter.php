<?php
/**
 * A partial with a form that triggers a jQuery filter on Staff Resources.
 *
 * Filtered items must be contained within a div id="resources".
 * Each div must have the resource category slugs as classes.
 * Each div must have the resource title slugified as the first class.
 *
 * {$this->table_ID_base}-{$this->div_class}
 *
 */

?>
<form role="form" class="form-inline resource-search">
  <div class="form-group">
    <div class="custom-input-group" style="position: relative;display: inline-block;">
      <input placeholder="Search Resources" data-src="<?php echo $this->data_src; ?>" id="<?php echo $search_ID ;?>" type="text" class="input-lg form-control" />
    </div>
    <a href="#" data-src="<?php echo $this->data_src; ?>" id="<?php echo 'management-resource-category' == $filter_tax ? 'management-' : ''; ?>showall" class="btn btn-primary btn-lg grey">All Resources</a>
    <?php

    if ( ! empty( $terms_array ) ) {

      $sectors = '';

      foreach ( $terms_array as $term ) {

        $sectors .= '<li class="' . $term->slug . '"><a href="#" data-src=' . $this->data_src . '">' . $term->name . '</a></li>';

      }

      echo <<<EOF
      <div id="$filter_ID" class="form-group dropdown">
        <a href="#" class="btn btn-primary btn-lg grey dropdown-toggle" type="button" data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-right"></i>&nbsp;Filter by Topic<!-- <span class="caret"></span>--></a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="select-product">
          $sectors
          <li class="resource"><a href="#">Show All Resources</a></li>
        </ul>
      </div>
EOF;

    }

    ?>
    <a href="#" id="<?php echo 'management-resource-category' == $filter_tax ? 'management-' : ''; ?>not-completed" class="btn btn-primary btn-lg grey">
      <?php
        _e( "Resources I Haven't Completed", 'staff-area ');
      ?>
    </a>
  </div>
</form>
<div id="<?php echo 'management-resource-category' == $filter_tax ? 'management-' : ''; ?>filter-feedback" class="topspace alert alert-info">
  This table shows <?php echo $this->section_title; ?><span id="filter-message"></span>
</div>
<hr>
<?php
