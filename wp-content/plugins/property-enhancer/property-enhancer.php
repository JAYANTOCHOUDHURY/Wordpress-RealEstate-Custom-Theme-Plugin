<?php
/**
 * Plugin Name: Real Estate Plugin
 * Description: Custom plugin for advanced real estate features.
 * Version: 1.0
 * Author: Jayanto Choudhury
 */

require_once plugin_dir_path(__FILE__) . 'includes/mortgage-calculator-widget.php';
// Include Shortcodes
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/class-property-shortcodes.php';
new \PropertyEnhancer\Shortcodes\Property_Shortcodes();

require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/class-property-by-type-shortcode.php';
new \PropertyEnhancer\Shortcodes\Property_By_Type_Shortcode();

// CPT
require_once plugin_dir_path(__FILE__) . 'includes/cpt/class-agent-application-cpt.php';
new \PropertyEnhancer\CPT\Agent_Application_CPT();

// Shortcode
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/class-agent-registration-shortcode.php';
new \PropertyEnhancer\Shortcodes\Agent_Registration_Shortcode();

// Admin Meta Box
require_once plugin_dir_path(__FILE__) . 'includes/admin/class-agent-application-admin.php';
new \PropertyEnhancer\Admin\Agent_Application_Admin();


function pe_register_widgets() {
    register_widget('Mortgage_Calculator_Widget');
}
add_action('widgets_init', 'pe_register_widgets');

function pe_enqueue_calculator_assets() {
    wp_enqueue_script(
        'mortgage-calculator-js',
        plugin_dir_url(__FILE__) . '/assets/js/mortgage-calculator.js',
        [],
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'pe_enqueue_calculator_assets');

function pe_enqueue_mortgage_calculator_assets() {
    // Only load on frontend
    if (!is_admin()) {
        wp_enqueue_style(
            'pe-mortgage-calculator-style',
            plugin_dir_url(__FILE__) . 'assets/css/mortgage-calculator.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'pe_enqueue_mortgage_calculator_assets');

// Elementor Widget Registration
function pe_register_elementor_widgets() {
    // Load only if Elementor is active
    if (did_action('elementor/loaded')) {

        // Load class file
        require_once plugin_dir_path(__FILE__) . 'elementor/class-mortgage-calculator-elementor-widget.php';

        // Register the widget using the correct namespace
        \Elementor\Plugin::instance()->widgets_manager->register( new \PE_Elementor\Mortgage_Calculator_Widget() );
    }
}
add_action('elementor/widgets/register', 'pe_register_elementor_widgets');



// Register Elementor widgets
add_action('elementor/widgets/register', function($widgets_manager) {
    require_once plugin_dir_path(__FILE__) . 'elementor/class-universal-property-elementor-widget.php';
    $widgets_manager->register(new \Elementor\Universal_Property_Elementor_Widget());
});

// Enqueue assets
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('universal-property-css', plugin_dir_url(__FILE__) . 'assets/css/universal-property.css');
    wp_enqueue_script('universal-property-js', plugin_dir_url(__FILE__) . 'assets/js/universal-property.js', ['jquery'], false, true);

    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', [], null, true);
    wp_enqueue_script('universal-property-js', plugin_dir_url(__FILE__) . 'assets/js/universal-property-widget.js', ['swiper-js'], null, true);

});

function pe_enqueue_agent_form_styles() {
    if (is_page()) { // or add condition if this form is only on certain pages
        wp_enqueue_style(
            'pe-agent-registration-style',
            plugin_dir_url(__FILE__) . 'assets/css/agent-registration-form.css',
            [],
            '1.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'pe_enqueue_agent_form_styles');
