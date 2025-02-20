<?php

/**
 * AGCN_sanitizers class.
 * 
 * This class handles the sanitization of the AGCN plugin.
 * 
 * @since 1.0.0
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */
class AGCN_sanitizers

{
    /**
     * Sanitizes the options for the AGCN plugin.
     * 
     * @param array $input The input to sanitize.
     * @return array The sanitized options.
     */
    public static function agcn_sanitize_options($input)
    {
        $current_options = get_option('agcn_options');
        $sanitized = array();

        if (!is_array($input)) {
            return $current_options;
        }

        if (isset($input['remove_language'])) {
            $lang = sanitize_text_field($input['remove_language']);

            unset($current_options['content'][$lang]);

            if ($current_options['config']['language'] === $lang) {
                $first_language = array_key_first($current_options['content']);
                $current_options['config']['language'] = $first_language;
            }

            return $current_options;
        }

        $sanitized['config'] = isset($input['config']) ? [
            'language' => sanitize_text_field($input['config']['language'] ?? $current_options['config']['language']),
            'support' => isset($input['config']['support']),
            'show_badge' => isset($input['config']['show_badge']),
            'badge_position' => sanitize_text_field($input['config']['badge_position'] ?? $current_options['config']['badge_position']),
        ] : $current_options['config'];

        $sanitized['content'] = $current_options['content'];

        if (isset($input['content']) && is_array($input['content'])) {

            foreach ($input['content'] as $lang => $content) {
                $allowed_tags = array(
                    'a'      => array('href' => array(), 'title' => array(), 'target' => array()),
                    'strong' => array(),
                    'em'     => array(),
                    'u'      => array(),
                    'p'      => array(),
                    'br'     => array(),
                    'ul'     => array(),
                    'ol'     => array(),
                    'li'     => array(),
                );

                $sanitized['content'][$lang] = array(
                    'header' => sanitize_text_field($content['header'] ?? $current_options['content'][$lang]['header'] ?? ''),
                    'title' => sanitize_text_field($content['title'] ?? $current_options['content'][$lang]['title'] ?? ''),
                    'body' => wp_kses($content['body'] ?? $current_options['content'][$lang]['body'] ?? '', $allowed_tags),
                    'sections_header' => sanitize_text_field($content['sections_header'] ?? $current_options['content'][$lang]['sections_header'] ?? ''),
                    'sections' => array(),
                );

                if (isset($input['content'][$lang]['sections']) && is_array($input['content'][$lang]['sections'])) {
                    $slugs = $input['content'][$lang]['sections']['slug'] ?? array_column($current_options['content'][$lang]['sections'], 'slug') ?? [];
                    $notice_texts = $input['content'][$lang]['sections']['notice_text'] ?? array_column($current_options['content'][$lang]['sections'], 'notice_text') ?? [];
                    $titles = $input['content'][$lang]['sections']['title'] ?? array_column($current_options['content'][$lang]['sections'], 'title') ?? [];
                    $bodies = $input['content'][$lang]['sections']['body'] ?? array_column($current_options['content'][$lang]['sections'], 'body') ?? [];

                    $sections = array();
                    foreach ($titles as $index => $title) {
                        if (isset($bodies[$index]) && !empty($title)) {
                            $slug = sanitize_text_field($slugs[$index]);

                            if (!preg_match('/^[a-z]+$/', $slug)) {
                                $slug = preg_replace('/[^a-z]/', '', strtolower($slug));
                            }

                            $sections[] = array(
                                'slug' => $slug,
                                'notice_text' => sanitize_text_field($notice_texts[$index]),
                                'title' => sanitize_text_field($title),
                                'body' => wp_kses($bodies[$index], $allowed_tags)
                            );
                        }
                    }

                    $sanitized['content'][$lang]['sections'] = $sections;
                }
            }
        }



        return $sanitized;
    }

    /**
     * Sanitizes the styles for the AGCN plugin.
     * 
     * @param array $input The input to sanitize.
     * @return array The sanitized styles.
     */
    public static function agcn_sanitize_styles($input)
    {
        $current_options = get_option('agcn_styles');
        $sanitized = array();

        if (isset($input['colors']) && is_array($input['colors'])){
            foreach ($input['colors'] as $key => $value) {
                $sanitized['colors'][$key] = sanitize_hex_color($value) ?? $current_options['colors'][$key];
            }
        }

        // Handle badge offset
        $sanitized['badge-offset'] = sanitize_text_field($input['badge-offset']) ?? $current_options['badge-offset'];

        return $sanitized;
    }
}
