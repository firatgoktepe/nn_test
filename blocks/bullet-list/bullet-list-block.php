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

the_block_header('bullet-list-block');
while (have_rows('columns')) {
    the_row();
    $icon = get_sub_field('column_icon');
    $bullet_type = get_sub_field('bullet_type');
    $list_type = get_sub_field('list_type');
    $bullet_color_type = get_sub_field('bullet_color_type');
    $title = get_text_esc(get_sub_field('title'));

    $color = '';
    if ($bullet_color_type == 'column') {
        $color = get_sub_field('column_bullet_color');
    }

    echo "<article>";

    if ($title) {
        echo "<h3>{$title}</h3>";
    }
    echo "<ul>";
    while (have_rows('list')) {
        the_row();
        $text = get_wysiwyg(get_sub_field('text'));

        echo "<li>";
        if ($bullet_type == 'row') {
            $icon = get_sub_field('row_icon');
        }
        if ($bullet_color_type == 'row') {
            $color = get_sub_field('row_bullet_color');
        }
        $color_class = '';
        if ($bullet_color_type == 'row') {
            $color_class = "class='{$color}'";
        }

        if ($list_type == 'link') {
            $link = get_sub_field('link');
            $link_url = esc_url($link['url']);
            $link_title = esc_attr($link['title']);
            $link_target = esc_attr($link['target'] ? $link['target'] : '_self');
            echo "<a href='{$sdf}' target'{$sdf}' titlle='{$sdf}'>";
        }

        echo "<span class='{$color}'>{$icon}</span>";
        echo "<div class='wysiwyg'>{$text}</div>";

        if ($list_type == 'link') {
            echo "</a>";
        }
        echo "</li>";
    }
    echo "</ul>";

    echo "</article>";
}
the_block_footer();
