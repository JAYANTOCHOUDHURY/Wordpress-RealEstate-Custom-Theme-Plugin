<?php
namespace PropertyEnhancer\Shortcodes;

if (!defined('ABSPATH')) exit;

class Property_By_Type_Shortcode {

    public function __construct() {
        add_shortcode('property_type_listings', [$this, 'render_by_type']);
    }

    public function render_by_type($atts) {
        $atts = shortcode_atts([
            'type' => '', // property_type term slug
        ], $atts, 'property_type_listings');

        if (empty($atts['type'])) {
            return '<p>Please specify a property type.</p>';
        }

        ob_start();

        // Reliable pagination logic
        $paged = get_query_var('paged') ?: (isset($_GET['paged']) ? intval($_GET['paged']) : 1);

        $args = [
            'post_type' => 'property',
            'posts_per_page' => 6,
            'paged' => $paged,
            'tax_query' => [[
                'taxonomy' => 'property_type',
                'field' => 'slug',
                'terms' => sanitize_text_field($atts['type']),
            ]]
        ];

        $query = new \WP_Query($args);

        echo '<div class="property-grid">';

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                // Renders from your theme's template-parts/property-card.php
                get_template_part('template-parts/property-card');
            endwhile;

            echo '<div class="pagination">';
            echo paginate_links([
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'current' => $paged,
                'total' => $query->max_num_pages,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ]);
            echo '</div>';
        else:
            echo '<p>No properties found for this type.</p>';
        endif;

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }
}
