<?php

/**
 * TinyMCE functions
 * 
 * @package Nax_Custom
 * 
 */

 /**
 *  Remove the h1 tag from the WordPress editor.
 *
 *  @param   array  $settings  The array of editor settings
 *  @return  array             The modified edit settings
 */
function customize_tinymce_format_toolbar($in)
{
    $in['block_formats'] = "Paragraph=p; Heading 4=h4; Heading 5=h5; Heading 6=h6;Preformatted=pre";
    return $in;
}
add_filter('tiny_mce_before_init', 'customize_tinymce_format_toolbar');

