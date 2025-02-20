<?php

/**
 * AGCN_config_callbacks class.
 * 
 * This class handles the configuration callbacks for the AGCN plugin.
 * It includes methods for displaying language selection, badge position, and support options.
 * 
 * @since 1.0.0
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */
class AGCN_config_callbacks
{
    /**
     * Displays the language selection dropdown.
     * 
     * This method retrieves the available languages and the current language from the plugin options.
     * It then generates a dropdown for selecting the language.
     */
    public static function agcn_language_callback()
    {
        $available_languages = AGCN_plugin::agcn_get_config('available_languages');
        $options = get_option('agcn_options');

?>
        <select name="agcn_options[config][language]">
            <?php foreach ($options['content'] as $key => $value) : ?>
                <?php $selected_language_name = $available_languages[$key] ?? $key; ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($options['config']['language'], $key); ?>><?php echo esc_attr($selected_language_name) ?></option>
            <?php endforeach; ?>
        </select>
    <?php
    }

    /**
     * Displays the badge position selection dropdown.
     * 
     * This method retrieves the badge position from the plugin options and generates a dropdown for selecting the position.
     */
    public static function agcn_show_badge_callback()
    {
        $options = get_option('agcn_options');
        $show_badge = $options['config']['show_badge'] ?? false;
    ?>

        <input type="checkbox" name="agcn_options[config][show_badge]" <?php checked($show_badge); ?>>
        <label for="agcn_show_badge"><?php esc_attr_e('Show the AGCN badge on the front end.', 'agcn'); ?></label>
    <?php
    }

    /**
     * Displays the badge position selection dropdown.
     * 
     * This method retrieves the badge position from the plugin options and generates a dropdown for selecting the position.
     */
    public static function agcn_badge_position_callback()
    {
        $options = get_option('agcn_options');
        $badge_position = $options['config']['badge_position'] ?? 'top-left';
    ?>

        <select name="agcn_options[config][badge_position]">
            <option value="top-left" <?php selected(esc_attr($badge_position), 'top-left'); ?>><?php esc_attr_e('Top Left', 'agcn'); ?></option>
            <option value="top-right" <?php selected(esc_attr($badge_position), 'top-right'); ?>><?php esc_attr_e('Top Right', 'agcn'); ?></option>
            <option value="bottom-left" <?php selected(esc_attr($badge_position), 'bottom-left'); ?>><?php esc_attr_e('Bottom Left', 'agcn'); ?></option>
            <option value="bottom-right" <?php selected(esc_attr($badge_position), 'bottom-right'); ?>><?php esc_attr_e('Bottom Right', 'agcn'); ?></option>
        </select>
        <p class="description"><?php esc_attr_e('Select the position where the badge will be displayed on the screen.', 'agcn'); ?></p>
    <?php
    }

    /**
     * Displays the support checkbox.
     * 
     * This method retrieves the support option from the plugin options and generates a checkbox for displaying the support note.
     */
    public static function agcn_support_callback()
    {
        $options = get_option('agcn_options');
        $support = $options['config']['support'] ?? false;
    ?>

        <input type="checkbox" name="agcn_options[config][support]" <?php checked($support); ?>>
        <label for="agcn_support"><?php esc_attr_e('Support AGCN by displaying a "Powered by" footer note with a link.', 'agcn'); ?></label>
<?php
    }
}
