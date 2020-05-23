<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Nax_Custom
 */

function the_theme_header()
{
?>
	<header id="site-header" class="<?php the_device_type(); ?>">
		<section class="company-info">
			<div>
				<?php while (have_rows('contact_info', 'option')) : the_row();
					$mail = get_sub_field('mail');
					$phone = get_sub_field('phone');
				?>
					<a href="tel:<?php echo $phone; ?>" target="_blank" rel='noopener' rel='noreferrer'>
						<i class="fas fa-phone"></i>
						<span><?php echo $phone; ?></span>
					</a>
					<a href="mailto:<?php echo $mail; ?>" target="_blank" rel='noopener' rel='noreferrer'>
						<i class="far fa-envelope"></i>
						<span><?php echo $mail; ?></span>
					</a>
				<?php endwhile; ?></nav>
				<?php the_company_menu(); ?>
			</div>
		</section>
		<section class="sticky-menu">
			<section>
				<?php the_logo(); ?>
				<?php the_header_menu(); ?>
			</section>
		</section>
	</header>
<?php
}
if (is_admin()) {
	return;
}

/* not working properly, cant get acf field outside page?

$blog_page_id = get_field('blog_page_id', 'option');
global $post;
$add_blog_link_rel = $blog_page_id == $post->ID;
if ($add_blog_link_rel) {
	$posts_per_page = get_field('posts_per_page');
	$ajax_init = get_ajax_blogposts(false, $posts_per_page, false);
	$current_url = $ajax_init['current_url'];
	$next_url = $ajax_init['next_url'];
	$prev_url = $ajax_init['prev_url'];
	$link_rel = "<link rel='canonical' href='{$current_url}'>";
	$link_rel .= empty($next_url) ? '' : "<link rel='next' href='{$next_url}'>";
	$link_rel .= empty($prev_url) ? '' : "<link rel='prev' href='{$prev_url}'>";
}
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php the_device_type(); ?>">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php /* FAVICON */ ?>
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="theme-color" content="#ffffff">
	<script src="https://kit.fontawesome.com/f76a1e3b45.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-76541327-6"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-76541327-6');
	</script>
	<?php /*if ($add_blog_link_rel) echo $link_rel; */ ?>
	<?php /* WP */ ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(array(get_device_type(), 'base')); ?>>
	<?php the_theme_header(); ?>
