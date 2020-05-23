<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Nax_Custom
 */

$title = get_the_title();
global $post;
$author_id = $post->post_author;
$author = get_the_author_meta('display_name', $author_id);
$date = date_format(get_post_datetime(), "Y-m-d H:i");;
$categories = get_the_category();
$category_list = get_cpt_taxonomy_field_names($categories);
$image_url = get_attachment_url(get_post_thumbnail_id());

// ob_start();
// the_content();
// $content = ob_get_clean();

global $post;
$post = get_field('blog_post_page', 'option');
//$post = get_post(2616);
setup_postdata($post);
ob_start();
the_content();
$template_content = ob_get_clean();
wp_reset_postdata();

$start_pos = strpos($template_content, '<h1>');
$length = strpos($template_content, '</h1>') + strlen('</h1>') - $start_pos;
$old_title = substr($template_content, $start_pos, $length);
$template_content = str_replace($old_title, '<h1>' . $title . '</h1>', $template_content);

$postinfo = "<span>{$category_list} - {$date}</span>";

ob_start();
the_content();
$content = ob_get_clean();

$html = '';
if ($image_url) {
    $html .= "<img src='{$image_url}' alt='{$title}' title='{$title}' />";
}
$html .= $content;


$template_content = str_replace('<!--blogpost-placeholder-subtitle-->', $author, $template_content);
$template_content = str_replace('<!--blogpost-placeholder-abstract-->', $postinfo, $template_content);
$template_content = str_replace('<!--blogpost-placeholder-main-->', $html, $template_content);

get_header();
?>
<main id="site-main" class="page-<?php the_ID(); ?> <?php the_device_type(); ?>">
    <?php echo $template_content; ?>
    
</main>
<?php
get_footer();
