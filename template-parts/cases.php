<?php

/**
 * Template Name: Cases
 */
get_header();
?>
<main>
  <?php
  //Do the WP loop
  if (have_posts()) {
    while (have_posts()) {
      the_post();

    }
  }
  ?>

  <section class="news-items">
    <?php
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
    $args = array('post_type' => 'news', 'posts_per_page' => 6, 'paged' => $paged);
    $myposts = get_posts($args);
    $the_query = new WP_Query($args);
    foreach ($myposts as $post) :  setup_postdata($post); ?>
      <a href="<?php echo get_post_permalink() ?>">
        <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
        <h3><?php the_title(); ?></h3>
        <?php the_excerpt(); ?>
      </a>
    <?php endforeach; ?>
    <?php wp_reset_postdata(); ?>
  </section>
  <nav class="news-pagination">
    <?php
    $big = 999999999; // need an unlikely integer


    echo paginate_links(array(
      'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
      'format' => '?paged=%#%',
      'current' => max(1, get_query_var('paged')),
      'total' => $the_query->max_num_pages,
      'prev_text'    => __('<i class="fas fa-chevron-left"></i>'),
      'next_text'    => __('<i class="fas fa-chevron-right"></i>'),
    ));
    ?>
  </nav>

</main>
<?php
get_footer();
