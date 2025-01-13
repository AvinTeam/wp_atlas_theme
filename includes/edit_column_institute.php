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
        $operator_id = intval($_GET[ 'operator' ]);
        $query->query_vars[ 'meta_key' ] = '_operator';
        $query->query_vars[ 'meta_value' ] = $operator_id;
    }

    if (is_admin() && isset($_GET[ 'city' ]) && !empty($_GET[ 'city' ])) {
        $city_id = intval($_GET[ 'city' ]);
        $query->query_vars[ 'meta_key' ] = '_atlas_city';
        $query->query_vars[ 'meta_value' ] = $city_id;
    }

    if (is_admin() && isset($_GET[ 's' ]) && is_mobile(sanitize_phone($_GET[ 's' ]))) {
        $query->query_vars[ 'meta_key' ] = '_atlas_responsible-mobile';
        $query->query_vars[ 'meta_value' ] = sanitize_phone($_GET[ 's' ]);
        $query->set('s', '');   
    }








    
//     add_filter('query', 'log_sql_query');
// function log_sql_query($query)
// {
//     print_r($query);
//     exit;
//     return $query;
// }

}
add_action('pre_get_posts', 'sort_mat_total_points_column');

