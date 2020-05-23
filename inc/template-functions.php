<?php

/**
 * General template functions
 *
 * @package Nax_Custom
 */

require_once get_template_directory() . '/inc/site/logo-functions.php';
require_once get_template_directory() . '/inc/site/background-functions.php';
require_once get_template_directory() . '/inc/menus/menu-functions.php';

function get_device_type()
{
    return wp_is_mobile() ? 'mobile' : 'desktop';
}
function the_device_type($generate_class_attribute = false)
{
    $device = get_device_type();

    echo $generate_class_attribute ? "class='{$device}'" : $device;
}

function get_textarea_esc($textarea)
{
    return wp_kses($textarea, array('br' => array()));
}

function get_wysiwyg($wysiwyg)
{
    return $wysiwyg;
}

function get_text_esc($text)
{
    return esc_html($text);
}

function rng_id($length = 4)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $rng_id = '';

    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $rng_id .= $characters[$index];
    }

    return $rng_id;
}

function get_acf_post_id()
{
    if (is_admin() && function_exists('acf_maybe_get_POST')) :
        return intval(acf_maybe_get_POST('post_id'));
    else :
        global $post;
        return $post->ID;
    endif;
}

function get_cpt_taxonomy_field($field_name, $post_id)
{
    $field = get_field($field_name, $post_id);
    if ($field && isset($field->name)) {
        $field = get_text_esc($field->name);
    }
    return $field;
}

function get_cpt_related_post_title($field_name, $post_id)
{
    $field = get_field($field_name, $post_id);
    if ($field && isset($field->post_title)) {
        $field = get_text_esc($field->post_title);
    }
    return $field;
}

function get_cpt_related_post_field($related_post_name, $field_name, $post_id)
{/*
    // override $post
    $post = $post_object;
    setup_postdata($post);

?>
    <div>
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <span>Post Object Custom Field: <?php the_field('field_name'); ?></span>
    </div>
<?php wp_reset_postdata();
    $field = get_field($field_name, $post_id);
    if ($field && isset($field->post_title)) {
        $field = get_text_esc($field->post_title);
    }
    return $field;
    */
}

function get_cpt_image_url($field_name, $post_id = -1)
{
    if ($post_id == -1) {
        $image = get_field($field_name);
    } else {
        $image = get_field($field_name, $post_id);
    }
    $url = '';
    if (isset($image) && !empty($image)) {
        $url = get_attachment_url($image['ID']);
    }
    return $url;
}

function get_attachment_url($attachment_id, $size = '')
{
    $url = '';
    if (isset($attachment_id) && !empty($attachment_id)) {
        if ($size == '') {
            $size = wp_is_mobile() ? 'medium' : 'large';
        }
        $attachment  = wp_get_attachment_image_src($attachment_id, $size);
        $url = isset($attachment[0]) ? $attachment[0] : '';
    }
    return $url;
}

function get_cpt_taxonomy_field_names($terms, $max_terms = 0)
{
    $names = '';
    if ($terms) {
        $length = count($terms);
        if ($max_terms != 0) {
            $length = $max_terms > $length ? $length : $max_terms;
        }
        for ($index = 0; $index < $length; $index++) {
            $names .= $terms[$index]->name;
            if ($index < $length - 1) {
                if ($index == $length - 2) {
                    $names .= ' & ';
                } else {
                    $names .= ', ';
                }
            }
        }
    }
    return $names;
}

function get_post_parameter($key)
{
    return (isset($_POST[$key]) ? $_POST[$key] : '');
}

/**
 * Get field key for field name.
 * Will return first matched acf field key for a give field name.
 * 
 * ACF somehow requires a field key, where a sane developer would prefer a human readable field name.
 * http://www.advancedcustomfields.com/resources/update_field/#field_key-vs%20field_name
 * 
 * This function will return the field_key of a certain field.
 * 
 * @param $field_name String ACF Field name
 * @param $post_id int The post id to check.
 * @return 
 */
function acf_get_field_key( $field_name, $post_id ) {
	global $wpdb;
	$acf_fields = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_parent,post_name FROM $wpdb->posts WHERE post_excerpt=%s AND post_type=%s" , $field_name , 'acf-field' ) );
	// get all fields with that name.
	switch ( count( $acf_fields ) ) {
		case 0: // no such field
			return false;
		case 1: // just one result. 
			return $acf_fields[0]->post_name;
	}
	// result is ambiguous
	// get IDs of all field groups for this post
	$field_groups_ids = array();
	$field_groups = acf_get_field_groups( array(
		'post_id' => $post_id,
	) );
	foreach ( $field_groups as $field_group )
		$field_groups_ids[] = $field_group['ID'];
	
	// Check if field is part of one of the field groups
	// Return the first one.
	foreach ( $acf_fields as $acf_field ) {
		if ( in_array($acf_field->post_parent,$field_groups_ids) )
			return $acf_field->post_name;
	}
	return false;
}