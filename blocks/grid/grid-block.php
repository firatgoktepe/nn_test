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

$items_per_row = (int) get_field('items_per_row');
$item_shape = get_field('item_shape');

if ($items_per_row > 0 && !wp_is_mobile()) {
    $grid_template_columns = "grid-template-columns: repeat({$items_per_row}, 1fr);";
} else {
    $grid_template_columns = "grid-template-columns: 1fr;";
}

the_block_header('grid-block', $grid_template_columns);

while (have_rows('items')) {
    the_row();
    $item_type = get_sub_field('item_type');
    $text_color =  get_sub_field('text_color');
    $background_color =  get_sub_field('background_color');
    $html = "<article class='{$text_color} {$background_color} {$item_shape}'>";

    switch ($item_type) {
        case 'text':
            $title = get_text_esc(get_sub_field('title'));
            $text = get_wysiwyg(get_sub_field('text'));
            if ($title) {
                $html .= "<h3>{$title}</h3>";
            }
            if ($text) {
                $html .= "<div>{$text}</div>";
            }
            break;
        case 'image':
            $style = '';
            $image = get_sub_field('image');
            if ($image) {
                $style .= "style='";
                $url = get_attachment_url($image['ID']);
                $style .= "background-image: url({$url});";
            }
            $image_size = get_sub_field('image_size');
            if ($image_size) {
                $style .= "background-size: {$image_size};";
            }
            $image_position = get_sub_field('image_position');
            if ($image_position) {
                $style .= "background-position: {$image_position};";
            }
            $style .= "'";
            $html .= "<figure class='{$item_shape}' {$style}>";
            $html .= "</figure>";

            break;
        case 'embedded':
            $emdedded_html = get_sub_field('emdedded_html');
            if ($emdedded_html) {
                $html .= $emdedded_html;
            }
            break;
    }
    $html .= "</article>";
    echo $html;
}

the_block_footer();
