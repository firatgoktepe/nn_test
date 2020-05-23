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
?>

<?php 

$post_objects = get_field('blog_post');

if( $post_objects ): ?>
    <section class='block blog-post-block <?php echo get_device_type(); ?>'>
      <main>  
    <?php foreach( $post_objects as $post): ?>
     <?php setup_postdata($post); ?>
            <a href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?>
               <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
               <?php the_excerpt(); ?> 
            </a>

    <?php endforeach; ?>
      </main>
    </section>
   <?php wp_reset_postdata(); ?> 
<?php endif; ?>



