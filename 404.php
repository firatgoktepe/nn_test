<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Nax_Custom
 */

global $post;
$post = get_field('page_not_found', 'option');
setup_postdata($post);

get_header();
?>
<main id="site-main" class="page-<?php the_ID(); ?> <?php the_device_type(); ?>">
    <?php the_content(); ?>
</main>
<?php
get_footer();
wp_reset_postdata();
