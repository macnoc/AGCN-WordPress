<?php

/**
 * AGCN_ajax_handlers class.
 * 
 * This class handles AJAX requests for the AGCN plugin.
 * It includes methods for adding and removing languages.
 * 
 * @since 1.0.0
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */
class AGCN_ajax_handlers
{
    /**
     * Constructor method.
     * 
     * This method hooks into WordPress actions to add AJAX handlers for adding and removing languages.
     */
    public function __construct()
    {
        if (!has_action('wp_ajax_agcn_add_language')) {
            add_action('wp_ajax_agcn_add_language', [$this, 'agcn_add_language_handler']);
        }

        if (!has_action('wp_ajax_agcn_remove_language')) {
            add_action('wp_ajax_agcn_remove_language', [$this, 'agcn_remove_language_handler']);
        }
    }

    /**
     * Handles the AJAX request for adding a language.
     * 
     * This method checks for the necessary permissions and validates the language input.
     * It then updates the plugin options and sends a success response.
     */
    public function agcn_add_language_handler()
    {
        try {
            // Check nonce
            if (!check_ajax_referer('agcn_add_language', 'nonce', false)) {
                wp_send_json_error(__('Security check failed.', 'agcn'));
            }

            // Check user permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(__('You do not have permission to perform this action.', 'agcn'));
            }

            // Check if language is provided
            if (!isset($_POST['language']) || empty($_POST['language'])) {
                wp_send_json_error(__('Language not provided', 'agcn'));
            }

            // Sanitize language input
            $language = sanitize_text_field(wp_unslash($_POST['language']));

            // Check if language already exists
            $options = get_option('agcn_options');

            if (isset($options['content'][$language])) {
                wp_send_json_error(__('Language already exists', 'agcn'));
            }

            // Check if language is available
            $available_languages = AGCN_plugin::agcn_get_config('available_languages');
            $available_languages_keys = array_keys($available_languages);
            if (!in_array($language, $available_languages_keys)) {
                wp_send_json_error(__('This language is not available', 'agcn'));
            }
            
            $language_name = $available_languages[$language];

            // Create empty structure
            $options['content'][$language] = array();

            // Update options
            $update_result = update_option('agcn_options', $options);
            if (!$update_result) {
                wp_send_json_error(__('Failed to update options', 'agcn'));
            }

            // Set transient
            set_transient('agcn_admin_notice', [
                'type' => 'success',
                'message' => sprintf(
                    /* translators: %s: The name of the language that was added */
                    __('Language %s added successfully!', 'agcn'),
                    $language_name
                ),
                'dismissible' => true
            ], 30);

            // Send success response
            wp_send_json_success();
        } catch (Exception $e) {
            // Send error response
            wp_send_json_error(__('An unexpected error occurred.', 'agcn'));
        }
    }

    /**
     * Handles the AJAX request for removing a language.
     * 
     * This method checks for the necessary permissions and validates the language input.
     * It then updates the plugin options and sends a success response.
     */
    public function agcn_remove_language_handler()
    {
        try {
            // Check nonce
            if (!check_ajax_referer('agcn_remove_language', 'nonce', false)) {
                wp_send_json_error(__('Security check failed.', 'agcn'));
            }

            // Check user permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(__('You do not have permission to perform this action.', 'agcn'));
            }

            // Check if language is provided
            if (!isset($_POST['language']) || empty($_POST['language'])) {
                wp_send_json_error(__('Language not provided', 'agcn'));
            }

            // Sanitize language input
            $language = sanitize_text_field(wp_unslash($_POST['language']));

            // Get options
            $options = get_option('agcn_options');

            // Check if language exists
            if (!isset($options['content'][$language])) {
                wp_send_json_error(__('Language does not exist', 'agcn'));
            }

            // Check if there is only one language
            if (count($options['content']) <= 1) {
                wp_send_json_error(__('Cannot remove last language', 'agcn'));
            }

            // Get language name
            $available_languages = AGCN_plugin::agcn_get_config('available_languages');
            $language_name = $available_languages[$language] ?? $language;

            // Remove language
            $options['content'][$language] = array();

            // Update options
            $update_result = update_option('agcn_options', ['remove_language' => $language]);
            if (!$update_result) {
                wp_send_json_error(__('Failed to update options', 'agcn'));
            }

            // Set transient
            set_transient('agcn_admin_notice', [
                'type' => 'success',
                'message' => sprintf(
                    /* translators: %s: The name of the language that was removed */
                    __('Language %s removed successfully!', 'agcn'),
                    $language_name
                ),
                'dismissible' => true
            ], 30);

            // Send success response
            wp_send_json_success();
        } catch (Exception $e) {
            // Send error response
            wp_send_json_error(__('An unexpected error occurred.', 'agcn'));
        }
    }
}
