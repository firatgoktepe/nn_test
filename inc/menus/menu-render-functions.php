<?php

/**
 * Menu render functions
 *
 * @package Nax_Custom
 */

require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/toolbox-functions.php';

function create_menu($theme_location, $container_tag, $max_depth = 1, $selected_tag = '')
{
    $menu = create_menu_hierarchy($theme_location);
    $html = create_menu_html($menu, $max_depth, 'ul', 'li', $selected_tag);
    if (!$html) {
        return '';
    }

    echo "<{$container_tag} class='{$theme_location} unselectable'>{$html}</{$container_tag}>";
}

/**
 * Create HTML menu from array of MenuItem
 * @param array $menu_items An array of class MenuItem
 * @param int $max_depth An array of class MenuItem
 * @param string $list_tag Tag for list  (default = 'ul')
 * @param string $list_item_tag Tag for list item (default = 'li')
 * @param string $selected_tag Tag sor extra selected item added after link (default empty)
 * @param int $depth Dont use on initial call only use in recursive calls for measuring depth
 * @return string Returns string with HTML-list
 */
function create_menu_html($menu_items, $max_depth, $list_tag = 'ul', $list_item_tag = 'li', $selected_tag = '', $depth = 0)
{
    if (!$menu_items || $depth >= $max_depth) {
        return '';
    }

    $id = get_acf_post_id();
    $ancestor_ids = get_menu_item_ancestor_ids($menu_items, $menu_items, $id);

    $device = get_device_type();

    $html = "";
    // start list
    $html .= "<{$list_tag} class='menu {$device}'>";
    foreach ($menu_items as $menu_item) {

        $selected = $id == $menu_item->object_id;
        $selected_class = $selected ? "selected" : "";

        $has_children = '';
        if ($menu_item->children) {
            $has_children = 'has-children';
        }

        // start list item
        $html .= "<{$list_item_tag} class='menu-item {$selected_class} {$has_children} menu-depth-{$depth}'>";
        // link to item
        $html .= "<a class='link' href='{$menu_item->url}'>{$menu_item->title}</a>";
        // add selected tag
        $html .= $selected_tag;
        // submenu
        $html .= create_menu_html(
            $menu_item->children ?? null,
            $max_depth,
            $list_tag,
            $list_item_tag,
            $selected_tag,
            $depth + 1
        );

        // end list item
        $html .= "</{$list_item_tag}>";
    }

    // end list
    $html .= "</{$list_tag}>";
    return $html;
}

/**
 * Create expander HTML menu from array of MenuItem
 * @param array $menu_items An array of class MenuItem
 * @param int $max_depth An array of class MenuItem
 * @param int $depth Dont use on initial call only use in recursive calls for measuring depth
 * @return string Returns string with HTML-list
 *  *
 *    <section class='expander'>
 *        <input class='expander-checkbox' type='checkbox' id='expander-id' />
 *        <article class='expander-content'>
 *            --- content ---
 *        </article>
 *        <label class='expander-label' for='expander-id'><span class='open'></span><span class='close'></span></label>
 *    </section>
 */
function create_expander_menu_html($menu_items, $max_depth, $expand_to_current = false, $depth = 0)
{
    if (!$menu_items || $depth >= $max_depth) {
        return '';
    }

    $id = get_acf_post_id();
    $ancestor_ids = get_menu_item_ancestor_ids($menu_items, $menu_items, $id);
    $html = "";
    foreach ($menu_items as $menu_item) {
        $selected = $id == $menu_item->object_id;
        $selected_class = $selected ? "selected" : "";
        $has_selected_descendants = in_array($menu_item->object_id, $ancestor_ids)
        || in_array($menu_item->menu_id, $ancestor_ids);

        $expander_id = 'expander-rng' . rng_id() . '-' . $menu_item->menu_id;

        $has_children = $menu_item->children && $depth < ($max_depth - 1);

        // check if expander is open
        $checked = '';
        $has_selected_descendants_class = '';
        if ($expand_to_current && ($has_selected_descendants || $selected)) {
            $checked = 'checked=checked';
            $has_selected_descendants_class = 'selected';
        }
        // start list
        if ($depth == 0) {
            $html .= "<section class='menu expander {$has_selected_descendants_class}'>";
        } else {
            $html .= "<section class='menu expander'>";
        }
        $html .= "<input class='expander-checkbox' type='checkbox' id='{$expander_id}' {$checked} />";
        // start item header
        $has_children_class = '';
        if ($has_children) {
            $has_children_class = 'has-children';
        }
        $html .= "<header class='menu-item expander-label {$selected_class} {$has_children_class} menu-depth-{$depth}'>";
        // link to item
        if ($selected) {
            $html .= "<span>{$menu_item->title}</span>";
        } else {
            $html .= "<a class='link' href='{$menu_item->url}'>{$menu_item->title}</a>";
        }
        if ($has_children) {
            // start expander button
            $html .= "<label for='{$expander_id}'>";
            // expander symbols
            $html .= "<span class='open'></span><span class='close'></span>";
            // end expander button
            $html .= "</label>";
        }
        // end item header
        $html .= "</header>";
        if ($has_children) {
            // start list item
            $html .= "<article class='expander-content {$selected_class}'>";
            // children
            $html .= create_expander_menu_html($menu_item->children ?? null, $max_depth, $expand_to_current, $depth + 1);
            // end list item
            $html .= "</article>";
        }
        // end list
        $html .= "</section>";

    }
    return $html;
}

function the_breadcrumb()
{
    $id = get_acf_post_id();

    $breadcrumb = get_field('breadcrumb', $id);
    if (!$breadcrumb) {
        return;
    }

    $current_url = get_permalink(get_queried_object_id());
    $home_url = home_url('/');

    $rel_url = str_replace($home_url, '', $current_url);
    $rel_url = ltrim(rtrim($rel_url, '/'), '/');

    $crumbs = explode('/', $rel_url);
    $breadcrumb = '';
    for ($i = 0; $i < count($crumbs); $i++) {
        $path = '';
        for ($j = 0; $j <= $i; $j++) {
            $path .= '/' . $crumbs[$j];
        }
        $title = get_the_title(get_page_by_path($path));

        if ($i < count($crumbs) - 1) {
            $breadcrumb .= "<li class='menu-item'><a class='link' href='{$path}'>{$title}</a></li>";
        } else {
            $breadcrumb .= "<li class='menu-item'><span>{$title}</span></li>";
        }
    }

    echo "<nav class='breadcrumb'><ul class='menu'>{$breadcrumb}</ul></nav>";
}
