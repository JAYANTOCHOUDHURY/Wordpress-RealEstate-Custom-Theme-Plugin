<?php get_header(); ?>

<main class="single-property">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="container property-card" style="position:relative;">
      <h1 class="property-title" style=" margin-left: 18px"><?php the_title(); ?></h1>
      <?php
    if (is_user_logged_in()) {
        $saved = get_wishlist_properties();
        $property_id = get_the_ID();
        $is_saved = in_array($property_id, $saved);
        $action = $is_saved ? 'remove' : 'add';
        $nonce = wp_create_nonce('wishlist_nonce');
        $icon = $is_saved ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
        $label = $is_saved ? 'Remove from Wish List' : 'Add to Wish List';
        echo '<button class="wishlist-icon-btn" aria-label="'.esc_attr($label).'"
            data-property="' . esc_attr($property_id) . '" 
            data-action="' . esc_attr($action) . '" 
            data-nonce="' . esc_attr($nonce) . '">
            <i class="' . esc_attr($icon) . '"></i>
        </button>';
    } else {
        echo '<a href="' . esc_url(wp_login_url(get_permalink(get_the_ID()))) . '" class="wishlist-icon-btn" title="Login to add to Wish List">
            <i class="fa-regular fa-heart"></i>
        </a>';
    }
    ?>

      <?php if (has_post_thumbnail()) : ?>
        <div class="property-image">
          <?php the_post_thumbnail('large'); ?>
        </div>
      <?php endif; ?>

      <?php
      $gallery = get_post_meta(get_the_ID(), '_property_gallery', true);

      if (!empty($gallery) && is_string($gallery)) {
        $image_ids = array_filter(explode(',', $gallery));
      } elseif (is_array($gallery)) {
        $image_ids = $gallery;
      } else {
        $image_ids = [];
      }

      if (!empty($image_ids)) {
        echo '<div class="property-gallery">';
        foreach ($image_ids as $image_id) {
          echo '<img src="' . esc_url(wp_get_attachment_image_url($image_id, 'large')) . '" class="gallery-image" />';
        }
        echo '</div>';
      }
      ?>

      <ul class="property-meta">
        <li><strong>Price:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_price', true)); ?></li>
        <li><strong>Location:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_location', true)); ?></li>
        <li><strong>Status:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_status', true)); ?></li>
        <li><strong>Area:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_area', true)); ?> sqft</li>
        <li><strong>Type:</strong>
          <?php
          $terms = get_the_terms(get_the_ID(), 'property_type');
          if ($terms && !is_wp_error($terms)) {
            $types = wp_list_pluck($terms, 'name');
            echo esc_html(implode(', ', $types));
          }
          ?>
        </li>
      </ul>

      <div class="property-content">
        <?php the_content(); ?>
      </div>

      <?php
      $map_embed = get_post_meta(get_the_ID(), '_map_embed', true);
      $contact_email = get_post_meta(get_the_ID(), '_contact_email', true);
      ?>

      <?php if ($map_embed): ?>
        <div class="property-map" style="margin-top:30px;">
          <h3>Location</h3>
          <div class="map-embed" style="width:100%;height:auto;">
            <?php echo $map_embed; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($contact_email): ?>
        <div class="property-contact-form" style="margin-top:30px;">
          <?php if (isset($_GET['inquiry']) && $_GET['inquiry'] === 'success') : ?>
            <div class="success-message" style="background-color: #e6ffed; border-left: 4px solid #2ecc71; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
              ✅ Thank you! Your inquiry has been sent.
            </div>
          <?php endif; ?>
          <h3>Contact Agent</h3>
          <form method="post" action="">
            <input type="hidden" name="property_id" value="<?php echo get_the_ID(); ?>">
            <input type="hidden" name="to_email" value="<?php echo esc_attr($contact_email); ?>">
            <input type="text" name="user_name" placeholder="Your Name" required>
            <input type="email" name="user_email" placeholder="Your Email" required>
            <input type="tel" name="user_phone" placeholder="Your Phone Number" required>
            <textarea name="user_message" placeholder="Your Message" required></textarea>
            <?php wp_nonce_field('property_inquiry_action', 'property_inquiry_nonce'); ?>
            <button type="submit">Send Inquiry</button>
          </form>

        </div>
      <?php endif; ?>

    </div>
  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
