<?php
// Redirect if filter params are all empty
if (
    isset($_GET['type'], $_GET['min_price'], $_GET['max_price'], $_GET['sort'], $_GET['keyword']) &&
    empty($_GET['type']) && empty($_GET['min_price']) && empty($_GET['max_price']) &&
    empty($_GET['sort']) && empty($_GET['keyword'])
) {
    wp_redirect(home_url('/'));
    exit;
}
?>

<?php get_header(); ?>

<style>
/* (hero/banner and Swiper styles unchanged) */
.hero {
    background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/banner2.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 65vh;
    position: relative;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 15px;
    box-sizing: border-box;
}
.banner-content { text-align: center; width: 100%; max-width: 100%; }
.banner-overlay::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0, 0, 0, 0);
    z-index: 1;
}
/* Swiper Styles */
.property-card {
    background: #fff; border-radius: 10px; overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; padding: 15px;
}
.property-card img { width: 100%; height: auto; border-radius: 5px; }
.property-card h3 { font-size: 1rem; margin-top: 10px; }
.swiper-button-next, .swiper-button-prev { color: #000; }
</style>

<section class="hero">
  <div class="banner-overlay">
    <div class="banner-content">
      <h2>Find Your Dream Home</h2>
      <p>Browse our listings to find the perfect property.</p>
    </div>
  </div>
</section>

<div class="container">

  <!-- Filter Form -->
  <form id="filter-form" method="GET" action="<?php echo esc_url(home_url('/')); ?>" class="property-filter">
    <div class="filter-grid">
      <!-- Property Type -->
      <div class="filter-item">
        <label>Property Type</label>
        <select name="type">
          <option value="">All Types</option>
          <?php
          $terms = get_terms('property_type');
          if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
              $selected = (isset($_GET['type']) && $_GET['type'] === $term->slug) ? 'selected' : '';
              echo '<option value="' . esc_attr($term->slug) . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
            }
          } else {
            echo '<option disabled>No property types found</option>';
          }
          ?>
        </select>
      </div>
      <!-- Min Price -->
      <div class="filter-item">
        <label for="min_price">Min Price (₹)</label>
        <input type="number" name="min_price" id="min_price" value="<?php echo esc_attr($_GET['min_price'] ?? ''); ?>">
      </div>
      <!-- Max Price -->
      <div class="filter-item">
        <label for="max_price">Max Price (₹)</label>
        <input type="number" name="max_price" id="max_price" value="<?php echo esc_attr($_GET['max_price'] ?? ''); ?>">
      </div>
      <!-- Sort -->
      <div class="filter-item">
        <label for="sort">Sort</label>
        <select name="sort" id="sort">
          <option value="">Sort By</option>
          <option value="low" <?php selected($_GET['sort'] ?? '', 'low'); ?>>Price: Low to High</option>
          <option value="high" <?php selected($_GET['sort'] ?? '', 'high'); ?>>Price: High to Low</option>
        </select>
      </div>
      <!-- Keyword -->
      <div class="filter-item">
        <label for="keyword">Keyword</label>
        <input type="text" name="keyword" id="keyword" placeholder="Search..." value="<?php echo esc_attr($_GET['keyword'] ?? ''); ?>">
      </div>
    </div>
    <div class="filter-actions">
      <button type="submit">Apply Filters</button>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="clear-filters">Clear Filters</a>
    </div>
  </form>

  <!-- Featured Section with Swiper -->
  <?php if (empty($_GET)): ?>
  <section class="featured-properties">
    <h2>Featured Properties</h2>
    <div class="swiper featured-swiper">
      <div class="swiper-wrapper">
        <?php
        $featured_query = new WP_Query(array(
          'post_type' => 'property',
          'posts_per_page' => -1,
          'meta_key' => '_is_featured',
          'meta_value' => '1',
        ));
        if ($featured_query->have_posts()) :
          while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
            <div class="swiper-slide">
              <div class="property-card">
                <?php if (has_post_thumbnail()) : ?>
                  <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium'); ?>
                  </a>
                <?php endif; ?>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              </div>
            </div>
          <?php endwhile;
          wp_reset_postdata();
        else : ?>
          <p>No featured properties found.</p>
        <?php endif; ?>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </section>
  <?php endif; ?>

  <!-- Conditionally Load Sidebar -->
  <?php
  $front_page_id = get_option('page_on_front');
  if (
    !( is_page($front_page_id) && is_main_query() ) &&
    !( is_front_page() && is_paged() )
  ) :
    if (is_active_sidebar('main-sidebar')) : ?>
      <aside id="secondary" class="widget-area">
        <?php dynamic_sidebar('main-sidebar'); ?>
      </aside>
    <?php endif;
  endif;
  ?>

  <!-- Latest or Filtered Properties -->
  <h2><?php echo empty($_GET) ? 'Latest Properties' : 'Filtered Properties'; ?></h2>

  <div class="property-grid" id="property-results">
    <?php
    // --- KEY SECTION: Robust pagination parameter ---
    if (get_query_var('paged')) {
        $paged = get_query_var('paged');
    } elseif (get_query_var('page')) {
        $paged = get_query_var('page');
    } else {
        $paged = 1;
    }

    $args = array(
      'post_type' => 'property',
      'posts_per_page' => 6,
      'paged' => $paged,
    );

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
    if (isset($_GET['featured_only']) && $_GET['featured_only'] == '1') {
      $meta_query[] = array(
        'key' => '_is_featured',
        'value' => '1',
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

    $properties = new WP_Query($args);

    if ($properties->have_posts()) :
      while ($properties->have_posts()): $properties->the_post();
        get_template_part('template-parts/property-card');
      endwhile;

      // --- PAGINATION ---
      $base_url = get_pagenum_link(1);
      $format   = 'page/%#%/';
      // Supports query args (works for most static home pages)
      echo '<div class="pagination">';
      echo paginate_links(array(
        'base' => get_pagenum_link(1) . '%_%',
        'format' => '?paged=%#%',
        'total' => $properties->max_num_pages,
        'current' => max(1, $paged),
        'add_args' => $_GET ? array_map('esc_attr', $_GET) : false
      ));
      echo '</div>';
    else:
      echo '<p>No properties found.</p>';
    endif;
    wp_reset_postdata();
    ?>
  </div>
</div>

<!-- Swiper Init Script -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const filter = document.querySelector(".property-filter");
    if (filter) filter.classList.add("visible");
    const pagination = document.querySelector(".pagination");
    if (pagination) pagination.classList.add("visible");
    // Swiper Init
    new Swiper('.featured-swiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        768: { slidesPerView: 3 },
        1024: { slidesPerView: 4 }
      }
    });
  });
</script>

<?php get_footer(); ?>
