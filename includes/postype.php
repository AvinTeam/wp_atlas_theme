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
        'name' => 'مرکز قرآنی',
        'singular_name' => 'institute',
        'menu_name' => 'مراکز قرآنی',
        'name_admin_bar' => 'مرکز قرآنی',
        'add_new' => 'اضافه کردن',
        'add_new_item' => 'اضافه کردن مرکز قرآنی',
        'new_item' => 'مرکز قرآنی جدید',
        'edit_item' => 'ویرایش مرکز قرآنی',
        'view_item' => 'نمایش مرکز قرآنی',
        'all_items' => 'همه مراکز قرآنی',
        'search_items' => 'جست و جو مرکز قرآنی',
        'parent_item_colon' => 'مرکز قرآنی والد: ',
        'not_found' => 'مرکز قرآنی ای یافت نشد',
        'not_found_in_trash' => 'مرکز قرآنی ای در سطل زباله یافت نشد',
        'featured_image' => 'کاور مرکز قرآنی',
        'set_featured_image' => 'انتخاب تصویر',
        'remove_featured_image' => 'حذف تصویر',
        'use_featured_image' => 'استفاده به عنوان کاور',
        'archives' => 'دسته بندی مرکز قرآنی',
        'insert_into_item' => 'در مرکز قرآنی درج کنید',
        'uploaded_to_this_item' => 'در این مرکز قرآنی درج کنید',
        'filter_items_list' => 'فیلتر مرکز قرآنی',
        'items_list_navigation' => 'پیمایش مرکز قرآنی',
        'items_list' => 'لیست مرکز قرآنی',
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