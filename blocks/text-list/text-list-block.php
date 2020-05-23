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

the_block_header('text-list-block');

while (have_rows('list')) {
    the_row();
    $html = "<article>";

    $title = get_text_esc(get_sub_field('title'));
    $text = get_wysiwyg(get_sub_field('text'));
    if ($title) {
        $html .= "<h3>{$title}</h3>";
    }
    if ($text) {
        $html .= "<div>{$text}</div>";
    }

    $html .= "</article>";
    echo $html;
}

the_block_footer();
