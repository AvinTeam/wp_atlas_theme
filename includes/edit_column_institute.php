<?php

use atlasclass\Iran_Area;

(defined('ABSPATH')) || exit;

function add_institute_columns($columns)
{

    if (isset($columns[ 'author' ])) {
        $columns[ 'author' ] = 'مسئول مرکز قرآنی';
    }

    $new_columns = [  ];
    foreach ($columns as $key => $label) {
        $new_columns[ $key ] = $label;

        if ($key === 'title') {
            $new_columns[ 'province' ] = 'استان';
            $new_columns[ 'city' ]     = 'شهر';
            $new_columns[ 'operator' ] = 'اپراتور';
        }

    }
    return $new_columns;
}
add_filter('manage_institute_posts_columns', 'add_institute_columns');

function fill_institute_columns($column, $post_id)
{

    $irandb = new Iran_Area;

    if ($column === 'province') {

        $province = absint(get_post_meta($post_id, '_atlas_ostan', true));

        if ($province) {
            $province = $province ? $irandb->get('id', $province) : 0;

        }

        echo $province ? esc_html($province->name) : '—';

    }
    if ($column === 'city') {

        $city = absint(get_post_meta($post_id, '_atlas_city', true));

        if ($city) {
            $city = $city ? $irandb->get('id', $city) : 0;

        }

        echo $city ? esc_html($city->name) : '—';

    }

    if ($column === 'operator') {

        $operator = absint(get_post_meta($post_id, '_operator', true));
        $output   = "نامشخص";

        if ($operator) {

            $user_info = get_userdata($operator);

            $output = sprintf('<a target="_blank" href="%s" class="edit"><span aria-hidden="true">%s</span></a>', admin_url('edit.php?post_type=institute&operator=' . $operator), $user_info->user_login);

        }
        echo $output;

    }
}
add_action('manage_institute_posts_custom_column', 'fill_institute_columns', 10, 2);

function sort_mat_total_points_column($query)
{

    if (is_admin() && isset($_GET[ 'operator' ]) && ! empty($_GET[ 'operator' ])) {
        $operator_id                       = intval($_GET[ 'operator' ]);
        $query->query_vars[ 'meta_key' ]   = '_operator';
        $query->query_vars[ 'meta_value' ] = $operator_id;
    }

    if (is_admin() && isset($_GET[ 'city' ]) && ! empty($_GET[ 'city' ])) {
        $city_id                           = intval($_GET[ 'city' ]);
        $query->query_vars[ 'meta_key' ]   = '_atlas_city';
        $query->query_vars[ 'meta_value' ] = $city_id;
    }

    if (is_admin() && isset($_GET[ 's' ]) && is_mobile(sanitize_phone($_GET[ 's' ]))) {
        $query->query_vars[ 'meta_key' ]   = '_atlas_responsible-mobile';
        $query->query_vars[ 'meta_value' ] = sanitize_phone($_GET[ 's' ]);
        $query->set('s', '');
    }
}
add_action('pre_get_posts', 'sort_mat_total_points_column');

function add_csv_export_button_to_custom_post_type($which)
{
    global $typenow;

    if ($typenow === 'institute' && $which === 'top') {

        echo '<div class="alignleft actions"><a href="' . esc_url(atlas_end_url('action', 'art_exel')) . '" class="button button-primary" >خروجی EXEL</a></div>';

    }
}
add_action('manage_posts_extra_tablenav', 'add_csv_export_button_to_custom_post_type', 10, 1);