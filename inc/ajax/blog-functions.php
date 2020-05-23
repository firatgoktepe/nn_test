<?php

/**
 * blog functions
 *
 * @package Nax_Custom
 */

function get_ajax_blogpost_html()
{
    global $post;
    $url = get_permalink();
    $title = get_the_title();
    //$author = get_author_name();
    //$date = date_format(get_post_datetime(), "Y-m-d H:i");;
    $categories = get_the_category();
    $category_list = get_cpt_taxonomy_field_names($categories);
    //$excerpt = get_the_excerpt();
    $image_url = get_attachment_url(get_post_thumbnail_id(), 'thumbnail');

    $html = "<a class='listitem' href='{$url}'>";
    if ($title) {
        $html .= "<h3>{$title}</h3>";
    }
    /*if ($author && $date) {
        $html .= "<span>{$category_list} - {$author} - {$date}</span>";
    }*/
    $has_image = "";
    if ($image_url) {
        $html .= "<figure style='background-image: url({$image_url});'></figure>";
        $has_image = " class='has-image'";
    }
    /*if ($excerpt) {
        $html .= "<p{$has_image}>{$excerpt}</p>";
    }*/

    $html .= "</a>";

    return $html;
}

function get_ajax_blogposts_data($paged, $posts_per_page, $category)
{
    $args = array(
        'post_type'         => 'blog',
        'post_status'       => array('publish'),
        'posts_per_page'    => $posts_per_page,
        'paged'             => $paged,
        'category_name'          => $category
    );
    $blogposts = new WP_Query($args);

    $page_url = get_ajax_blogposts_page_url($paged, $category);
    $html = "<fieldset id='blogpage{$paged}' page-url={$page_url} class='listitempage'>"; //Removed <legend>Sida {$paged}</legend> just before last "

    $data_page_url = get_ajax_blogposts_page_url($paged, $category);
    $html .= "<article data-page-url='{$data_page_url}' data-pagination=''>";
    while ($blogposts->have_posts()) {
        $blogposts->the_post();
        $html .= get_ajax_blogpost_html();
    };
    $html .= "</article>";
    $html .= "</fieldset>";

    return $html;
}

function count_posts_by_category($category)
{
    if (is_string($category) && !empty($category)) {
        $cat = get_category_by_slug($category);
        return $cat->count;
    } elseif (is_numeric($category)) {
        $cat = get_category($category);
        return $cat->count;
    } else {
        return wp_count_posts()->publish;
    }
}

function get_ajax_blogposts_data_url($posts_per_page, $paged, $category)
{
    $url = "/wp-admin/admin-ajax.php?action=blogposts";
    $url .= "&posts_per_page={$posts_per_page}&paged={$paged}";
    if (!empty($category)) {
        $url .= "&category={$category}";
    }
    return  $url;
}
function get_ajax_blogposts_page_url($paged, $category)
{
    $page_url = "blogg?blogpage={$paged}";
    if (!empty($category)) {
        $page_url .= "&blogcategory={$category}";
    }
    return  "/{$page_url}";
}

function get_ajax_blogposts($run_as_ajax = true, $posts_per_page = 10, $get_post_html = true)
{
    $posts_per_page = (isset($_GET['posts_per_page']) ?
        $_GET['posts_per_page'] :
        $posts_per_page);
    $paged = (isset($_GET['paged']) ?
        $_GET['paged'] : (isset($_GET['blogpage']) ?
            $_GET['blogpage'] :
            1));
    $category = (isset($_GET['category']) ?
        $_GET['category'] : (isset($_GET['blogcategory']) ?
            $_GET['blogcategory'] :
            null));

    $count = count_posts_by_category($category);
    $paged = $paged > $count ? $count : $paged;
    $next = $paged + 1;
    $prev = $paged - 1;

    $last_page = ceil((int) $count / (int) $posts_per_page);
    if ($next > $last_page) {
        $next = $last_page;
    }
    if ($prev < 1) {
        $prev = 1;
    }


    $current_url = get_ajax_blogposts_page_url($paged, $category);
    $next_url = get_ajax_blogposts_page_url($next, $category);
    $prev_url = get_ajax_blogposts_page_url($prev, $category);
    $next_url = $next_url == $current_url ? '' : $next_url;
    $prev_url = $prev_url == $current_url ? '' : $prev_url;

    $next_data_url = $next_url == '' ?
        '' :
        get_ajax_blogposts_data_url($posts_per_page, $next, $category);
    $prev_data_url = $prev_url == '' ?
        '' :
        get_ajax_blogposts_data_url($posts_per_page, $prev, $category);

    $html = '';
    if ($get_post_html) {
        $html = get_ajax_blogposts_data($paged, $posts_per_page, $category);
    }

    $categories = get_categories(array(
        'orderby' => 'name',
        'parent'  => 0
    ));
    $category_list = array();
    foreach ($categories as $category) {
        $category_list[$category->slug] = $category->name;
    }

    $json_array = array(
        'page_count'    => $last_page,
        'current_url'   => $current_url,
        'next_url'      => $next_url,
        'prev_url'      => $prev_url,
        'next_data_url' => $next_data_url,
        'prev_data_url' => $prev_data_url,
        'response'      => $html,
        'categories'    => $category_list
    );

    if ($run_as_ajax == true) {
        return json_encode($json_array, JSON_UNESCAPED_SLASHES);
    } else {
        return $json_array;
    }
}
function ajax_blogposts()
{
    echo get_ajax_blogposts();

    die;
}
add_action('wp_ajax_blogposts', 'ajax_blogposts');
add_action('wp_ajax_nopriv_blogposts', 'ajax_blogposts');
