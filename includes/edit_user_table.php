<?php
(defined('ABSPATH')) || exit;

add_filter('manage_users_columns', 'add_institute_posts_column');
function add_institute_posts_column($columns)
{
    $columns[ 'institute_posts' ] = 'موسسه';
    return $columns;
}

add_action('manage_users_custom_column', 'show_institute_posts_count', 10, 3);
function show_institute_posts_count($output, $column_name, $user_id)
{
    if ($column_name === 'institute_posts') {

        $args = [
            'post_type' => 'institute',
            'post_status' => 'publish',
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

        $post_count = count_user_posts($user_id, 'institute');
        $output = sprintf('<a href="%s" class="edit"><span aria-hidden="true">%d</span></a>', admin_url('edit.php?post_type=institute&author=' . $user_id), $post_count);
    }

    $user_cap = get_userdata($user_id);

    return ($user_cap && $user_cap->has_cap('operator')) ? $output : '-';
}