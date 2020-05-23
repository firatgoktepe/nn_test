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


$image_text_position = get_field('image_text_position');
$text = get_wysiwyg(get_field('text'));

$media_type = get_field('media_type');

$style = '';
if ($media_type == 'image') {
    $image = get_field('image');
    $image_size = get_field('image_size');
    $image_position = get_field('image_position');
    $url = get_attachment_url($image['ID']);

    $style .= "background-image: url({$url});";
    $style .= "background-size: {$image_size};";
    $style .= "background-position: {$image_position};";
}

$html = "<figure style='{$style}'>";
if ($media_type == 'video') {
    $video = get_field('video');
    $video_size = get_field('video_size');
    $video_position = '';
    if ($video_size == 'fit-width') {
        $video_position = get_field('video_vertical_position');
    } else {
        $video_position = get_field('video_horizontal_position');
    }

    $classes = "{$video_position} {$video_size}";

    $html .= "<video playsinline autoplay muted loop class='{$classes}'>";
    $html .= "<source src='{$video}' type='video/mp4'>";
    $html .= "</video>";
}
$html .= "</figure>";

$html .= "<article>";
$html .= $text;
$html .= "</article>";

the_block_header('text-media-block ' . $image_text_position);
echo $html;
the_block_footer();
