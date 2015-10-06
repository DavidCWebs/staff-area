<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php
  the_content();
  include_once( plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-staff-area-loops.php' );
  $all_resources = new Carawebs\Staff\Loop( ['orderby' => 'date', 'order' => 'DESC'] );
  $all_resources->staff_resource_loop();

  $terms = get_terms( 'resource_category' );

  caradump($terms);

    foreach( $terms as $term )  {

      $name = $term->name;

      $tax_query = ['tax_query' => array(
    		array(
    			'taxonomy' => 'resource_category',
    			'field'    => 'slug',
    			'terms'    => $term->slug,
    		),
    	)];

      echo "<h3>Resources in the $name Resource Category</h3>";

      $resources = new Carawebs\Staff\Loop( $tax_query );
      $resources->staff_resource_loop();

    }


?>
<?php endwhile; ?>
