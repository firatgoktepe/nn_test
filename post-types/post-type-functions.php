<?php

/**
 * Post Type functions 
 *
 * @package Nax_Custom
 */


/**
 * Post Types
 * 
 * Will appear in admin as ordered below
 */
require_once get_template_directory() . '/inc/toolbox-functions.php';

function nax_register_post_type($cpt_name, $cpt_singular, $cpt_plural, $cpt_domain, $cpt_dashicon)
{
    register_post_type($cpt_name, array(
        'labels' => array(
            'name' => __($cpt_plural, $cpt_domain),
            'singular_name' => __($cpt_singular, $cpt_domain),
            'all_items' => __('All ' . $cpt_plural, $cpt_domain),
            'archives' => __($cpt_singular . ' Archives', $cpt_domain),
            'attributes' => __($cpt_singular . ' Attributes', $cpt_domain),
            'insert_into_item' => __('Insert into ' . $cpt_singular, $cpt_domain),
            'uploaded_to_this_item' => __('Uploaded to this ' . $cpt_singular, $cpt_domain),
            'featured_image' => _x('Featured Image', $cpt_name, $cpt_domain),
            'set_featured_image' => _x('Set featured image', $cpt_name, $cpt_domain),
            'remove_featured_image' => _x('Remove featured image', $cpt_name, $cpt_domain),
            'use_featured_image' => _x('Use as featured image', $cpt_name, $cpt_domain),
            'filter_items_list' => __('Filter ' . $cpt_plural . ' list', $cpt_domain),
            'items_list_navigation' => __($cpt_plural . ' list navigation', $cpt_domain),
            'items_list' => __($cpt_plural . ' list', $cpt_domain),
            'new_item' => __('New ' . $cpt_singular, $cpt_domain),
            'add_new' => __('Add New', $cpt_domain),
            'add_new_item' => __('Add New ' . $cpt_singular, $cpt_domain),
            'edit_item' => __('Edit ' . $cpt_singular, $cpt_domain),
            'view_item' => __('View ' . $cpt_singular, $cpt_domain),
            'view_items' => __('View ' . $cpt_plural, $cpt_domain),
            'search_items' => __('Search ' . $cpt_plural, $cpt_domain),
            'not_found' => __('No ' . $cpt_plural . ' found', $cpt_domain),
            'not_found_in_trash' => __('No ' . $cpt_plural . ' found in trash', $cpt_domain),
            'parent_item_colon' => __('Parent ' . $cpt_singular . ':', $cpt_domain),
            'menu_name' => __($cpt_plural, $cpt_domain),
        ),
        'public' => false,
        'publicly_queryable' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'supports' => array('title', 'editor', 'custom-fields', 'excerpt', 'thumbnail'),
        'has_archive' => false,
        'rewrite' => true,
        'query_var' => true,
        'menu_position' => null,
        'menu_icon' => $cpt_dashicon,
        'show_in_rest' => true,
        'rest_base' => $cpt_name,
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ));
}

// My Post Types News and Cases

nax_register_post_type('News', 'news', 'News', 'nax-nordic', 'dashicons-admin-site-alt');
nax_register_post_type('Blog', 'blog', 'Blogs', 'nax-nordic', 'dashicons-admin-site-alt3');


// Add the custom columns to the custom post type:
function cpt_column_sort_link($custom_post_type, $name, $title)
{
    $order = isset($_GET['order']) ? $_GET['order'] : '';

    return '<a href="/wp-admin/edit.php?post_type='
        . $custom_post_type
        . '&orderby='
        . $name
        . '&order='
        . ($order == 'asc' ? 'desc' : 'asc')
        . '">'
        . $title . '</a>';
}

function set_custom_edit_cpt_columns(
    $columns,
    $custom_post_type,
    $custom_post_type_columns,
    $domain,
    $new_title_label = ''
) {
    $position = 2;
    foreach ($custom_post_type_columns as $column => $type) {
        $column_display_text = ucwords(str_replace('_', ' ', $column));
        $new = array($column => cpt_column_sort_link(
            $custom_post_type,
            $column,
            __($column_display_text, $domain)
        ));
        array_splice_assoc($columns, $position, null, $new);
        $position++;
    }
    //change title label
    if (!empty($new_title_label)) {
        $columns['title'] = $new_title_label;
    }

    return $columns;
}

// Add the data to the custom columns for the custom post type
function custom_cpt_column($custom_post_type_columns, $column, $post_id)
{
    $simple_value = function ($value, $field_type) {
        if ($value) {
            return $value;
        } else {
            return '-';
        }
    };
    $display_value = function ($value, $field_type) {
        if (!isset($value) || empty($value)) {
            return '-';
        }
        $html = '';
        switch ($field_type) {
            case "post":
                $html .= $value->post_title;
                break;
            case "taxonomy":
                $length = count($value);
                $counter = 1;
                foreach ($value as $term) {
                    $html .= $term->name;
                    if ($counter < $length) {
                        $html .= ', ';
                    }
                    $counter++;
                }
                break;
            case "image":
                $url = esc_url($value['sizes']['thumbnail']);
                $alt = $value['alt'];
                $html .= "<img src='{$url}' alt='{$alt}' style='height:4em;' />";
                break;
            case "url":
                $html .= "<a href='{$value}' target='_blank' rel='noopener' rel='noreferrer'>";
                $html .= "<span class='dashicons dashicons-admin-links'></span>";
                $html .= "</a>";
                break;
            default:
                return $value;
                break;
        }
        return $html;
    };

    if (isset($custom_post_type_columns[$column])) {
        if ($custom_post_type_columns[$column] == 'taxonomy') {
            # code...
        }
    }

    if (array_key_exists($column, $custom_post_type_columns)) {
        $field_type = $custom_post_type_columns[$column];
        $value = get_field($column, $post_id);
        $variable_type = gettype($value);
        switch ($variable_type) {
            case "integer":
            case "double":
            case "string":
            case "boolean":
            case "array":
                echo $display_value($value, $field_type);
                break;
            case "object":
                switch ($field_type) {
                    case "post":
                        echo $display_value($value, $field_type);
                        break;
                    case "taxonomy":
                        echo $display_value(array($value), $field_type);
                        break;
                    default:
                        echo '-';
                        break;
                }
                break;
            case "resource":
            case "resource (closed)":
            case "NULL":
            case "unknown type":
            default:
                echo '-';
                break;
        }
    }
}

function sort_cpt_column($custom_post_type_columns, $vars)
{
    if (array_key_exists('orderby', $vars)) {
        foreach ($custom_post_type_columns as $column => $type) {
            if ($column == $vars['orderby']) {
                $vars['orderby'] = 'meta_value';
                $vars['meta_key'] = $column;
            }
        }
    }
    return $vars;
}

/**
 * Sets the post updated messages for the custom post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the custom post type.
 */
function cpt_updated_messages($messages, $cpt_name, $cpt_singular, $cpt_domain)
{
    global $post;

    $permalink = get_permalink($post);

    $messages[$cpt_name] = array(
        0 => '', // Unused. Messages start at index 1.
        /* translators: %s: post permalink */
        1 => sprintf(__($cpt_singular
            . ' updated. <a target="_blank" href="%s" rel="noopener" rel="noreferrer">View '
            . $cpt_singular
            . '</a>', $cpt_domain), esc_url($permalink)),
        2 => __('Custom field updated.', $cpt_domain),
        3 => __('Custom field deleted.', $cpt_domain),
        4 => __($cpt_singular . ' updated.', $cpt_domain),
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf(__($cpt_singular . ' restored to revision from %s', $cpt_domain), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        /* translators: %s: post permalink */
        6 => sprintf(__($cpt_singular
            . ' published. <a href="%s">View '
            . $cpt_singular
            . '</a>', $cpt_domain), esc_url($permalink)),
        7 => __($cpt_singular . ' saved.', $cpt_domain),
        /* translators: %s: post permalink */
        8 => sprintf(__($cpt_singular
            . ' submitted. <a target="_blank" rel="noopener" rel="noreferrer" href="%s">Preview '
            . $cpt_singular
            . '</a>', $cpt_domain), esc_url(add_query_arg('preview', 'true', $permalink))),
        /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
        9 => sprintf(
            __($cpt_singular
                . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" rel="noopener" rel="noreferrer" href="%2$s">Preview '
                . $cpt_singular
                . '</a>', $cpt_domain),
            date_i18n(__('M j, Y @ G:i', $cpt_domain), strtotime($post->post_date)),
            esc_url($permalink)
        ),
        /* translators: %s: post permalink */
        10 => sprintf(__($cpt_singular
            . ' draft updated. <a target="_blank" rel="noopener" rel="noreferrer" href="%s">Preview '
            . $cpt_singular
            . '</a>', $cpt_domain), esc_url(add_query_arg('preview', 'true', $permalink))),
    );

    return $messages;
}

function taxonomy_labels($plural, $single)
{
    return array(
        'name' => $plural,
        'singular_name' => $single,
        'search_items' => 'Search ' . $plural,
        'popular_items' => 'Popular ' . $plural,
        'all_items' => 'All ' . $plural,
        'edit_item' => 'Edit ' . $single,
        'update_item' => 'Update ' . $single,
        'add_new_item' => 'Add New ' . $single,
        'new_item_name' => 'New ' . $single . ' Name',
        'separate_items_with_commas' => 'Separate ' . $plural . ' with commas',
        'add_or_remove_items' => 'Add or remove ' . $plural,
        'choose_from_most_used' => 'Choose from the most used ' . $plural,
        'not_found' => 'No ' . $plural . ' found.',
        'menu_name' => $plural,
    );
}

function nax_register_taxonomy($taxonomy)
{
    foreach ($taxonomy as $name => $data) {
        register_taxonomy(
            $name,
            $data['post_types'],
            array(
                'labels' => taxonomy_labels($data['plural'], $data['singular']),
                'hierarchical' => true,
                'public' => false,
                'show_ui' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'show_admin_column' => false,
                'show_in_quick_edit' => true,
                'meta_box_cb' => false,
                // 'show_in_menu' => true,
                // 'query_var' => true,
                // 'rewrite' => true,
                // 'capabilities' => array(
                //     'manage_terms' => 'edit_posts',
                //     'edit_terms' => 'edit_posts',
                //     'delete_terms' => 'edit_posts',
                //     'assign_terms' => 'edit_posts',
                // ),
                // 'show_in_rest' => true,
                // 'rest_base' => $name,
                // 'rest_controller_class' => 'WP_REST_Terms_Controller',
            )
        );

        if (0 === count(get_terms($name))) {
            foreach ($data['terms'] as $term) {
                wp_insert_term($term, $name);
            }
        }
    }
}


// My Custom Taxonomy called 'Turler' (seen as Genres inside Posts WP dashboard)

//nax_register_taxonomy( [ 'turler' => [ 'singular' => 'genre', 'plural' => 'Genres', 'post_types' => 'post', 'terms' => [] ] ] );

/*
Add ACF Fields to Quick Edit
https://sites.google.com/site/tessaleetutorials/home/add-acf-fields-to-quick-edit
*/
