<?php
namespace PropertyEnhancer\CPT;

if (!defined('ABSPATH')) exit;

class Agent_Application_CPT {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
    }

    public function register_cpt() {
        $labels = [
            'name' => 'Agent Applications',
            'singular_name' => 'Agent Application',
            'menu_name' => 'Agent Applications',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Agent Application',
            'edit_item' => 'Edit Agent Application',
            'new_item' => 'New Agent Application',
            'view_item' => 'View Agent Application',
            'all_items' => 'All Applications',
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-id', // WordPress icon
            'supports' => ['title', 'editor'],
        ];

        register_post_type('agent_application', $args);
    }
}
