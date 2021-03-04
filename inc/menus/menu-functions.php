<?php

/**
 * Menu functions
 *
 * @package Nax_Custom
 */

require get_template_directory() . '/inc/menus/menu-builder-functions.php';
require get_template_directory() . '/inc/menus/menu-render-functions.php';

function the_company_menu()
{
    if (!wp_is_mobile()) {
        echo create_menu('company-menu', 'nav', 1);
    }
}

function the_header_menu()
{
    if (wp_is_mobile() or !wp_is_mobile()) {
        $menu = create_menu_hierarchy('header-menu');
        $html = create_expander_menu_html($menu, 2, false);
        if (!$html) {
            return '';
        }
?>
        <section class="expander header-menu">
            <input class="expander-checkbox" type="checkbox" id="expander-header-menu" />
            <label class="expander-label" for="expander-header-menu"><span class="open"></span><span class="close"></span></label>
            <nav class="expander-content">
                <?php echo $html; ?>
            </nav>
        </section>
    <?php //
    } else {
        echo create_menu('header-menu', 'nav', 2);
    }
}
function the_footer_menu()
{
    if (wp_is_mobile() or !wp_is_mobile()) {
        $menu = create_menu_hierarchy('footer-menu');
        $html = create_expander_menu_html($menu, 1, false);
        if (!$html) {
            return '';
        }

        echo "<nav class='footer-menu'>{$html}</nav>";
    } else {
        echo create_menu('footer-menu', 'nav', 2);
    }
}

function the_side_menu($side_menu_tag = '')
{
    if (!has_side_menu()) {
        return;
    }

    $id = get_acf_post_id();
    $parent = get_field('side_menu_parent_page', $id);
    $menu = get_menu_by_parent($parent, 'page', array('publish'));

    if (wp_is_mobile() or !wp_is_mobile()) {
        $html = create_expander_menu_html($menu, 5, true, 1);
    } else {
        $html = create_expander_menu_html($menu, 5, true);
    }
    if (!$html) {
        return '';
    }

    if (wp_is_mobile() or !wp_is_mobile()) {
        if (!$parent) {
            $homepage_id = get_option('page_on_front');
            $parent = get_post($homepage_id);
        }
        global $post;
        $post = $parent;
        setup_postdata($post);
        $expander_id = 'expander-rng' . rng_id() . '-' . $post->ID;
        $url = get_permalink($post->ID);

        if ($side_menu_tag) {
            echo "<{$side_menu_tag}>";
        } ?>
        <nav>
            <section class="expander ">
                <input class="expander-checkbox" type="checkbox" id="<?php echo $expander_id; ?>" />
                <header class="expander-label  has-children menu-depth-0">
                    <a href="<?php echo $url; ?>"><?php echo $post->post_title ?></a>
                    <label for="<?php echo $expander_id; ?>"><span class="open"></span><span class="close"></span></label>
                </header>
                <article class="expander-content">
                    <?php echo $html; ?>
                </article>
            </section>
        </nav>
<?php //
        echo "</{$side_menu_tag}>";
        wp_reset_postdata();
    } else {
        $html = "<nav>{$html}</nav>";
        if ($side_menu_tag) {
            $html = "<{$side_menu_tag}>{$html}</{$side_menu_tag}>";
        }
        echo $html;
    }
}
