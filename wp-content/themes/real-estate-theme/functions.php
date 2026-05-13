<?php
// Enqueue Styles and Scripts
function realestate_theme_scripts() {
  wp_enqueue_style('style', get_stylesheet_uri());
  wp_enqueue_style('custom-css', get_template_directory_uri() . '/assets/css/style.css');
  wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v6.4.0/css/all.css');
  wp_enqueue_script('custom-js', get_template_directory_uri() . '/assets/js/script.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'realestate_theme_scripts');


// Theme Setup
function realestate_theme_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size('admin-thumb', 60, 60, true);

  register_nav_menus(array(
    'main-menu'   => __('Main Menu'),
    'mobile-menu' => __('Mobile Menu')
  ));
}
add_action('after_setup_theme', 'realestate_theme_setup');

// Register Custom Post Type: Property
function realestate_register_property_post_type() {
  register_post_type('property', array(
    'labels' => array(
      'name' => 'Properties',
      'singular_name' => 'Property',
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'properties'),
    'supports' => array('title', 'editor', 'thumbnail'),
    'menu_icon' => 'dashicons-admin-home',
  ));
}
add_action('init', 'realestate_register_property_post_type');


// Register Taxonomy: Property Type
function realestate_register_property_taxonomies() {
  register_taxonomy('property_type', 'property', array(
    'labels' => array(
      'name' => 'Property Types',
      'singular_name' => 'Property Type',
    ),
    'hierarchical' => true,
    'public' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'rewrite' => array('slug' => 'type'),
  ));
}
add_action('init', 'realestate_register_property_taxonomies');

// Meta Boxes for Property Details
function realestate_add_custom_meta_box() {
  add_meta_box('property_details', 'Property Details', 'realestate_display_custom_fields', 'property', 'normal', 'high');
}
add_action('add_meta_boxes', 'realestate_add_custom_meta_box');

function realestate_display_custom_fields($post) {
  $price = get_post_meta($post->ID, '_price', true);
  $location = get_post_meta($post->ID, '_location', true);
  $status = get_post_meta($post->ID, '_status', true);
  $area = get_post_meta($post->ID, '_area', true);
  $map_embed = get_post_meta($post->ID, '_map_embed', true);
  $contact_email = get_post_meta($post->ID, '_contact_email', true);
  ?>
  <label><strong>Price:</strong></label><br>
  <input type="text" name="property_price" value="<?php echo esc_attr($price); ?>" style="width:100%;"><br><br>

  <label><strong>Location:</strong></label><br>
  <input type="text" name="property_location" value="<?php echo esc_attr($location); ?>" style="width:100%;"><br><br>

  <label><strong>Status:</strong></label><br>
  <select name="property_status" style="width:100%;">
    <option value="Ready to Move" <?php selected($status, 'Ready to Move'); ?>>Ready to Move</option>
    <option value="Under Construction" <?php selected($status, 'Under Construction'); ?>>Under Construction</option>
  </select><br><br>

  <label><strong>Area (sqft):</strong></label><br>
  <input type="text" name="property_area" value="<?php echo esc_attr($area); ?>" style="width:100%;"><br><br>

  <label><strong>Google Maps Embed Code:</strong></label><br>
  <textarea name="property_map_embed" rows="4" style="width:100%;"><?php echo esc_textarea($map_embed); ?></textarea><br><br>

  <label><strong>Contact Email (Agent):</strong></label><br>
  <input type="email" name="property_contact_email" value="<?php echo esc_attr($contact_email); ?>" style="width:100%;"><br><br>
  <?php
}

function realestate_save_custom_fields($post_id) {
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  if (isset($_POST['property_price'])) update_post_meta($post_id, '_price', sanitize_text_field($_POST['property_price']));
  if (isset($_POST['property_location'])) update_post_meta($post_id, '_location', sanitize_text_field($_POST['property_location']));
  if (isset($_POST['property_status'])) update_post_meta($post_id, '_status', sanitize_text_field($_POST['property_status']));
  if (isset($_POST['property_area'])) update_post_meta($post_id, '_area', sanitize_text_field($_POST['property_area']));
  if (isset($_POST['property_map_embed'])) update_post_meta($post_id, '_map_embed', sanitize_textarea_field($_POST['property_map_embed']));
  if (isset($_POST['property_contact_email'])) update_post_meta($post_id, '_contact_email', sanitize_email($_POST['property_contact_email']));
}
add_action('save_post', 'realestate_save_custom_fields');

// Featured Property Meta Box
function realestate_add_featured_meta_box() {
  add_meta_box('realestate_featured', 'Featured Property', 'realestate_featured_field_callback', 'property');
}
add_action('add_meta_boxes', 'realestate_add_featured_meta_box');

function realestate_featured_field_callback($post) {
  $is_featured = get_post_meta($post->ID, '_is_featured', true);
  ?>
  <label>
    <input type="checkbox" name="realestate_is_featured" value="1" <?php checked($is_featured, '1'); ?> />
    Mark as Featured
  </label>
  <?php
}

function realestate_save_featured_meta($post_id) {
  $is_featured = isset($_POST['realestate_is_featured']) ? '1' : '';
  update_post_meta($post_id, '_is_featured', $is_featured);
}
add_action('save_post', 'realestate_save_featured_meta');

// Property Gallery
function property_gallery_meta_box() {
  add_meta_box('property_gallery', 'Property Gallery', 'property_gallery_callback', 'property', 'normal', 'high');
}
add_action('add_meta_boxes', 'property_gallery_meta_box');

function property_gallery_callback($post) {
  wp_nonce_field('property_gallery_nonce_action', 'property_gallery_nonce');
  $gallery = get_post_meta($post->ID, '_property_gallery', true);
  $image_ids = is_string($gallery) ? array_filter(explode(',', $gallery)) : (is_array($gallery) ? $gallery : []);

  echo '<div><a href="#" class="upload-gallery button">Add Gallery Images</a><div id="gallery-preview" style="margin-top:10px;">';
  foreach ($image_ids as $image_id) {
    $thumb_url = wp_get_attachment_thumb_url($image_id);
    if ($thumb_url) {
      echo '<img src="' . esc_url($thumb_url) . '" style="max-width:100px;margin:5px;">';
    }
  }
  echo '</div><input type="hidden" name="property_gallery" id="property_gallery" value="' . esc_attr(implode(',', $image_ids)) . '"></div>';
}

function save_property_gallery($post_id) {
  if (!isset($_POST['property_gallery_nonce']) || !wp_verify_nonce($_POST['property_gallery_nonce'], 'property_gallery_nonce_action')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_post', $post_id)) return;

  if (isset($_POST['property_gallery'])) {
    update_post_meta($post_id, '_property_gallery', sanitize_text_field($_POST['property_gallery']));
  } else {
    delete_post_meta($post_id, '_property_gallery');
  }
}
add_action('save_post', 'save_property_gallery');

function enqueue_property_gallery_scripts($hook) {
  if ($hook === 'post.php' || $hook === 'post-new.php') {
    wp_enqueue_media();
    wp_enqueue_script('property-gallery', get_template_directory_uri() . '/assets/js/property-gallery.js', array('jquery'), null, true);
  }
}
add_action('admin_enqueue_scripts', 'enqueue_property_gallery_scripts');

// Admin Columns
function custom_property_columns($columns) {
  return array(
    'cb' => $columns['cb'],
    'title' => __('Title'),
    'price' => __('Price (₹)'),
    'property_type' => __('Property Type'),
    'thumbnail' => __('Image')
  );
}
add_filter('manage_property_posts_columns', 'custom_property_columns');

function custom_property_column_content($column, $post_id) {
  switch ($column) {
    case 'price':
      $price = get_post_meta($post_id, '_price', true);
      echo '₹ ' . number_format((float) str_replace(',', '', $price));
      break;
    case 'property_type':
      $terms = get_the_terms($post_id, 'property_type');
      echo $terms && !is_wp_error($terms) ? esc_html(implode(', ', wp_list_pluck($terms, 'name'))) : '—';
      break;
    case 'thumbnail':
      echo has_post_thumbnail($post_id) ? get_the_post_thumbnail($post_id, 'admin-thumb') : '—';
      break;
  }
}
add_action('manage_property_posts_custom_column', 'custom_property_column_content', 10, 2);

add_filter('manage_edit-property_sortable_columns', function ($columns) {
  $columns['price'] = 'price';
  return $columns;
});

add_action('pre_get_posts', function ($query) {
  if (is_admin() && $query->is_main_query() && $query->get('orderby') === 'price') {
    $query->set('meta_key', '_price');
    $query->set('orderby', 'meta_value_num');
  }
});

function fix_static_front_page_pagination() {
    if (is_front_page()) {
        global $wp_query;
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $wp_query->set('paged', $paged);
    }
}
add_action('pre_get_posts', 'fix_static_front_page_pagination');



// Register Inquiry CPT
function realestate_register_inquiry_post_type() {
  register_post_type('inquiry', array(
    'labels' => array(
      'name' => 'Inquiries',
      'singular_name' => 'Inquiry',
    ),
    'public' => false,
    'show_ui' => true,
    'supports' => array('title', 'editor'),
    'menu_icon' => 'dashicons-email',
  ));
}
add_action('init', 'realestate_register_inquiry_post_type');

// Handle Property Inquiry (No Duplicate Submissions)
add_action('init', 'handle_property_inquiry_form');
function handle_property_inquiry_form() {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_inquiry_nonce']) && wp_verify_nonce($_POST['property_inquiry_nonce'], 'property_inquiry_action')) {
    if (!empty($_POST['user_name']) && !empty($_POST['user_email']) && !empty($_POST['user_message']) && !empty($_POST['user_phone'])) {
      $name        = sanitize_text_field($_POST['user_name']);
      $email       = sanitize_email($_POST['user_email']);
      $phone       = sanitize_text_field($_POST['user_phone']);
      $message     = sanitize_textarea_field($_POST['user_message']);
      $property_id = intval($_POST['property_id']);
      $to_email    = sanitize_email($_POST['to_email']);

      $property_title = get_the_title($property_id);
      $property_link  = get_permalink($property_id);

      $transient_key = 'submitted_inquiry_' . md5($email . $property_id);

      if (false === get_transient($transient_key)) {
        // Save to admin
        wp_insert_post(array(
          'post_type'    => 'inquiry',
          'post_title'   => 'Inquiry from ' . $name . ' – Property: ' . $property_title,
          'post_content' => "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message\n\nProperty: $property_title\nLink: $property_link",
          'post_status'  => 'publish'
        ));

        // Send email
        $subject = 'New Property Inquiry';
        $body    = "You have received a new inquiry:\n\n"
                 . "Property: $property_title\n"
                 . "Link: $property_link\n\n"
                 . "Name: $name\n"
                 . "Email: $email\n"
                 . "Phone: $phone\n\n"
                 . "Message:\n$message";
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        wp_mail($to_email, $subject, $body, $headers);

        set_transient($transient_key, 'sent', 30);

        wp_redirect(get_permalink($property_id) . '?inquiry=success');
        exit;
      }
    }
  }
}

// Enqueue Swiper for featured slider
function enqueue_featured_slider_assets() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), null, true);
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/assets/js/script.js', array('swiper-js'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_featured_slider_assets');

// Get current user's wish list property IDs
function get_wishlist_properties($user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    $saved = get_user_meta($user_id, 'wishlist_properties', true);
    return is_array($saved) ? $saved : [];
}

// Save the updated wish list
function save_wishlist_properties($property_ids, $user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    update_user_meta($user_id, 'wishlist_properties', $property_ids);
}

add_action('wp_ajax_toggle_wishlist_property', function() {
    check_ajax_referer('wishlist_nonce', 'nonce');
    if (!is_user_logged_in() || empty($_POST['property_id']) || empty($_POST['action_type'])) {
        wp_send_json_error('Invalid request');
    }

    $user_id     = get_current_user_id();
    $property_id = intval($_POST['property_id']);
    $action_type = sanitize_text_field($_POST['action_type']);
    $saved = get_wishlist_properties($user_id);

    if ($action_type === 'add' && !in_array($property_id, $saved)) {
        $saved[] = $property_id;
    } elseif ($action_type === 'remove') {
        $saved = array_diff($saved, [$property_id]);
    }
    save_wishlist_properties($saved, $user_id);

    $label  = in_array($property_id, $saved) ? 'Remove from Wish List' : 'Add to Wish List';
    $action = in_array($property_id, $saved) ? 'remove' : 'add';
    wp_send_json_success([
        'label'  => $label,
        'action' => $action,
    ]);
});

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('wishlist-ajax', get_template_directory_uri() . '/assets/js/wishlist.js', ['jquery'], null, true);
    wp_localize_script('wishlist-ajax', 'wishlist_ajax', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});


add_shortcode('my_wishlist', function() {
    if (!is_user_logged_in()) {
        return '<p>Please log in to view your wish list.</p>';
    }
    $saved = get_wishlist_properties();
    if (empty($saved)) {
        return '<p>Your wish list is empty.</p>';
    }
    $q = new WP_QUERY([
        'post_type' => 'property',
        'post__in' => $saved,
        'orderby' => 'post__in',
        'posts_per_page' => -1,  
    ]);
    ob_start();
    echo '<div class="wishlist-grid">';
    while ($q->have_posts()) {
        $q->the_post();
        get_template_part('template-parts/property-card');
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
});


function is_wishlist_page() {
    global $post;
    return is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'my_wishlist');
}

add_filter('wp_nav_menu_items', function($items, $args) {
    // Add to both 'main-menu' and 'mobile-menu' (or whatever your theme uses)
    $locations = ['main-menu', 'mobile-menu'];
    if (!isset($args->theme_location) || !in_array($args->theme_location, $locations)) {
        return $items;
    }
    if (is_user_logged_in()) {
        $saved = get_wishlist_properties();
        $count = count($saved);
        $wishlist_page = get_page_by_path('my-wish-list'); // Adjust the slug if needed
        $wishlist_link = $wishlist_page ? get_permalink($wishlist_page) : '#';
        $label = 'Wish List';
        if ($count > 0) {
            $label .= ' <span class="wishlist-count-badge">'.$count.'</span>';
        }
        $items .= '<li><a href="'.esc_url($wishlist_link).'">'.$label.'</a></li>';
    }
    return $items;
}, 10, 2);