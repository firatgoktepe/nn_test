<?php

/**
 * ACF Block
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 * 
 * @package Chili_Mobil_Theme
 * 
 */

require_once get_template_directory() . '/blocks/blocks-functions.php';

$counter = 0;

the_block_header('expander-list-block');

?>
<?php while (have_rows('list')) : the_row();
    $headline = get_text_esc(get_sub_field('headline'));
    $text = get_wysiwyg(get_sub_field('text'));
    $counter++;
    $expander_id = 'expander-list-id-' . $counter;
?>
    <article class="expander unselectable">
        <input class="expander-checkbox" type="checkbox" id="<?php echo $expander_id; ?>" />
        <label class="expander-label" for="<?php echo $expander_id; ?>"><h4><?php echo $headline; ?></h4></label>
        <article class="expander-content selectable">
            <?php echo $text; ?>
        </article>
    </article>
<?php endwhile; ?>
<?php
the_block_footer();
