<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/access-check.php' );

  if ( 'no_access' == $access ) {

    return;

  }
  ?>

  <?php the_content(); ?>
  <?php
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-staff-area-loops.php' );
  echo "<h2>All Staff Resources</h2>";
  $all_resources = new Carawebs\Staff\Loop( ['orderby' => 'date', 'order' => 'DESC'] );
  $all_resources->staff_resource_loop();

  $terms = get_terms( 'resource_category' );

    foreach( $terms as $term )  {

      $name = $term->name;

      $tax_query = ['tax_query' => array(
    		array(
    			'taxonomy' => 'resource_category',
    			'field'    => 'slug',
    			'terms'    => $term->slug,
    		),
    	)];

      echo "<h2>Staff Resources: $name</h2>";

      $resources = new Carawebs\Staff\Loop( $tax_query );
      $resources->staff_resource_loop();

    }

?>
<?php endwhile; ?>
