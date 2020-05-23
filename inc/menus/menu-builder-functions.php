<?php

/**
 * Menu builder functions
 *
 * @package Nax_Custom
 */

class MenuItem
{
	public $title;
	public $url;
	public $menu_id;
	public $menu_parent_id;
	public $object_id;
	public $children = array();

	public function __construct($title, $url, $menu_id, $menu_parent_id, $object_id)
	{
		$this->object_id = $object_id;
		$this->menu_id = $menu_id;
		$this->menu_parent_id = $menu_parent_id;
		$this->title = $title;
		$this->url = $url;
	}
}



/**
 * Create heirachical menu of array of MenuItem
 * @param string $theme_location Name of menu location
 * @return string Returns an array of class MenuItem
 */
function create_menu_hierarchy($theme_location)
{
	if (($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location])) {

		$nav_menu_object = wp_get_nav_menu_object($locations[$theme_location]);
		$nav_menu_items = wp_get_nav_menu_items($nav_menu_object->term_id);

		if (!$nav_menu_items)  return false;

		$all_items = array();
		foreach ($nav_menu_items as $nav_menu_item) {
			$parent = $nav_menu_item->menu_item_parent;
			$menu_item = new MenuItem(
				$nav_menu_item->title,
				$nav_menu_item->url,
				$nav_menu_item->ID,
				$nav_menu_item->menu_item_parent,
				$nav_menu_item->object_id
			);
			$all_items[$nav_menu_item->ID] = $menu_item;
			if ($parent != 0) {
				$all_items[$parent]->children[$menu_item->menu_id] = $menu_item;
			}
		}
		$menu = array();
		foreach ($all_items as $menu_item) {
			if ($menu_item->menu_parent_id == 0) {
				$menu[$menu_item->menu_id] = $menu_item;
			}
		}
		return $menu;
	}
}



function has_side_menu($css_class = '')
{
	$id = get_acf_post_id();
	$has_menu = get_field('side_menu', $id);
	if ($css_class == '') {
		return $has_menu;
	} else {
		echo $has_menu ?  $css_class : '';
	}
}

function get_menu_by_parent($parent, $post_type, $post_status, $toggle_char = '', $depth = 0)
{
	$get_child_pages = function ($post_type, $post_status, $parent_id) {
		return get_pages(
			array(
				'post_type'     => $post_type,
				'post_status'   => $post_status,
				'parent'		=> $parent_id,
			)
		);
	};

	$menu = array();
	$children = $get_child_pages($post_type, $post_status, $parent ? $parent->ID : 0);
	if ($children) {
		foreach ($children as $child) {
			$menu_item = new MenuItem(
				$child->post_title,
				get_permalink($child->ID),
				$child->ID,
				$child->post_parent,
				$child->ID
			);
			$menu_item->children = get_menu_by_parent($child, $post_type, $post_status, $toggle_char, $depth + 1);
			$menu[$child->ID] = $menu_item;
		}
	}
	return $menu;
}

function get_menu_item_ancestor_ids($root_items, $menu_items, $menu_item_id, $ancestor_ids = array())
{
	foreach ($menu_items as $menu_item) {
		if ($menu_item_id == $menu_item->object_id) {
			array_push($ancestor_ids, $menu_item->menu_parent_id);
			$ancestor_ids = get_menu_item_ancestor_ids($root_items, $root_items, $menu_item->menu_parent_id, $ancestor_ids);
		}
		if ($menu_item->children) {
			$ancestor_ids = get_menu_item_ancestor_ids($root_items, $menu_item->children, $menu_item_id, $ancestor_ids);
		}
	}
	return $ancestor_ids;
}
