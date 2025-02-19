<?php

/**
 * AGCN (AI-Generated Content Notifier)
 *
 * @package           AGCN
 * @author            Macnoc
 * @copyright         2024 Macnoc
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       AGCN (AI-Generated Content Notifier)
 * Plugin URI:        https://github.com/macnoc/AGCN
 * Description:       A modern WordPress plugin to inform visitors about AI-generated content on your website.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Macnoc
 * Author URI:        https://github.com/macnoc
 * Text Domain:       agcn
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 */

if (!defined('WPINC')) {
    die;
}


// Define plugin constants
define('AGCN_VERSION', '1.0.0');
define('AGCN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AGCN_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
foreach (glob(AGCN_PLUGIN_DIR . 'includes' . '/*.php') as $file) {
    require_once $file;
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, [AGCN_plugin::class, 'activator']);
register_deactivation_hook(__FILE__, [AGCN_plugin::class, 'deactivator']);

// If in admin, initialize admin classes
if (is_admin()) {
    new AGCN_admin;
    new AGCN_ajax_handlers;
}

// Initialize the loader
new AGCN_loader;

// Add settings link to plugin page
$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename", [AGCN_plugin::class, 'agcn_settings_link']);

// Load plugin text domain
add_action('plugins_loaded', [AGCN_plugin::class, 'agcn_load_plugin_textdomain']);
