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
the_block_header('content-background-icon-block');

$text = get_wysiwyg(get_field('text'));
$icon = get_field('icon');
$icon_size = (int) get_field('icon_size');
$left = $icon_size * -0.5;
$style = "font-size:{$icon_size}em;left:{$left}%";

echo "<article>";
if ($icon) {
    echo "<div class='icon' style='{$style}'>{$icon}</div>";
}
if ($text) {
    echo "<div class='wysiwyg'>{$text}</div>";
}
echo "</article>";

the_block_footer();
