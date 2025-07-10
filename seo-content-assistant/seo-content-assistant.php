<?php
/*
Plugin Name: SEO Content Assistant | دستیار سئوی محتوا
Description: Lightweight WordPress plugin for basic SEO checklist and AI-powered content generation using OpenAI, OpenRouter, Claude, Bard, and Yunwu.
Version: 1.0
Author: Reza Yarmohammadi
Author URI: https://yourdomain.com
Plugin URI: https://yourdomain.com/plugins/seo-content-assistant
*/

if (!defined('ABSPATH')) exit;

// Include plugin modules
$includes = [
    'init.php',
    'settings-page.php',
    'meta-box.php',
    'meta-box-product.php',
    'seo-checker.php',
    'ai-generator.php',
    'chat-logger.php'
];

foreach ($includes as $file) {
    $path = plugin_dir_path(__FILE__) . 'includes/' . $file;
    if (file_exists($path)) {
        include_once $path;
    } else {
        error_log("[SEO Content Assistant] Missing include file: $file");
    }
}

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('sca-admin', plugin_dir_url(__FILE__) . 'assets/css/admin.css');
    wp_enqueue_script('sca-admin', plugin_dir_url(__FILE__) . 'assets/js/admin.js', ['jquery'], null, true);
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $links[] = '<a href="admin.php?page=seo_content_assistant_settings">Settings</a>';
    return $links;
});

add_filter('plugin_row_meta', function($links, $file) {
    if ($file === plugin_basename(__FILE__)) {
        $links[] = '<a href="https://yourdomain.com/docs" target="_blank">📖 Documentation</a>';
        $links[] = '<a href="https://yourdomain.com/faq" target="_blank">❓ FAQ</a>';
        $links[] = '<a href="https://yourdomain.com/tutorial" target="_blank">🎥 Video Tutorial</a>';
    }
    return $links;
}, 10, 2);

add_action('admin_menu', function () {
    add_menu_page(
        'SEO Content Assistant Settings',
        'SEO Assistant',
        'manage_options',
        'seo_content_assistant_settings',
        'sca_render_settings_page',
        'dashicons-analytics',
        56
    );
});
