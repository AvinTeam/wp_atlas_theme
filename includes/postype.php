<?php

(defined('ABSPATH')) || exit;

/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function atlas_institute_init()
{
    $labels = array(
        'name' => 'موسسه',
        'singular_name' => 'موسسه',
        'menu_name' => 'موسسه ها',
        'name_admin_bar' => 'موسسه',
        'add_new' => 'اضافه کردن',
        'add_new_item' => 'اضافه کردن موسسه',
        'new_item' => 'موسسه جدید',
        'edit_item' => 'ویرایش موسسه',
        'view_item' => 'نمایش موسسه',
        'all_items' => 'همه موسسه ها',
        'search_items' => 'جست و جو موسسه',
        'parent_item_colon' => 'موسسه والد: ',
        'not_found' => 'کتابی یافت نشد',
        'not_found_in_trash' => 'کتابی در سطل زباله یافت نشد',
        'featured_image' => 'کاور موسسه',
        'set_featured_image' => 'انتخاب تصویر',
        'remove_featured_image' => 'حذف تصویر',
        'use_featured_image' => 'استفاده به عنوان کاور',
        'archives' => 'دسته بندی موسسه',
        'insert_into_item' => 'در موسسه درج کنید',
        'uploaded_to_this_item' => 'در این موسسه درج کنید',
        'filter_items_list' => 'فیلتر موسسه',
        'items_list_navigation' => 'پیمایش موسسه',
        'items_list' => 'لیست موسسه',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'hierarchical' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,        
        'menu_position' => null,
        'query_var' => true,
        'menu_icon' => 'dashicons-businessman',
        'capability_type' => 'atlas',
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'comments','custom-fields'),
        'rewrite' => array('slug' => 'institute'),
        'has_archive' => true,
    );

    register_post_type('institute', $args);
}

add_action('init', 'atlas_institute_init');



//institute