<?php

/**
 * AGCN_plugin class.
 * 
 * This class handles the plugin configuration and activation.
 * It includes methods for retrieving configuration, activating the plugin, and deactivating the plugin.
 * 
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */
class AGCN_plugin
{
    private static $default = null;

    /**
     * Retrieves the configuration for the AGCN plugin.
     * 
     * This method checks if the configuration is already cached and returns it if so.
     * Otherwise, it retrieves the configuration from the plugin configuration file and caches it.
     * 
     * @param string $config The configuration to retrieve.
     * @return array The configuration.
     */
    public static function get_config($config)
    {
        if (self::$default === null) {
            self::$default = require AGCN_PLUGIN_DIR . 'agcn-config.php';
        }

        return self::$default[$config];
    }

    /**
     * Activates the AGCN plugin.
     * 
     * This method checks if the options and styles are already set and updates them if not.
     */
    public static function activator()
    {
        if (!get_option('agcn_options')) {
            update_option('agcn_options', self::get_config('options_default'));
        }

        if (!get_option('agcn_styles')) {
            update_option('agcn_styles', self::get_config('styles_default'));
        }
    }

    /**
     * Deactivates the AGCN plugin.
     * 
     * This method flushes the cache.
     */
    public static function deactivator()
    {
        wp_cache_flush();
    }

    /**
     * Adds the settings link to the plugin page.
     * 
     * This method adds the settings link to the plugin page.
     */
    public static function agcn_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=agcn-settings">' . __('Settings', 'agcn') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Loads the plugin text domain.
     * 
     * This method loads the plugin text domain.
     */
    public static function agcn_load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'agcn',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }
}
