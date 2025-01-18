<?php
(defined('ABSPATH')) || exit;

function atlas_title_filter($title) {
    if (is_home() || is_front_page()) {
        $title = get_bloginfo('name') ." | صفحه اصلی";
    } elseif (is_single()) {
        $title = get_the_title() . " | " . get_bloginfo('name');
    } elseif (is_category()) {
        $title = single_cat_title('', false) . " | دسته‌بندی";
    } elseif (is_tag()) {
        $title = single_tag_title('', false) . " | برچسب";
    } elseif (is_search()) {
        $title = "نتایج جستجو برای " . get_search_query();
    } elseif (is_404()) {
        $title =get_bloginfo('name'). "صفحه پیدا نشد | ";
    } else {
        $title = get_bloginfo('name');
    }
    return $title;
}
add_filter('wp_title', 'atlas_title_filter');
function custom_institute_template($template) {
    if (is_singular('institute')) {
        $custom_template = locate_template('institute.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'custom_institute_template');