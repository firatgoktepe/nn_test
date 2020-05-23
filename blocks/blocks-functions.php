<?php

/**
 * ACF Block - common functions
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 *
 * @package Nax_Custom
 *
 */

require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/toolbox-functions.php';

// CSS CLASS
function the_block_classes($background_settings, $classes = '')
{
    $classes .= ' block ' . get_device_type();
    if ($background_settings) {
        $background_type = get_background_type($background_settings);
        $classes .= ' background-' . $background_type;
        if ($background_type == 'image' || $background_type == 'video') {
            $overlay = $background_settings['overlay'];
            $classes .= ' overlay-' . $overlay;
        }
    }
    if (get_field('block_title')) {
        $classes .= ' has-title';
    }
    if (get_field('block_subtitle')) {
        $classes .= ' has-subtitle';
    }
    switch (get_field('block_width')) {
        case 'max':
            $classes .= ' width-max';
            break;
        case 'medium':
            $classes .= ' width-medium';
            break;
        case 'min':
            $classes .= ' width-min';
            break;
        default:
            $classes .= ' width-max';
            break;
    }
    if (get_field('change_text_color')) {
        $classes .= ' ' . get_field('text_color');
    }
    if (get_field('abstract')) {
        $classes .= ' has-abstract';
    }
    if (get_field('show_bottom_link') != 'none') {
        $classes .= ' has-bottom-link';
    }

    echo trim($classes);
}

// TITLE
function the_block_title($title_tag = 'h2')
{
    if (!get_field('show_block_title')) {
        return;
    }

    $block_title = get_field('block_title');
    if ($block_title) {
        echo '<' . $title_tag . '>' . get_textarea_esc($block_title) . '</' . $title_tag . '>';
    }
}
function the_block_subtitle($subtitle_class = 'subtitle2')
{
    if (!get_field('show_block_title')) {
        return;
    }

    $block_subtitle = get_field('block_subtitle');
    if ($block_subtitle) {
        echo '<div class="' . $subtitle_class . '">' . get_wysiwyg($block_subtitle) . '</div>';
    }
}

// ABSTRACT
function the_block_abstract()
{
    if (!get_field('show_abstract')) {
        return;
    }

    $abstract = get_field('abstract');
    if ($abstract) {
        echo '<article class="abstract">' . get_wysiwyg($abstract) . '</article>';
    }
}

// BOTTOM LINK
function the_block_bottom_link()
{
    $show_bottom_link = get_field('show_bottom_link');
    if (!isset($show_bottom_link) || $show_bottom_link == 'none') {
        return;
    }

    $link_title = get_text_esc(get_field('link_title'));
    $internal_link = get_field('internal_link');
    $external_link = get_field('external_link');
    $url = '';
    $link_target = '';

    if ($show_bottom_link == 'internal' && isset($internal_link->ID)) {
        $url = get_permalink($internal_link->ID);
    } elseif ($external_link) {
        $url = $external_link;
        $link_target = ' target="_blank" rel="noopener" rel="noreferrer"';
    }
    if ($link_title && $url) {
        echo "<a class='button-style' href='{$url}' {$link_target}>{$link_title}</a>";
    }
}

// BACKGROUND COLOR & IMAGE/VIDEO
function get_block_background_settings($post_id = false)
{
    $use_separate_mobile_background = get_field('use_separate_mobile_background', $post_id);
    $backround_field = wp_is_mobile() ?
        ($use_separate_mobile_background ? 'mobile_background' : 'desktop_background') :
        'desktop_background';
    return get_field($backround_field, $post_id);
}
function get_background_type($background_settings)
{
    if (isset($background_settings['background_type'])) {
        return $background_settings['background_type'];
    } else {
        return 'none';
    }
}
function the_block_background($background_settings)
{
    $background_type = get_background_type($background_settings);

    if ($background_type == 'none') {
        return;
    }

    $get_background_style = function ($background_settings, $background_type) {
        if ($background_settings) {

            if ($background_type == 'image') {
                // begin style
                $style = "style='";
                // url
                $image_id = $background_settings['image'];
                if ($image_id) {
                    $url = get_attachment_url($image_id);
                    $style .= "background-image: url({$url});";
                }

                $image_size = $background_settings['image_size'];
                if ($image_size) {
                    $style .= "background-size: {$image_size};";
                }

                $image_position = $background_settings['image_position'];
                if ($image_position) {
                    $style .= "background-position: {$image_position};";
                }

                // end style
                $style .= "'";

                return $style;
            }
        }
    };

    $get_overlay_style = function ($background_settings, $background_type) {
        if ($background_settings) {
            $overlay = $background_settings['overlay'];
            $style = '';

            if ($overlay == 'gradient' || $background_type == 'gradient') {
                $primary_color_settings = $background_settings['primary_color_settings'];
                $primary_color = $primary_color_settings['color'];
                $primary_transparency = $primary_color_settings['transparency'];
                $secondary_color_settings = $background_settings['secondary_color_settings'];
                $secondary_color = $secondary_color_settings['color'];
                $secondary_transparency = $secondary_color_settings['transparency'];

                // begin gradient style
                $style = "style='background-image: linear-gradient(";
                $gradient_direction = 180 + (int) $background_settings['gradient_direction'];
                $style .= "{$gradient_direction}deg,";

                $primary_color = str_replace(", 1)", ", {$primary_transparency})", $primary_color);
                $style .= "{$primary_color},";

                $secondary_color = str_replace(", 1)", ", {$secondary_transparency})", $secondary_color);
                $style .= "{$secondary_color}";

                // end style
                $style .= ");'";
            }

            return $style;
        }
    };

    $class = get_device_type();
    $overlay_class = '';
    if ($background_settings['overlay'] == 'single' || $background_type == 'color') {
        $background_color = $background_settings['background_color'];
        if (is_array($background_color)) {
            $background_color = '';
        }
        $overlay_class .= "class='{$background_color}'";
    }
    $background_style = $get_background_style($background_settings, $background_type);
    $overlay_style = $get_overlay_style($background_settings, $background_type);

    $html = "<figure class='block-background {$class}' {$background_style}>";
    if (get_background_type($background_settings) == 'video' && isset($background_settings['video'])) {
        // url
        $video = $background_settings['video'];
        // html

        $video_size = $background_settings['video_size'];
        $video_position = '';
        if ($video_size == 'fit-width') {
            $video_position = $background_settings['video_vertical_position'];
        } else {
            $video_position = $background_settings['video_horizontal_position'];
        }

        $classes = "{$video_position} {$video_size}";

        $html .= "<video playsinline autoplay muted loop class='{$classes}'>";
        $html .= "<source src='{$video}' type='video/mp4'>";
        $html .= "</video>";
    }
    $html .= "<div {$overlay_class} {$overlay_style}></div>";
    $html .= "</figure>";

    echo $html;
}


// BLOCK HEADER
function the_block_header($block_css_classes, $block_main_css_style = '', $isHero = false)
{
    $background_settings = get_block_background_settings();
?>
    <section class='<?php the_block_classes($background_settings, $block_css_classes); ?>'>
        <?php the_block_background($background_settings); ?>
        <header><?php
                the_block_title($isHero ? 'h1' : 'h2');
                the_block_subtitle($isHero ? 'subtitle1' : 'subtitle2');
                the_block_abstract();
                ?></header>
        <main class='<?php the_block_classes($background_settings); ?>' <?php echo empty($block_main_css_style) ? '' : "style='{$block_main_css_style}'"; ?>>
        <?php
    }

    // BLOCK FOOTER
    function the_block_footer()
    {
        $has_content = get_field('show_bottom_link') == 'none' ? '' : ' class="has-content"';
        ?>
        </main>
        <footer<?php echo $has_content; ?>><?php the_block_bottom_link(); ?></footer>
    </section>
<?php
    }
