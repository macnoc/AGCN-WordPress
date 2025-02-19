<?php

/**
 * Uninstall the AGCN plugin.
 * 
 * This file is used to uninstall the AGCN plugin.
 * It deletes the plugin options and styles, and removes the block attributes from all posts and pages.
 * 
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */


if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('agcn_options');
delete_option('agcn_styles');
delete_transient('agcn_admin_notice');

// Get all posts and pages that might have our block attributes
$posts = get_posts(array(
    'post_type'      => 'any',
    'posts_per_page' => -1,
    'post_status'    => 'any',
));

// Remove our block attributes from Gutenberg blocks
foreach ($posts as $post) {
    $content = $post->post_content;

    // Decode block content
    $blocks = parse_blocks($content);
    $updated = false;

    foreach ($blocks as &$block) {
        if (isset($block['attrs'])) {
            // Remove the attributes if they exist
            if (isset($block['attrs']['aiContent'])) {
                unset($block['attrs']['aiContent']);
                $updated = true;
            }
            if (isset($block['attrs']['agcnPosition'])) {
                unset($block['attrs']['agcnPosition']);
                $updated = true;
            }
        }
    }

    // If we updated any attributes, save the new content
    if ($updated) {
        $new_content = serialize_blocks($blocks);
        wp_update_post(array(
            'ID'           => $post->ID,
            'post_content' => $new_content
        ));
    }
}

// If you are also using template files, you might need to clear templates
// Check if you're targeting template content as well, based on custom logic for your use case

// Clear any transients or caches
wp_cache_flush();