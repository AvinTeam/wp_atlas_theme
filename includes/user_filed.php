<?php
defined('ABSPATH') || exit;

add_action('edit_user_profile', 'dsl_user_inputs');
add_action('show_user_profile', 'dsl_user_inputs');
add_action('user_new_form', 'dsl_user_inputs');

function dsl_user_inputs($user)
{

    $phone = '';
    if (is_a($user, 'WP_User')) {
        $phone = $user->mobile;
    }

    include ATLAS_VIEWS . 'menu/user-inputs.php';
}

add_action('edit_user_profile_update', 'dsl_update_user');
add_action('personal_options_update', 'dsl_update_user');
add_action('user_register', 'dsl_update_user');

function dsl_update_user($user_id)
{

    if (! current_user_can('edit_user', $user_id)) {
        return;
    }

    if (isset($_POST[ 'mobile' ])) {
        $phone = sanitize_phone($_POST[ 'mobile' ]);
        update_user_meta($user_id, 'mobile', $phone);
    }

}