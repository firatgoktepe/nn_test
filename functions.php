<?php

/**
 * NAX Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Nax_Custom
 */

if (!function_exists('naxtheme_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function naxtheme_setup()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(
            array(
                'company-menu' => __('Company'),
                'header-menu' => __('Header'),
                'footer-menu' => __('Footer'),
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // add acf options page
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page();
        }
    }
endif;
add_action('after_setup_theme', 'naxtheme_setup');

function wpdocs_remove_menus()
{

    remove_menu_page('customize.php');
}
add_action('admin_menu', 'wpdocs_remove_menus');

function limit_blocks($allowed_blocks)
{

    // get widget blocks and registered by plugins blocks
    $registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

    $block_names_to_remove = array();
    foreach ($registered_blocks as $block) {
        if (startsWith($block->name, 'core')) {
            array_push($block_names_to_remove, $block->name);
        }
    }

    foreach ($block_names_to_remove as $block_name) {
        unset($registered_blocks[$block_name]);
    }

    // now $registered_blocks contains only blocks registered by plugins, but we need keys only
    $registered_blocks = array_keys($registered_blocks);

    // merge the whitelist with plugins blocks
    return array_merge(array(
        // add blocks to keep
        'core/block',         // to keep reusable block function
    ), $registered_blocks);
}
add_filter('allowed_block_types', 'limit_blocks');

/**
 * Enqueue scripts and styles.
 */
function naxtheme_scripts()
{
    wp_enqueue_script('menu-scripts', get_template_directory_uri() . '/src/js/menus.js');
    wp_enqueue_style('font-awesome', 'https://kit-free.fontawesome.com/releases/latest/css/free.min.css');
    wp_enqueue_style('naxtheme-style', get_stylesheet_directory_uri() . '/dist/style.css' );
}
add_action('wp_enqueue_scripts', 'naxtheme_scripts');


/* ACF JSON */

            // Save
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
 
function my_acf_json_save_point( $path ) {
    
    // update path
    $path = get_stylesheet_directory() . '/acf-json';
    
    
    // return
    return $path;
    
}

            // Load

add_filter('acf/settings/load_json', 'my_acf_json_load_point');

function my_acf_json_load_point( $paths ) {
    
    // remove original path (optional)
    unset($paths[0]);
    
    
    // append path
    $paths[] = get_stylesheet_directory() . '/acf-json';
    
    
    // return
    return $paths;
    
}


/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/toolbox-functions.php';
//require_once get_template_directory() . '/inc/minify-html-functions.php';
require_once get_template_directory() . '/inc/ajax/mailchimp-functions.php';
require_once get_template_directory() . '/inc/ajax/contactform-functions.php';
require_once get_template_directory() . '/inc/ajax/blog-functions.php';

/**
 * Post Types
 */
require_once get_template_directory() . '/post-types/post-type-functions.php';

/**
 * Blocks
 */
require_once get_template_directory() . '/blocks/register-blocks.php';


/**
 *
 */
