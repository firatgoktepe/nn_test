<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Nax_Custom
 */

global $post;
$post = get_post(get_option('page_on_front'));
setup_postdata($post);

get_header();
?>
<main id="site-main" class="page-<?php the_ID(); ?> <?php the_device_type(); ?>">
	<?php the_content(); ?>
	<p>template</p>
</main>

<?php

get_footer();
wp_reset_postdata();
