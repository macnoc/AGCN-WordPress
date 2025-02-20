<?php
/**
 * AGCN_admin class.
 * 
 * This class handles the administration functionality for the AGCN plugin.
 * It includes methods for adding plugin pages, initializing settings, displaying messages,
 * and enqueueing scripts.
 * 
 * @since 1.0.0
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */
class AGCN_admin
{
    /**
     * Constructor method.
     * 
     * This method hooks into WordPress actions to add a plugin page, initialize settings,
     * display messages, and enqueue scripts.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'agcn_add_plugin_page']);
        add_action('admin_init', [$this, 'agcn_page_init']);
        add_action('admin_enqueue_scripts', [$this, 'agcn_enqueue_admin_scripts']);
        add_action('admin_notices', [$this, 'agcn_display_message']);
    }

    /**
     * Displays a message to the administrator.
     * 
     * This method checks for a transient notice and displays it to the administrator.
     * The notice is then deleted.
     */
    public function agcn_display_message()
    {
        // Check for transient notice
        $notice = get_transient('agcn_admin_notice');
        if ($notice) {
            $class = 'notice notice-' . esc_attr($notice['type']) .
                ($notice['dismissible'] ? ' is-dismissible' : '');
            printf(
                '<div class="%1$s"><p>%2$s</p></div>',
                esc_attr($class),
                esc_html($notice['message'])
            );
            delete_transient('agcn_admin_notice');
        }
    }

    /**
     * Adds a plugin page to the WordPress admin menu.
     * 
     * This method adds a settings page for the AGCN plugin under the Settings menu.
     */
    public function agcn_add_plugin_page()
    {
        add_options_page(
            __('AGCN Settings', 'agcn-ai-generated-content-notifier'),
            __('AGCN', 'agcn-ai-generated-content-notifier'),
            'manage_options',
            'agcn-settings',
            [$this, 'agcn_create_admin_page']
        );
    }

    /**
     * Creates the content for the plugin page.
     * 
     * This method generates the HTML content for the plugin page, including tabs and forms.
     */
    public function agcn_create_admin_page()
    {
        $allowed_tabs = array(
            'config' => __('Configuration', 'agcn-ai-generated-content-notifier'),
            'content' => __('Content management', 'agcn-ai-generated-content-notifier'),
            'styles' => __('Styling', 'agcn-ai-generated-content-notifier')
        );
        $default_tab = 'config';

        $tab_from_url = filter_input(INPUT_GET, 'tab', FILTER_DEFAULT);
        $tab_from_url = $tab_from_url ? sanitize_text_field($tab_from_url) : $default_tab;
        $active_tab = array_key_exists($tab_from_url, $allowed_tabs) ? $tab_from_url : $default_tab;
?>
        <div class="wrap">
            <h1><?php esc_attr_e('AGCN Settings', 'agcn-ai-generated-content-notifier') ?></h1>
            <nav class="nav-tab-wrapper">
                <?php foreach ($allowed_tabs as $tab => $name) : ?>
                    <a href="<?php echo esc_url(admin_url('options-general.php?page=agcn-settings&tab=' . $tab)); ?>"
                        class="nav-tab <?php echo $active_tab == $tab ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_attr($name); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <?php

            if ($active_tab == 'config') {
            ?>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('agcn_options');
                    do_settings_sections('agcn_config');
                    submit_button('Save Configuration');
                    ?>
                </form>
            <?php
            } elseif ($active_tab == 'content') {
            ?>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('agcn_options');
                    do_settings_sections('agcn_content');
                    submit_button(__('Save Content', 'agcn-ai-generated-content-notifier'));
                    ?>
                </form>
            <?php
            } elseif ($active_tab == 'styles') {
            ?>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('agcn_styles');
                    do_settings_sections('agcn_styles');
                    submit_button(__('Save Style', 'agcn-ai-generated-content-notifier'));
                    ?>
                </form>
            <?php
            }
            ?>
        </div>
<?php
    }

    /**
     * Initializes settings for the plugin.
     * 
     * This method registers settings, sections, and fields for the plugin.
     */
    public function agcn_page_init()
    {
        // Register settings
        register_setting(
            'agcn_options', // option group
            'agcn_options', // option name
            function ($input) {
                return AGCN_sanitizers::agcn_sanitize_options($input);
            }
        );

        register_setting(
            'agcn_styles',
            'agcn_styles',
            function ($input) {
                return AGCN_sanitizers::agcn_sanitize_styles($input);
            }
        );

        // Config section
        add_settings_section(
            'agcn_config',
            __('Configuration Settings', 'agcn-ai-generated-content-notifier'),
            function () {
                echo '<p>' . esc_attr__('Configure the general settings for the AGCN widget.', 'agcn-ai-generated-content-notifier') . '</p>';
            },
            'agcn_config' // page 
        );

        // Content section
        add_settings_section(
            'agcn_content',
            __('Content Settings', 'agcn-ai-generated-content-notifier'),
            function () {
                echo '<p>' . esc_attr__('Configure the content for the AGCN widget.', 'agcn-ai-generated-content-notifier') . '</p>';
            },
            'agcn_content' // page
        );

        // Style section
        add_settings_section(
            'agcn_styles',
            __('Style Settings', 'agcn-ai-generated-content-notifier'),
            function () {
                echo '<p>' . esc_attr__('Configure the style for the AGCN widget.', 'agcn-ai-generated-content-notifier') . '</p>';
            },
            'agcn_styles' // page
        );

        // Config fields
        add_settings_field(
            'language',
            __('Default Language', 'agcn-ai-generated-content-notifier'),
            [AGCN_config_callbacks::class, 'agcn_language_callback'],
            'agcn_config',
            'agcn_config'
        );

        add_settings_field(
            'show_badge',
            __('Enable Badge', 'agcn-ai-generated-content-notifier'),
            [AGCN_config_callbacks::class, 'agcn_show_badge_callback'],
            'agcn_config',
            'agcn_config'
        );

        add_settings_field(
            'badge_position',
            __('Badge Position', 'agcn-ai-generated-content-notifier'),
            [AGCN_config_callbacks::class, 'agcn_badge_position_callback'],
            'agcn_config',
            'agcn_config'
        );

        add_settings_field(
            'support',
            __('Show Powered-By Footer', 'agcn-ai-generated-content-notifier'),
            [AGCN_config_callbacks::class, 'agcn_support_callback'],
            'agcn_config',
            'agcn_config'
        );

        // Content fields
        add_settings_field(
            'add_language',
            __('Add New Language', 'agcn-ai-generated-content-notifier'),
            [AGCN_content_callbacks::class, 'agcn_add_language_callback'],
            'agcn_content',
            'agcn_content'
        );

        add_settings_field(
            'select_language',
            __('Select Language', 'agcn-ai-generated-content-notifier'),
            [AGCN_content_callbacks::class, 'agcn_select_language_callback'],
            'agcn_content',
            'agcn_content'
        );
        add_settings_field(
            'header',
            __('Header Text', 'agcn-ai-generated-content-notifier'),
            [AGCN_content_callbacks::class, 'agcn_header_callback'],
            'agcn_content',
            'agcn_content'
        );

        add_settings_field(
            'title',
            __('Title Text', 'agcn-ai-generated-content-notifier'),
            [AGCN_content_callbacks::class, 'agcn_title_callback'],
            'agcn_content',
            'agcn_content'
        );

        add_settings_field(
            'body',
            __('Body Text', 'agcn-ai-generated-content-notifier'),
            [AGCN_content_callbacks::class, 'agcn_body_callback'],
            'agcn_content',
            'agcn_content'
        );

        add_settings_field(
            'sections_header',
            __('Sections Header', 'agcn-ai-generated-content-notifier'),
            [AGCN_content_callbacks::class, 'agcn_sections_header_callback'],
            'agcn_content',
            'agcn_content'
        );

        add_settings_field(
            'sections',
            __('Sections', 'agcn-ai-generated-content-notifier'),
            [AGCN_content_callbacks::class, 'agcn_sections_callback'],
            'agcn_content',
            'agcn_content'
        );

        // Style fields
        add_settings_field(
            'colors',
            __('Colors Settings', 'agcn-ai-generated-content-notifier'),
            [AGCN_styles_callbacks::class, 'agcn_color_callback'],
            'agcn_styles',
            'agcn_styles',
        );

        add_settings_field(
            'badge-offset',
            __('Badge Offset', 'agcn-ai-generated-content-notifier'),
            [AGCN_styles_callbacks::class, 'agcn_badge_offset_callback'],
            'agcn_styles',
            'agcn_styles',
        );
    }

    /**
     * Enqueues scripts for the plugin page.
     * 
     * This method enqueues a script for the plugin page.
     * 
     * @param string $hook The current admin page hook.
     */
    public function agcn_enqueue_admin_scripts($hook)
    {
        if ('settings_page_agcn-settings' !== $hook) {
            return;
        }

        wp_enqueue_script(
            'agcn-admin',
            AGCN_PLUGIN_URL . 'admin/js/admin.js',
            array(),
            AGCN_VERSION,
            true
        );
    }
}
