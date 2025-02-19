<?php

/**
 * AGCN_loader class.
 * 
 * This class handles the loading of the AGCN plugin.
 * It includes methods for enqueuing scripts, initializing widgets, adding block attributes, and registering block attributes.
 * 
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */
class AGCN_loader
{
    /**
     * Constructor method.
     * 
     * This method hooks into WordPress actions to enqueue scripts, initialize widgets, add block attributes, and register block attributes.
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_footer', [$this, 'init_widget']);
        add_action('wp_enqueue_scripts', [$this, 'init_styles'], 11);
        add_action('init', [$this, 'register_block_attributes']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
        add_filter('render_block', [$this, 'add_block_attributes'], 10, 2);
    }

    /**
     * Enqueues the scripts for the AGCN plugin.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'agcn-widget',
            AGCN_PLUGIN_URL . 'public/js/agcn.min.js',
            array(),
            AGCN_VERSION,
            true
        );

        wp_register_style('agcn-custom-vars', false, [], AGCN_VERSION);
        wp_enqueue_style('agcn-custom-vars');
    }

    /**
     * Initializes the styles for the AGCN plugin.
     */
    public function init_styles()
    {
        $styles = get_option('agcn_styles', array());

        if (!empty($styles)) {
            $css = '.agcn-variables {';
            // Colors
            foreach ($styles['colors'] as $key => $value) {
                $css .= '--' . esc_attr($key) . ': ' . esc_attr($value) . ' !important;';
            }

            // Rest of the styles
            $css .= '--badge-offset: ' . esc_attr($styles['badge-offset'] ?? '0rem') . ' !important;';
            $css .= '}';

            wp_add_inline_style('agcn-custom-vars', $css);
        };
    }

    /**
     * Initializes the widget for the AGCN plugin.
     */
    public function init_widget()
    {
        $options = get_option('agcn_options');

?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const widget = new AGCN({
                    config: {
                        language: <?php echo json_encode($options['config']['language'] ?? 'en'); ?>,
                        support: <?php echo json_encode($options['config']['support'] ?? false); ?>,
                        showBadge: <?php echo json_encode($options['config']['show_badge'] ?? false); ?>,
                        badgePosition: <?php echo json_encode($options['config']['badge_position'] ?? 'top-left'); ?>
                    },
                    content: {
                        <?php foreach ($options['content'] as $lang => $content) : ?>
                            <?php echo esc_attr($lang); ?>: {
                                <?php if (isset($content['header']) && !empty($content['header'])) echo 'header: ' . json_encode($content['header']) . ','; ?>
                                <?php if (isset($content['title']) && !empty($content['title'])) echo 'title: ' . json_encode($content['title']) . ','; ?>
                                <?php if (isset($content['body']) && !empty($content['body'])) echo 'body: ' . json_encode(wpautop($content['body'])) . ','; ?>
                                <?php if (isset($content['sections_header']) && !empty($content['sections_header'])) echo 'sectionsHeader: ' . json_encode($content['sections_header']) . ','; ?>
                                <?php if (isset($content['sections']) && !empty($content['sections'])) : ?>
                                    sections: [
                                        <?php foreach ($content['sections'] as $section) : ?>
                                            {
                                                <?php if (isset($section['slug']) && !empty($section['slug'])) echo 'slug: ' . json_encode($section['slug']) . ','; ?>
                                                <?php if (isset($section['notice_text']) && !empty($section['notice_text'])) echo 'noticeText: ' . json_encode($section['notice_text']) . ','; ?>
                                                <?php if (isset($section['title']) && !empty($section['title'])) echo 'title: ' . json_encode($section['title']) . ','; ?>
                                                <?php if (isset($section['body']) && !empty($section['body'])) echo 'body: ' . json_encode(wpautop($section['body'])) . ','; ?>
                                            },
                                            <?php endforeach; ?>
                                        ]
                                <?php endif; ?>
                            },
                        <?php endforeach; ?>
                    }
                });
            });
        </script>
<?php
    }

    /**
     * Registers the block attributes for the AGCN plugin.
     */
    public function register_block_attributes()
    {
        $registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

        foreach ($registered_blocks as $block_name => $block_type) {
            $block_type->attributes['aiContent'] = array(
                'type' => 'string',
                'default' => '',
            );
            $block_type->attributes['agcnPosition'] = array(
                'type' => 'string',
                'default' => '',
            );
        }
    }

    /**
     * Enqueues the editor assets for the AGCN plugin.
     */
    public function enqueue_editor_assets()
    {
        wp_enqueue_script(
            'agcn-editor',
            AGCN_PLUGIN_URL . 'admin/js/agcn-editor.js',
            array('wp-blocks', 'wp-components', 'wp-element', 'wp-i18n'),
            AGCN_VERSION,
            true
        );
    }

    /**
     * Adds the block attributes for the AGCN plugin.
     */
    public function add_block_attributes($block_content, $block)
    {
        $allowed_blocks = array(
            'core/paragraph', 
            'core/group', 
            'core/code', 
            'core/details', 
            'core/embed', 
            'core/footnotes', 
            'core/image', 
            'core/form',
            'core/html',
            'core/list',
            'core/post-content',
            'core/post-excerpt',
            'core/post-featured-image',
            'core/table',
            'core/verse',
            'core/video'
        );

        if (!in_array($block['blockName'], $allowed_blocks)) {
            return $block_content;
        }

        if (!empty($block['attrs']['aiContent']) || !empty($block['attrs']['agcnPosition'])) {
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($block_content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $wrapper = $dom->getElementsByTagName('*')->item(0);

            if ($wrapper) {
                if (!empty($block['attrs']['aiContent'])) {
                    $wrapper->setAttribute('data-ai-content', $block['attrs']['aiContent']);
                }
                if (!empty($block['attrs']['agcnPosition'])) {
                    $wrapper->setAttribute('data-agcn-position', $block['attrs']['agcnPosition']);
                }

                $block_content = $dom->saveHTML();
            }
        }

        return $block_content;
    }
}
