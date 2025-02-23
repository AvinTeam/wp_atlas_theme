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
            'nonce'    => wp_create_nonce('ajax-nonce'),
         ]
    );

}

add_action('wp_enqueue_scripts', 'atlas_style');

function atlas_style()
{
    wp_register_style(
        'bootstrap',
        ATLAS_VENDOR . 'bootstrap/bootstrap.min.css',
        [  ],
        '5.3.3'
    );
    wp_register_style(
        'bootstrap.rtl',
        ATLAS_VENDOR . 'bootstrap/bootstrap.rtl.min.css',
        [ 'bootstrap' ],
        '5.3.3'
    );
    wp_register_style(
        'bootstrap.icons',
        ATLAS_VENDOR . 'bootstrap/bootstrap-icons.min.css',
        [ 'bootstrap' ],
        '1.11.3'
    );
    wp_register_style(
        'select2',
        ATLAS_VENDOR . 'select2/select2.min.css',
        [ 'bootstrap' ],
        '4.1.0-rc.0'
    );

    wp_enqueue_style(
        'atlas_style',
        ATLAS_CSS . 'public.css',
        [ 'bootstrap.rtl','bootstrap.icons', 'select2' ],
        ATLAS_VERSION
    );

    wp_register_script(
        'bootstrap',
        ATLAS_VENDOR . 'bootstrap/bootstrap.min.js',
        [  ],
        '5.3.3',
        true
    );

    wp_register_script(
        'select2',
        ATLAS_VENDOR . 'select2/select2.min.js',
        [  ],
        '4.1.0-rc.0',
        true
    );

    wp_enqueue_script(
        'atlas_js',
        ATLAS_JS . 'public.js',
        [ 'jquery', 'bootstrap', 'select2' ],
        ATLAS_VERSION,
        true
    );

    wp_localize_script(
        'atlas_js',
        'atlas_js',
        [
            'ajaxurl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('ajax-nonce' . atlas_cookie()),
            'page_base' => atlas_base_url(),
         ]
    );

}
