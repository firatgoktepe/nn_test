<?php


/**
 * 
 * Template Name: Home
 * 
 */
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Nax_Custom
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <main id="site-main" class="page-<?php the_ID(); ?> <?php the_device_type(); ?>">
    <?php get_header(); ?>
    <?php the_content(); ?>
  </main>
<?php endwhile; ?>
<?php get_footer(); ?>

Home

