<?php
namespace PropertyEnhancer\Admin;

if (!defined('ABSPATH')) exit;

class Agent_Application_Admin {

    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'agent_application_details',
            'Agent Details',
            [$this, 'render_meta_box'],
            'agent_application',
            'normal',
            'default'
        );
    }

    public function render_meta_box($post) {
        $email = get_post_meta($post->ID, '_agent_email', true);
        $phone = get_post_meta($post->ID, '_agent_phone', true);
        $experience = get_post_meta($post->ID, '_agent_experience', true);
        $resume = get_post_meta($post->ID, '_agent_resume', true);

        echo "<p><strong>Email:</strong> {$email}</p>";
        echo "<p><strong>Phone:</strong> {$phone}</p>";
        echo "<p><strong>Experience:</strong> {$experience} years</p>";
        echo $resume ? "<p><strong>Resume:</strong> <a href='{$resume}' target='_blank'>Download</a></p>" : '';
    }
}
