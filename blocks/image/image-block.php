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

the_block_header('image-block');

$caption = get_wysiwyg(get_field('caption'));
$figure = get_field('figure');
$url = get_attachment_url($figure['ID']);
$alt = esc_attr($figure['alt']);

if ($figure) {
    echo "<figure>";
    echo "<img src='{$url}' alt='{$alt}' />";
    if ($caption) {
        echo "<figcaption class='wysiwyg'>{$caption}</figcaption>";
    }
    echo "</figure>";
}

the_block_footer();
