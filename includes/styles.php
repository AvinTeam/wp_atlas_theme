<?php

(defined('ABSPATH')) || exit;

add_action('admin_enqueue_scripts', 'atlas_admin_script');

function atlas_admin_script()
{

    wp_enqueue_style(
        'atlas_admin',
        ATLAS_CSS . 'admin.css',
        [  ],
        ATLAS_VERSION
    );
    
    wp_enqueue_media();

    wp_enqueue_script(
        'atlas_admin',
        ATLAS_JS . 'admin.js',
        [ 'jquery' ],
        ATLAS_VERSION,
        true
    );

    wp_localize_script(
        'atlas_admin',
        'atlas_js',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
         ]
    );

}
