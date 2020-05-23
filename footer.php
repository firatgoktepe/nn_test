<?php

/**
 * The template for displaying the footer
 *
 * @package Nax_Custom
 */

?>
<footer id="site-footer" class="<?php the_device_type(); ?>">

	<div class="footer-content">
	  <p>Du kan också köpa tjänsten som en del av företagshälsovården av våra partners:</p>
	  <a href="https://www.avonova.se/" target="_blank">
		<p>Avonova</p>
	  </a>
	  <a href="https://www.feelgood.se/hem/" target="_blank">
	  	<p>Feelgood</p>
	  </a>

    </div>
	<header><?php the_logo(); ?></header>
	<main>
		<section id="social-media-links">
			<?php while (have_rows('social_media', 'option')) : the_row();
				$social_media_link = get_sub_field('social_media_link');
				$social_media_icon = get_sub_field('line_awesome_icon');

				if ($social_media_link && !empty($social_media_icon)) :
					$link_url = esc_url($social_media_link['url']);
					$link_title = esc_attr($social_media_link['title']);
					$link_target = esc_attr($social_media_link['target'] ? $social_media_link['target'] : '_self');
			?>
					<a href="<?php echo $link_url; ?>" target="<?php echo $link_target; ?>" rel="noopener" rel="noreferrer" title="<?php echo $link_title; ?>">
						<?php echo $social_media_icon; ?>
					</a>
				<?php endif; ?>
			<?php endwhile; ?>
		</section>
		<section id="contact-info">
			<?php while (have_rows('contact_info', 'option')) : the_row();
				$address = get_sub_field('address');
				$google_maps_link = get_sub_field('google_maps_link');
				$mail = get_sub_field('mail');
				$phone = get_sub_field('phone');
			?>
				<a href="<?php echo $google_maps_link; ?>" target="_blank" rel='noopener' rel='noreferrer'>
					<span><i class="fas fa-map-marker-alt"></i></span>
					<span><?php echo $address; ?></span>
				</a>
				<a href="tel:<?php echo $phone; ?>" target="_blank" rel='noopener' rel='noreferrer'>
					<span><i class="fas fa-phone-alt"></i></span>
					<span><?php echo $phone; ?></span>
				</a>
				<a href="mailto:<?php echo $mail; ?>" target="_blank" rel='noopener' rel='noreferrer'>
					<span><i class="far fa-envelope"></i></span>
					<span><?php echo $mail; ?></span>
				</a>
			<?php endwhile; ?>
		</section>
		<section id="footer-menu">
			<?php the_footer_menu(); ?>
		</section>
		<section id="copyright">
			<span><?php echo get_text_esc(get_field('copyright_text', 'option')); ?></span>
			<span><?php echo date('Y'); ?></span>
		</section>
	</main>
	<footer>
		<section id="credits">
			<?php while (have_rows('credits_links', 'option')) : the_row();
				$link_text = get_sub_field('link_text');
				$link = get_sub_field('link');
			?>
				<a href="<?php echo $link; ?>" target="_blank" title="<?php echo $link_text; ?>">
					<?php echo $link_text; ?>
				</a>
			<?php endwhile; ?>
		</section>
	</footer>
</footer>
<?php wp_footer(); ?>
</body>

</html>
