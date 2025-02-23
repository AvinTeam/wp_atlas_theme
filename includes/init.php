<?php

use atlasclass\Iran_Area;

add_action('init', 'atlas_panel_rewrite');
function atlas_panel_rewrite()
{
    atlas_cookie();

    add_rewrite_rule(
        ATLAS_PAGE_BASE . '/([^/]+)/?',
        'index.php?atlas=$matches[1]',
        'top'
    );

    // add_rewrite_rule(
    //     ATLAS_PAGE_BASE . '/?',
    //     'index.php?atlas=dashboard',
    //     'top'
    // );

    add_rewrite_rule(
        '^institute/([0-9]+)/?$',
        'index.php?post_type=institute&p=$matches[1]',
        'top'
    );

}

add_filter('query_vars', 'atlas_query_vars');

/**
 * Filters the query variables allowed before processing.
 *
 * @param string[] $public_query_vars The array of allowed query variable names.
 * @return string[] The array of allowed query variable names.
 */
function atlas_query_vars($public_query_vars)
{

    $public_query_vars[  ] = 'atlas';

    return $public_query_vars;
}

add_filter('template_include', 'atlas_template_include');

/**
 * Filters the path of the current template before including it.
 *
 * @param string $template The path of the template to include.
 * @return string The path of the template to include.
 */
function atlas_template_include($template)
{

    $atlas = get_query_var('atlas');
    if ($atlas) {

        $path = atlas_template_path($atlas);

        if ($path) {return $path;}

    }

    return $template;
}

function restrict_admin_access()
{
    if (! is_user_logged_in()) {
        return;
    }

    $user             = wp_get_current_user();
    $restricted_roles = [ 'subscriber', 'responsible' ];

    if (array_intersect($restricted_roles, $user->roles) && ! defined('DOING_AJAX')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'restrict_admin_access');

add_filter('show_admin_bar', 'disable_admin_bar_for_specific_roles');

function disable_admin_bar_for_specific_roles($show)
{
    if (is_user_logged_in()) {
        $user             = wp_get_current_user();
        $restricted_roles = [ 'subscriber', 'responsible' ];

        if (array_intersect($restricted_roles, $user->roles)) {
            return false;
        }
    }

    return $show;
}

$atlas_iran_area = new Iran_Area;

if (! $atlas_iran_area->num()) {
    $atlas = $atlas_iran_area->insert_old_data();
}

function custom_login_redirect($redirect_to, $request, $user)
{
    if (isset($user->roles) && in_array('operator', $user->roles)) {
        $redirect_to = admin_url('edit.php?post_type=institute');
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

function hide_default_meta_boxes($hidden, $screen)
{
    if ($screen->post_type === 'institute') {
        $hidden[  ] = 'authordiv';
        $hidden[  ] = 'commentsdiv';
        $hidden[  ] = 'postcustom';
        $hidden[  ] = 'commentstatusdiv';
    }
    return $hidden;
}
add_filter('default_hidden_meta_boxes', 'hide_default_meta_boxes', 10, 2);

function save_comment_rating($comment_id)
{
    if (isset($_POST[ 'rating' ]) && ! empty($_POST[ 'rating' ])) {
        $rating = intval($_POST[ 'rating' ]);
        add_comment_meta($comment_id, 'rating', $rating);
    }

    if (is_user_logged_in()) {

        $this_user = wp_get_current_user();
        add_comment_meta($comment_id, 'mobile', $this_user->mobile);

    } elseif (isset($_POST[ 'mobile' ]) && ! empty($_POST[ 'mobile' ])) {
        $mobile = sanitize_phone($_POST[ 'mobile' ]);
        add_comment_meta($comment_id, 'mobile', $mobile);
    }

}
add_action('comment_post', 'save_comment_rating');
