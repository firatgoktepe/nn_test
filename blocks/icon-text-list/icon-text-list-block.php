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

$prevous_sibling = function ($has_prevous_sibling) {
    return $has_prevous_sibling ? 'has-previous-sibling' : '';
};

$orientation = get_field('orientation');
$title_icon_order = get_field('title_icon_order');
$item_border_color = get_field('item_border_color');
$item_background_color = get_field('item_background_color');
$item_icon_color = get_field('item_icon_color');
$item_title_color = get_field('item_title_color');
$item_text_color = get_field('item_text_color');

$grid_template_columns = '';
if ($orientation == 'horizontal') {
    $items_per_row = (int) get_field('items_per_row');
    if ($items_per_row > 0 && !wp_is_mobile()) {
        $grid_template_columns = "grid-template-columns: repeat({$items_per_row}, 1fr);";
    } else {
        $grid_template_columns = "grid-template-columns: 1fr;";
    }
}

$article_margin = '';
if ($item_border_color != 'no-border' || $item_background_color != 'transparent') {
    $article_margin = ' has-border-or-background';
}

the_block_header('icon-text-list-block ' . $orientation . $article_margin, $grid_template_columns);

while (have_rows('icons')) {
    the_row();
    $title = get_text_esc(get_sub_field('title'));
    $icon = get_sub_field('icon');
    $text = get_wysiwyg(get_sub_field('text'));
    $link_type = get_sub_field('link_type');
    $link = get_sub_field('link');

    if ($link) {
        $link_url = esc_url($link['url']);
        $link_title = esc_attr($link['title']);
        $link_target = esc_attr($link['target'] ? $link['target'] : '_self');
    }
    $link_icon = get_sub_field('link_icon');

    if ($orientation == 'horizontal') {
        echo "<article class='{$item_border_color} {$item_background_color} {$item_title_color}'>";
    }

    $render_title = function ($title, $prevous_sibling_class, $orientation, $item_title_color) {
        if ($title) {
            echo "<h3 class='{$prevous_sibling_class} {$item_title_color}'>{$title}</h3>";
        } elseif ($orientation == 'vertical') {
            // empty tag so grid layout dont break
            echo "<h3></h3>";
        }
    };
    $render_icon = function ($icon, $prevous_sibling_class, $orientation, $item_icon_color) {
        if ($icon) {
            echo "<span class='{$prevous_sibling_class} {$item_icon_color}''>{$icon}</span>";
        } elseif ($orientation == 'vertical') {
            // empty tag so grid layout dont break
            echo "<span></span>";
        }
    };
    $has_prevous_sibling = false;
    if ($title_icon_order == 'title_icon') {
        $render_title($title, $prevous_sibling(false), $orientation, $item_title_color);
        $render_icon($icon, $prevous_sibling($title), $orientation, $item_icon_color);
    } else {
        $render_icon($icon, $prevous_sibling(false), $orientation, $item_icon_color);
        $render_title($title, $prevous_sibling($icon), $orientation, $item_title_color);
    }
    if ($text) {
        echo "<div class='wysiwyg {$prevous_sibling($title ||$icon)} {$item_text_color}'>{$text}</div>";
    } elseif ($orientation == 'vertical') {
        // empty tag so grid layout dont break
        echo "<div></div>";
    }
    if ($link_type != 'none' && $link) {
        echo "<a class='{$prevous_sibling($title ||$icon ||$text)} {$link_type}' href='{$link_url}'>";
        if (($link_type == 'text' || $link_type == 'button-style') && $link_title) {
            echo "<div>{$link_title}</div>";
        }
        if ($link_type == 'icon' && $link_icon) {
            echo $link_icon;
        }
        echo "</a>";
    } elseif ($orientation == 'vertical') {
        // empty tag so grid layout dont break
        echo "<a></a>";
    }

    if ($orientation == 'horizontal') {
        echo "</article>";
    }
}

the_block_footer();
