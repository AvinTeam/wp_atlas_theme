<?php

$atlas_iran_area = new Iran_Area;

if (!$atlas_iran_area->num()) {
    $atlas = $atlas_iran_area->insert_old_data();
    print_r($atlas);
}


function custom_login_redirect($redirect_to, $request, $user) {
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