<?php

/**
 * Register blocks
 *
 * @package Nax_Custom
 *
 *
 *
 */

// timymce
require_once get_template_directory() . '/blocks/tinymce-functions.php';

// blocks
$blocks_to_register = array(
    'hero',
    'icon-text-list',
    'content-background-icon',
    'image',
    'icon-bullet-list',
    'bullet-list',
    'text-media',
    'contact-form',
    'newsletter',
    'grid',
    'text-list',
    'expander-list',
    'blog-list',
    'blog-post',
    'text',
);

foreach ($blocks_to_register as $block) {
    require get_template_directory() . '/blocks/' . $block . '/' . $block . '-block-register.php';
}

/**
 * ACF Blocks
 * https://www.advancedcustomfields.com/resources/acf-input-admin_enqueue_scripts/
 */
function nax_acf_admin_enqueue_scripts()
{
    //app\public\wp-content\themes\nax\assets\css\admin-style.min.css
    $admin_min_css_path = get_template_directory() . '/dist/admin-style.css';
    $admin_min_css_url = get_stylesheet_directory_uri() . '/dist/admin-style.css';
    if (file_exists($admin_min_css_path)) {
        // add style to admin editor
        wp_register_style('admin-style', $admin_min_css_url);
        wp_enqueue_style('admin-style');
        // add line awesome style to admin editor
        wp_register_script('font-awesome-script', 'https://kit.fontawesome.com/f76a1e3b45.js', false);
        wp_enqueue_script('font-awesome-script');
    }
}
add_action('acf/input/admin_enqueue_scripts', 'nax_acf_admin_enqueue_scripts');
