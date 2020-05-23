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

$posts_per_page = get_field('posts_per_page');
$blogposts = get_ajax_blogposts(false, $posts_per_page, true);

$count = wp_count_posts()->publish;

$blog = $blogposts['response'];
$next_data_url = $blogposts['next_data_url'];
$prev_data_url = $blogposts['prev_data_url'];

$categories = get_categories(array(
    'orderby' => 'name',
    'parent'  => 0
));
$blogcategory = isset($_GET['blogcategory']) ?$_GET['blogcategory'] : '';

/*
$pagination = "<nav id='blog-pagination'>";
$pagination .= "<a class='link' id='blog-first' style='display:none;'>&lt;&lt;</a>";
$pagination .= "<span id='blog-pages'></span>";
$pagination .= "<a class='link' id='blog-last' style='display:none;'>&gt;&gt;</a>";
$pagination .= "<select id='blog-category' onchange='location = this.value;''>";
$selected =  $blogcategory == '' ? " selected='selected'" : "";
$pagination .= "<option value='/blogg' {$selected}>";
$pagination .= "- Alla kategorier -</option>";
foreach ($categories as $category) {
    $value = " value='/blogg?blogcategory={$category->slug}'";
    $selected =  $blogcategory == $category->slug ? " selected='selected'" : "";
    $pagination .= "<option {$value}{$selected}>";
    $pagination .= "{$category->name}</option>";
}
$pagination .= "</select>";
$pagination .= "</nav>"; */

the_block_header('blog-list-block');
echo $blog;
//echo $pagination;
the_block_footer();

?>

