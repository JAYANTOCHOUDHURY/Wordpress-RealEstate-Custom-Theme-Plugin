<?php
namespace PropertyEnhancer\Shortcodes;

if (!defined('ABSPATH')) exit;

class Agent_Registration_Shortcode {

    public function __construct() {
        add_shortcode('agent_registration_form', [$this, 'render_form']);
        add_action('init', [$this, 'handle_form_submission']);
    }

    public function render_form() {
        ob_start();

        if (isset($_GET['agent_submitted']) && $_GET['agent_submitted'] === 'true') {
            echo '<p class="success-msg">Your application has been submitted successfully!</p>';
        }

        ?>
        <form method="post" enctype="multipart/form-data" class="agent-registration-form">
            <?php wp_nonce_field('agent_registration_action', 'agent_registration_nonce'); ?>

            <label>Full Name*</label>
            <input type="text" name="agent_name" required>

            <label>Email*</label>
            <input type="email" name="agent_email" required>

            <label>Phone*</label>
            <input type="text" name="agent_phone" required>

            <label>Experience (Years)*</label>
            <input type="number" name="agent_experience" required min="0">

            <label>Message</label>
            <textarea name="agent_message"></textarea>

            <label>Resume (PDF/DOC/DOCX)*</label>
            <input type="file" name="agent_resume" accept=".pdf,.doc,.docx" required>

            <input type="submit" name="agent_submit" value="Submit Application">
        </form>
        <?php

        return ob_get_clean();
    }

    public function handle_form_submission() {
        if (!isset($_POST['agent_submit']) || !isset($_POST['agent_registration_nonce'])) return;

        if (!wp_verify_nonce($_POST['agent_registration_nonce'], 'agent_registration_action')) return;

        $name = sanitize_text_field($_POST['agent_name']);
        $email = sanitize_email($_POST['agent_email']);
        $phone = sanitize_text_field($_POST['agent_phone']);
        $experience = intval($_POST['agent_experience']);
        $message = sanitize_textarea_field($_POST['agent_message']);

        // Handle file upload
        if (!function_exists('wp_handle_upload')) require_once(ABSPATH . 'wp-admin/includes/file.php');

        $resume_url = '';
        if (!empty($_FILES['agent_resume']['name'])) {
            $upload = wp_handle_upload($_FILES['agent_resume'], ['test_form' => false]);
            if (!isset($upload['error'])) {
                $resume_url = esc_url($upload['url']);
            }
        }

        $post_id = wp_insert_post([
            'post_type' => 'agent_application',
            'post_title' => $name,
            'post_content' => $message,
            'post_status' => 'publish'
        ]);

        if ($post_id) {
            update_post_meta($post_id, '_agent_email', $email);
            update_post_meta($post_id, '_agent_phone', $phone);
            update_post_meta($post_id, '_agent_experience', $experience);
            update_post_meta($post_id, '_agent_resume', $resume_url);

            wp_redirect(add_query_arg('agent_submitted', 'true', wp_get_referer()));
            exit;
        }
    }
}
