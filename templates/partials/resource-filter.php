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
<form role="form" id="form-<?php echo $this->data_ID; ?>" class="resource-search">
  <div class="form-group staff-area-grid">
      <div class="custom-input-group grid-unit">
        <input placeholder="Search Resources" data-id="<?php echo $this->data_ID; ?>" id="<?php echo $search_ID ;?>" type="text" class="input-lg form-control cw-staff-area-search" />
      </div>
      <div class="grid-unit">
        <a href="#" data-id="<?php echo $this->data_ID; ?>" id="<?php echo $this->data_ID; ?>-showall" class="showall btn btn-block btn-primary btn-lg grey">All Resources</a>
      </div>
    <!--</div>
    <div class="row topspace">-->
      <div class="staff-area-grid-unit">
        <?php

        if ( ! empty( $terms_array ) ) {

          $sectors = '';

          foreach ( $terms_array as $term ) {

            $sectors .= '<li class="' . $term->slug . '"><a href="#" data-id="' . $this->data_ID . '">' . $term->name . '</a></li>';

          }

          echo <<<EOF
          <div id="$filter_ID" class="form-group dropdown">
            <a href="#" class="btn btn-primary btn-lg btn-block grey dropdown-toggle" type="button" data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-right"></i>&nbsp;Filter by Topic<!-- <span class="caret"></span>--></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="select-product">
              $sectors
              <li class="resource"><a data-id="$this->data_ID" href="#">Show All Resources</a></li>
            </ul>
          </div>
EOF;

        }

        ?>
      </div>
      <div class="grid-unit">
        <a href="#" data-id="<?php echo $this->data_ID; ?>" id="<?php echo $this->data_ID; ?>-not-completed" class="not-completed btn btn-block btn-primary btn-lg grey">
          <?php
            _e( "Resources I Haven't Completed", 'staff-area ');
          ?>
        </a>
      </div>
      <div class="gridbreak"></div>
    </div>
</form>
<div id="<?php echo 'management-resource-category' == $filter_tax ? 'management-' : ''; ?>filter-feedback" class="topspace alert alert-info">
  This table shows <?php echo $this->section_title; ?><span id="filter-message"></span>
</div>
<hr>
<?php
