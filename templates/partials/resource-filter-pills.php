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
<form role="form" id="form-<?php echo $this->data_ID; ?>" class="resource-search form">
  <div class="form-group">
    <ul class="staff-area-grid">
      <li class="staff-area-grid-unit custom-input-group">
        <input placeholder="Search Resources" data-id="<?php echo $this->data_ID; ?>" id="<?php echo $search_ID ;?>" type="text" class="input-lg form-control cw-staff-area-search" />
      </li>
      <li class="staff-area-grid-unit">
        <a href="#" data-id="<?php echo $this->data_ID; ?>" id="<?php echo $this->data_ID; ?>-showall" class="showall btn btn-block btn-primary btn-lg">All Resources</a>
      </li>
      <li class="staff-area-grid-unit">
        <a href="#" data-id="<?php echo $this->data_ID; ?>" id="<?php echo $this->data_ID; ?>-not-completed" class="not-completed btn btn-block btn-primary btn-lg grey">
          <?php
            _e( "Resources I Haven't Completed", 'staff-area ');
          ?>
        </a>
      </li>
      <li id="<?= $filter_ID; ?>" class="form-group dropdown staff-area-grid-unit">
        <?php

        if ( ! empty( $terms_array ) ) {

          $sectors = '';

          foreach ( $terms_array as $term ) {

            $sectors .= '<li class="' . $term->slug . '"><a href="#" data-id="' . $this->data_ID . '">' . $term->name . '</a></li>';

          }

          echo <<<EOF
          <!--<div id="$filter_ID" class="form-group dropdown">-->
            <button class="btn btn-primary btn-lg btn-block grey dropdown-toggle" type="button" data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-right"></i>&nbsp;&nbsp;Filter by Category</button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="select-product">
              $sectors
              <li class="resource"><a data-id="$this->data_ID" href="#">Show All Resources</a></li>
            </ul>
          <!--</div>-->
EOF;

        }

        ?>
      </li>
    </ul>

    </div>
</form>
<div id="<?= $this->data_ID; ?>-filter-feedback" class="topspace alert alert-info">
  This table shows <?php echo $this->section_title; ?><span class="filter-message"></span>
</div>
<hr>
<?php
