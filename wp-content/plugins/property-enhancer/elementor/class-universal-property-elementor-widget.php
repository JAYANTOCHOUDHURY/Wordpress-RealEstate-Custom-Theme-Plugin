<?php
namespace Elementor;
/**
 * Universal Property Widget Elementor Block
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Universal_Property_Elementor_Widget extends Widget_Base {

    public function get_name() {
        return 'universal_property_widget';
    }

    public function get_title() {
        return __('Universal Property Widget', 'property-enhancer');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['property', 'real estate', 'listing', 'grid', 'slider'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'property-enhancer'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => __('Display Layout', 'property-enhancer'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid', 'property-enhancer'),
                    'list' => __('List', 'property-enhancer'),
                    'slider' => __('Slider', 'property-enhancer'),
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Number of Properties', 'property-enhancer'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
    $settings = $this->get_settings_for_display();
    $layout = $settings['layout_type'];
    $count = !empty($settings['posts_per_page']) ? intval($settings['posts_per_page']) : 6;

    $args = [
        'post_type' => 'property',
        'posts_per_page' => $count,
    ];

    $query = new \WP_Query($args);

    if ($query->have_posts()) {
        // Add wrapper classes for layout
        $wrapper_class = ($layout === 'slider') ? 'property-slider swiper-container' : 'property-listings layout-' . esc_attr($layout);
        echo '<div class="universal-property-widget ' . esc_attr($wrapper_class) . '">';

        if ($layout === 'slider') {
            echo '<div class="swiper-wrapper">';
        }

        while ($query->have_posts()) {
            $query->the_post();

            $price = get_post_meta(get_the_ID(), '_property_price', true);
            $location = get_post_meta(get_the_ID(), '_property_location', true);
            $area = get_post_meta(get_the_ID(), '_property_area', true);
            $type = get_the_term_list(get_the_ID(), 'property_type', '', ', ');
            $image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $title = get_the_title();
            $permalink = get_permalink();

            // Open wrapper
            $item_class = ($layout === 'slider') ? 'swiper-slide' : 'property-card';
            echo '<div class="' . esc_attr($item_class) . '">';

            // List layout
            if ($layout === 'list') {
                echo '<div class="property-card-list">';
                echo '<div class="property-thumb"><img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '"></div>';
                echo '<div class="property-content">';
                echo '<h4>' . esc_html($title) . '</h4>';
                echo '<p><strong>Price:</strong> ' . esc_html($price) . '</p>';
                echo '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
                echo '<p><strong>Area:</strong> ' . esc_html($area) . '</p>';
                echo '<p><strong>Type:</strong> ' . wp_kses_post($type) . '</p>';
                echo '<a href="' . esc_url($permalink) . '" class="btn">View Details</a>';
                echo '</div></div>';
            } else {
                // Grid or Slider
                echo '<div class="property-card-grid">';
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" />';
                echo '<h4>' . esc_html($title) . '</h4>';
                echo '<p><strong>Price:</strong> ' . esc_html($price) . '</p>';
                echo '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
                echo '<p><strong>Area:</strong> ' . esc_html($area) . '</p>';
                echo '<p><strong>Type:</strong> ' . wp_kses_post($type) . '</p>';
                echo '<a href="' . esc_url($permalink) . '" class="btn">View Details</a>';
                echo '</div>';
            }

            echo '</div>'; // Close item
        }

        if ($layout === 'slider') {
            echo '</div>'; // .swiper-wrapper
            echo '<div class="swiper-pagination"></div>';
            echo '<div class="swiper-button-prev"></div>';
            echo '<div class="swiper-button-next"></div>';
        }

        echo '</div>'; // .universal-property-widget
        wp_reset_postdata();
    } else {
        echo '<p>No properties found.</p>';
    }
}


    protected function _content_template() {}
}

// ✅ Register the widget — this should go ONLY ONCE (NOT in this file if it's already required elsewhere)
add_action('elementor/widgets/widgets_registered', function($widgets_manager) {
    if (!class_exists('Universal_Property_Elementor_Widget')) {
        require_once plugin_dir_path(__FILE__) . 'class-universal-property-elementor-widget.php';
    }
    $widgets_manager->register(new \Universal_Property_Elementor_Widget());
});
