<?php
namespace PropertyEnhancer\Shortcodes;

if (!defined('ABSPATH')) exit;

class Property_Shortcodes {

    public function __construct() {
        add_shortcode('property_filter', [$this, 'render_property_filter']);
        add_shortcode('property_listings', [$this, 'render_property_listings']);
    }

    // === Filter Section Shortcode ===
    public function render_property_filter() {
        ob_start(); ?>
        <form id="filter-form" method="GET" action="<?php echo esc_url(get_permalink(get_option('page_on_front'))); ?>" class="property-filter shortcode-filter-form">
            <div class="filter-grid">
                <!-- Property Type -->
                <div class="filter-item">
                    <label>Property Type</label>
                    <select name="type">
                        <option value="">All Types</option>
                        <?php
                        $terms = get_terms('property_type');
                        if (!is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                $selected = (isset($_GET['type']) && $_GET['type'] === $term->slug) ? 'selected' : '';
                                echo '<option value="' . esc_attr($term->slug) . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
                            }
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
                <a href="<?php echo esc_url(get_permalink(get_option('page_on_front'))); ?>" class="clear-filters">Clear Filters</a>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }

    // === Listings Section Shortcode ===
    public function render_property_listings($atts) {
        ob_start();

        $paged = max(1, get_query_var('paged') ?: get_query_var('page'));

        $args = [
            'post_type' => 'property',
            'posts_per_page' => 6,
            'paged' => $paged,
        ];

        // Taxonomy filter
        if (!empty($_GET['type'])) {
            $args['tax_query'] = [[
                'taxonomy' => 'property_type',
                'field' => 'slug',
                'terms' => sanitize_text_field($_GET['type']),
            ]];
        }

        // Meta filters
        $meta_query = ['relation' => 'AND'];
        if (!empty($_GET['min_price'])) {
            $meta_query[] = [
                'key' => '_price',
                'value' => (int) $_GET['min_price'],
                'compare' => '>=',
                'type' => 'NUMERIC',
            ];
        }
        if (!empty($_GET['max_price'])) {
            $meta_query[] = [
                'key' => '_price',
                'value' => (int) $_GET['max_price'],
                'compare' => '<=',
                'type' => 'NUMERIC',
            ];
        }
        if (count($meta_query) > 1) {
            $args['meta_query'] = $meta_query;
        }

        // Keyword
        if (!empty($_GET['keyword'])) {
            $args['s'] = sanitize_text_field($_GET['keyword']);
        }

        // Sorting
        if (!empty($_GET['sort'])) {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_price';
            $args['order'] = ($_GET['sort'] === 'high') ? 'DESC' : 'ASC';
        }

        $properties = new \WP_Query($args);

        echo '<div class="property-grid">';
        if ($properties->have_posts()) :
            while ($properties->have_posts()) : $properties->the_post();
                get_template_part('template-parts/property-card');
            endwhile;

            echo '<div class="pagination">';
            echo paginate_links([
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'current' => $paged,
                'total' => $properties->max_num_pages,
                'add_args' => $_GET,
            ]);
            echo '</div>';
        else:
            echo '<p>No properties found.</p>';
        endif;
        echo '</div>';

        wp_reset_postdata();

        return ob_get_clean();
    }
}
