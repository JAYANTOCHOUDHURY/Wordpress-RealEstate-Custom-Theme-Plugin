<?php
$paged = get_query_var('paged') ?: 1;

$args = array(
  'post_type' => 'property',
  'posts_per_page' => 6,
  'paged' => $paged,
);

// Add the same filter logic as in front-page.php:
if (!empty($_GET['type'])) {
  $args['tax_query'] = array(array(
    'taxonomy' => 'property_type',
    'field' => 'slug',
    'terms' => sanitize_text_field($_GET['type']),
  ));
}

$meta_query = array('relation' => 'AND');
if (!empty($_GET['min_price'])) {
  $meta_query[] = array(
    'key' => '_price',
    'value' => (int) $_GET['min_price'],
    'compare' => '>=',
    'type' => 'NUMERIC',
  );
}
if (!empty($_GET['max_price'])) {
  $meta_query[] = array(
    'key' => '_price',
    'value' => (int) $_GET['max_price'],
    'compare' => '<=',
    'type' => 'NUMERIC',
  );
}
if (count($meta_query) > 1) {
  $args['meta_query'] = $meta_query;
}

if (!empty($_GET['keyword'])) {
  $args['s'] = sanitize_text_field($_GET['keyword']);
}

if (!empty($_GET['sort'])) {
  $args['orderby'] = 'meta_value_num';
  $args['meta_key'] = '_price';
  $args['order'] = ($_GET['sort'] === 'high') ? 'DESC' : 'ASC';
}

$query = new WP_Query($args);
if ($query->have_posts()) :
  while ($query->have_posts()): $query->the_post();
    get_template_part('template-parts/property-card');
  endwhile;
else :
  echo '<p>No properties found.</p>';
endif;

wp_reset_postdata();
