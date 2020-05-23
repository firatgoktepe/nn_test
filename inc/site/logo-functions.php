<?php

/**
 * Background functions
 *
 * @package Nax_Custom
 *
 *
 *
 */
function get_logo_url()
{
    $logo_settings = get_field('logo_settings', 'option');
    return get_attachment_url($logo_settings['logo_dark_background']);
}

function the_logo()
{

    $site_name = get_bloginfo('name');
    $site_url = get_bloginfo('url');

    $url = get_logo_url();

    $html = "<figure class='logo'>";
    $html .= "<a href='{$site_url}'>";
    //$html .= "<a href='{$site_url}' style='background-image: url({$url});'>";
    $html .= "<img src='{$url}' alt='{$site_name}' title='{$site_name}' />";
    $html .= "</a>";
    $html .= "</figure>";

    echo $html;
}
