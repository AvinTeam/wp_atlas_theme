<?php
(defined('ABSPATH')) || exit;

add_filter('manage_users_columns', 'add_institute_posts_column');
function add_institute_posts_column($columns)
{
    if (isset($columns[ 'email' ])) {
        unset($columns[ 'email' ]);
    }
    if (isset($columns[ 'posts' ])) {
        unset($columns[ 'posts' ]);
    }
    $columns[ 'institute_posts' ] = 'تعداد مرکز قرآنی';
    return $columns;
}

add_action('manage_users_custom_column', 'show_institute_posts_count', 10, 3);
function show_institute_posts_count($output, $column_name, $user_id)
{

   

    if ($column_name === 'institute_posts') {

        $args = [
            'post_type' => 'institute',
            'post_status' => [ 'pending', 'publish' ],
            'meta_query' => [
                [
                    'key' => '_operator',
                    'value' => $user_id,
                    'compare' => '=',
                 ],
             ],
            'fields' => 'ids',
            'posts_per_page' => -1,
         ];

        $query = new WP_Query($args);
        $post_count = $query->found_posts;

        update_user_meta($user_id, 'post_operator', $post_count);

        $post_count = absint(get_user_meta($user_id, 'post_operator', true));

        $output = sprintf('<a target="_blank" href="%s" class="edit"><span aria-hidden="true">%d</span></a>', admin_url('edit.php?post_type=institute&operator=' . $user_id), $post_count);

        $user_cap = get_userdata($user_id);

        $output = ($user_cap && $user_cap->has_cap('operator')) ? $output : '-';
    }
    return (!empty($output)) ? $output : '-';

}

function make_user_posts_count_sortable($sortable_columns)
{
    $sortable_columns[ 'institute_posts' ] = 'institute_posts';
    return $sortable_columns;
}
add_filter('manage_users_sortable_columns', 'make_user_posts_count_sortable');

add_filter('users_list_table_query_args', 'dsl_users_sortable_query');
function dsl_users_sortable_query($args)
{

    if (is_admin() && !empty($_GET[ 'orderby' ]) && $_GET[ 'orderby' ] == 'institute_posts') {

        $args[ 'orderby' ] = 'meta_value_num';
        $args[ 'meta_key' ] = 'post_operator';

    }

    return $args;
}

