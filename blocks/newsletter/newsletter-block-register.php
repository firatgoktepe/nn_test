<?php

/**
 * Register block
 *
 * @package Nax_Custom
 * 
 * 
 * 
 */

if (function_exists('acf_register_block_type')) {
    add_action('acf/init', function () {
        $block_name = 'newsletter';
        $block_title = __('Newsletter Registration');
        $block_desctiption = __('Displays a form for subscribing to newsletter.');

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => $block_name . '-block',
            'title'             => $block_title,
            'description'       => $block_desctiption,
            'category'          => 'common', // common | formatting | layout | widgets | embed
            'icon'              => file_get_contents(
                get_template_directory() . '/blocks/' . $block_name . '/' . $block_name . '-block.svg'
            ),
            'mode'              => 'preview',
            'align'             => '', //  “left”, “center”, “right”, “wide” and “full” (default)
            'keywords'          => array(),
            'supports'          => array(
                'html'              => false,
                'customClassName'   => false,
                'reusable'          => true,
                'align'             => false, // disable alignment toolbar
                //'align' 		    => array('left', 'center', 'right'), // customize alignment toolbar
                'multiple'          => false //allows the block to be added multiple times
            ),
            'post_types'        => array('post', 'page'),
            'render_template'   => 'blocks/' . $block_name . '/' . $block_name . '-block.php',
            'render_callback'   => false,
            'enqueue_style'     => false,
            'enqueue_script'    => false,
            'enqueue_assets'    => false,
        ));
    });
}
