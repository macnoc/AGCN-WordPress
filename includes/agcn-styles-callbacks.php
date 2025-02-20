<?php

/**
 * AGCN_styles_callbacks class.
 * 
 * This class handles the styles callbacks for the AGCN plugin.
 * 
 * @since 1.0.0
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */
class AGCN_styles_callbacks
{
    /**
     * Displays the color picker for the AGCN plugin.
     * 
     * This method retrieves the styles options and displays the color picker for the AGCN plugin.
     */
    public static function agcn_color_callback()
    {
        $options = get_option('agcn_styles');

        $colors_labels = array(
            'main-color' =>  __('Main Color', 'agcn'),
            'badge-color' =>  __('Badge Color', 'agcn'),
            'badge-bg-color' =>  __('Badge Background Color', 'agcn'),
            'badge-bg-color-hover' =>  __('Badge Background Color (Hover)', 'agcn'),
            'badge-border-color' =>  __('Badge Border Color', 'agcn'),

            'modal-color' =>  __('Modal Color', 'agcn'),
            'modal-bg-color' => __('Modal Background Color', 'agcn'),
            'modal-border-color' => __('Modal Border Color', 'agcn'),
            'modal-icon-color' => __('Modal Icon Color', 'agcn'),
            'modal-support-color' => __('Modal Support Color', 'agcn'),
            'modal-accordion-open-bg-color' => __('Modal Accordion Open Background Color', 'agcn'),

            'notice-color' => __('Notice Color', 'agcn'),
            'notice-bg-color' => __('Notice Background Color', 'agcn'),
            'notice-border-color' => __('Notice Border Color', 'agcn'),
        );

?>
        <?php foreach ($colors_labels as $key => $label) : ?>
            <div class="color-picker">
                <input type="color" name="agcn_styles[colors][<?php echo esc_attr($key) ?>]" value="<?php echo esc_attr($options['colors'][$key]) ?? '' ?>">
                <label for="main-color"><?php echo esc_html($label) ?></label>
            </div>
        <?php endforeach; ?>
        <style>
            .color-picker {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
            }
        </style>
    <?php
    }

    /**
     * Displays the badge offset for the AGCN plugin.
     * 
     * This method retrieves the styles options and displays the badge offset for the AGCN plugin.
     */
    public static function agcn_badge_offset_callback()
    {
        $options = get_option('agcn_styles');
        $badge_offset = $options['badge-offset'] ?? '0rem';
    ?>

        <input type="text" name="agcn_styles[badge-offset]" value="<?php echo esc_attr($badge_offset); ?>" class="regular-text">
        <p class="description"><?php esc_html_e('The space between the badge and the viewport edge.', 'agcn'); ?></p>
<?php
    }
}
