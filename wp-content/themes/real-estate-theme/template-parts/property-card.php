<div class="property-card">

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
        // Show nothing or a non-clickable heart if you wish, or a tooltip.
        echo '<a href="' . esc_url(wp_login_url(get_permalink(get_the_ID()))) . '" class="wishlist-icon-btn" title="Login to add to Wish List">
            <i class="fa-regular fa-heart"></i>
        </a>';
    }
    ?>

    <?php if (has_post_thumbnail()): ?>
        <div class="property-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="property-details">
        <h3 class="property-title"><?php the_title(); ?></h3>

        <?php
        $terms = get_the_terms(get_the_ID(), 'property_type');
        if ($terms && !is_wp_error($terms)) {
            $types = wp_list_pluck($terms, 'name');
            echo '<p class="property-type">' . implode(', ', $types) . '</p>';
        }

        $price = get_post_meta(get_the_ID(), '_price', true);
        $location = get_post_meta(get_the_ID(), '_location', true);
        $area = get_post_meta(get_the_ID(), '_area', true);
        ?>

        <ul class="property-meta">
            <?php if ($price): ?>
                <li><strong>Price:</strong> ₹<?php echo esc_html($price); ?></li>
            <?php endif; ?>
            <?php if ($location): ?>
                <li><strong>Location:</strong> <?php echo esc_html($location); ?></li>
            <?php endif; ?>
            <?php if ($area): ?>
                <li><strong>Area:</strong> <?php echo esc_html($area); ?> sqft</li>
            <?php endif; ?>
        </ul>

        <a href="<?php the_permalink(); ?>" class="view-button">View Details</a>

        <?php // ---- HERE: add Remove button ONLY on wish list page ---- ?>
        <?php if (function_exists('is_wishlist_page') && is_wishlist_page()): ?>
            <button
                class="wishlist-remove-btn"
                data-property="<?php echo get_the_ID(); ?>"
                data-action="remove"
                data-nonce="<?php echo esc_attr(wp_create_nonce('wishlist_nonce')); ?>"
                style="margin-top:10px;"
            >Remove from Wish List</button>
        <?php endif; ?>
    </div>
</div>
