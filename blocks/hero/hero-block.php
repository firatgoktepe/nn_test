<?php

/**
 * ACF Block
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 * 
 * @package Nax_Custom
 * 
 */

require_once get_template_directory() . '/blocks/blocks-functions.php';
if (is_admin()) {
    require_once get_template_directory() . '/inc/menus/menu-functions.php';
    require_once get_template_directory() . '/inc/site/logo-functions.php';
    require_once get_template_directory() . '/inc/site/background-functions.php';
    require_once get_template_directory() . '/header.php';
    the_theme_header();
}

$decrease_bottom_margin = get_field('decrease_bottom_margin');
the_block_header('hero-block ' . $decrease_bottom_margin, '', true);
the_block_footer();
