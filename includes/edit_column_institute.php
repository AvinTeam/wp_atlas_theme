<?php
(defined('ABSPATH')) || exit;

function add_institute_columns($columns)
{
    $new_columns = [  ];
    foreach ($columns as $key => $label) {
        $new_columns[ $key ] = $label;

        if ($key === 'title') {
            $new_columns[ 'province' ] = 'استان';
            $new_columns[ 'city' ] = 'شهر';
        }
    }
    return $new_columns;
}
add_filter('manage_institute_posts_columns', 'add_institute_columns');

function fill_institute_columns($column, $post_id)
{

    $irandb = new Iran_Area;

    if ($column === 'province') {

        $province = get_post_meta($post_id, '_atlas_ostan', true);

        $province = $province ? $irandb->get('id', $province) : 0;

        echo $province ? esc_html($province->name) : '—';
    }
    if ($column === 'city') {

        $city = get_post_meta($post_id, '_atlas_city', true);

        $city = $city ? $irandb->get('id', $city) : 0;

        echo $city ? esc_html($city->name) : '—';

    }
}
add_action('manage_institute_posts_custom_column', 'fill_institute_columns', 10, 2);



function sort_mat_total_points_column($query)
{

    if (is_admin() && isset($_GET[ 'operator' ]) && !empty($_GET[ 'operator' ])) {
        $area_id = intval($_GET[ 'operator' ]);
        $query->query_vars[ 'meta_key' ] = '_operator';
        $query->query_vars[ 'meta_value' ] = $area_id;
    }



}
add_action('pre_get_posts', 'sort_mat_total_points_column');