<?php

use atlasclass\Iran_Area;

(defined('ABSPATH')) || exit;

add_action('admin_menu', 'mph_admin_menu');

/**
 * Fires before the administration menu loads in the admin.
 *
 * @param string $context Empty context.
 */
function mph_admin_menu(string $context): void
{

    $province_suffix = add_menu_page(
        'اطلس',
        'اطلس',
        'manage_options',
        'atlas',
        'atlas_menu_callback',
        'dashicons-hammer',
        55
    );

    $province_suffix = add_submenu_page(
        'atlas',
        'استان ها',
        'استان ها',
        'manage_options',
        'atlas',
        'atlas_menu_callback',
    );

    function atlas_menu_callback()
    {

        $iran = new Iran_Area();

        require_once ATLAS_VIEWS . 'menu/list.php';

    }

    $add_file_ayeh_suffix = add_submenu_page(
        'atlas',
        'افزودن با اکسل',
        'افزودن با اکسل',
        'manage_options',
        'add_file_ayeh',
        'add_file_ayeh',
    );

    function add_file_ayeh()
    {

        require_once ATLAS_VIEWS . 'menu/add_file.php';

    }

    add_action('load-' . $province_suffix, 'atlas__province');
    add_action('load-' . $add_file_ayeh_suffix, 'atlas__add_file');

    function atlas__province()
    {

        if (isset($_POST[ 'atlas_act' ]) && $_POST[ 'atlas_act' ] == 'atlas__submit') {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'atlas_nonce' . get_current_user_id())) {

                $iran = new Iran_Area();

                $data         = [ 'description' => wp_kses_post(wp_unslash(nl2br($_REQUEST[ 'description' ]))) ];
                $where        = [ 'id' => $_REQUEST[ 'province' ] ];
                $format       = [ '%s' ];
                $where_format = [ '%d' ];

                $res = $iran->update($data, $where, $format, $where_format);

                wp_admin_notice(
                    'تغییر شما با موفقیت ثبت شد',
                    [
                        'id'                 => 'message',
                        'type'               => 'success',
                        'additional_classes' => [ 'updated' ],
                        'dismissible'        => true,
                     ]
                );

            } else {
                wp_admin_notice(
                    'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                    [
                        'id'                 => 'atlas_message',
                        'type'               => 'error',
                        'additional_classes' => [ 'updated' ],
                        'dismissible'        => true,
                     ]
                );
            }

        }

        if (isset($_POST[ 'atlas_act' ]) && $_POST[ 'atlas_act' ] == 'atlas_city_submit') {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'atlas_nonce' . get_current_user_id())) {

                $iran = new Iran_Area();

                if (isset($_REQUEST[ 'city_id' ]) && absint($_REQUEST[ 'city_id' ])) {

                    $data = [
                        'name'        => sanitize_text_field($_REQUEST[ 'city_name' ]),
                        'city2'       => sanitize_text_field($_REQUEST[ 'city2_name' ]),
                        'description' => wp_kses_post(wp_unslash(nl2br($_REQUEST[ 'description' ]))),
                     ];
                    $where        = [ 'id' => absint($_REQUEST[ 'city_id' ]) ];
                    $format       = [ '%s', '%s', '%s' ];
                    $where_format = [ '%d' ];

                    $res = $iran->update($data, $where, $format, $where_format);

                    if ($res) {
                        wp_admin_notice(
                            'تغییر شما با موفقیت ثبت شد',
                            [
                                'id'                 => 'atlas_message',
                                'type'               => 'success',
                                'additional_classes' => [ 'updated' ],
                                'dismissible'        => true,
                             ]
                        );
                    } else {
                        wp_admin_notice(
                            'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                            [
                                'id'                 => 'atlas_message',
                                'type'               => 'error',
                                'additional_classes' => [ 'updated' ],
                                'dismissible'        => true,
                             ]
                        );
                    }

                } elseif (isset($_REQUEST[ 'city_id' ]) && ! absint($_REQUEST[ 'city_id' ])) {

                    $data = [
                        'name'        => sanitize_text_field($_REQUEST[ 'city_name' ]),
                        'city2'       => sanitize_text_field($_REQUEST[ 'city2_name' ]),
                        'province_id' => absint($_REQUEST[ 'province' ]),
                        'description' => wp_kses_post(wp_unslash(nl2br($_REQUEST[ 'description' ]))),
                     ];
                    $format = [ '%s', '%s', '%d', '%s' ];

                    $res = $iran->insert($data, $format);

                    if ($res) {
                        wp_admin_notice(
                            'تغییر شما با موفقیت ثبت شد',
                            [
                                'id'                 => 'atlas_message',
                                'type'               => 'success',
                                'additional_classes' => [ 'updated' ],
                                'dismissible'        => true,
                             ]
                        );
                    } else {
                        wp_admin_notice(
                            'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                            [
                                'id'                 => 'atlas_message',
                                'type'               => 'error',
                                'additional_classes' => [ 'updated' ],
                                'dismissible'        => true,
                             ]
                        );
                    }

                } else {
                    wp_admin_notice(
                        'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                        [
                            'id'                 => 'atlas_message',
                            'type'               => 'error',
                            'additional_classes' => [ 'updated' ],
                            'dismissible'        => true,
                         ]
                    );
                }

            } else {

                wp_admin_notice(
                    'ذخیره سازی به مشکل خورده دوباره تلاش کنید',
                    [
                        'id'                 => 'atlas_message',
                        'type'               => 'error',
                        'additional_classes' => [ 'updated' ],
                        'dismissible'        => true,
                     ]
                );
            }

        }

    }

    function atlas__add_file()
    {

        if (isset($_POST[ 'atlas_act' ]) && $_POST[ 'atlas_act' ] == "atlas__import") {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'atlas_nonce' . get_current_user_id())) {

                require_once ATLAS_INCLUDES . 'import-file.php';

                if ($count_row) {
                    wp_admin_notice(
                        "تعداد $count_row سوال از اکسل فراخوانی شد.",
                        [
                            'id'          => 'atlas_message',
                            'type'        => 'success',
                            'dismissible' => true,
                         ]
                    );
                } else {
                    wp_admin_notice(
                        'استخراج به مشکل خورده لطفا اکسل رو بررسی کنید و دوباره امتحان کنید',
                        [
                            'id'                 => 'atlas_message',
                            'type'               => 'error',
                            'additional_classes' => [ '' ],
                            'dismissible'        => true,
                         ]
                    );
                }

            }
        }
    }

    add_submenu_page(
        'edit.php?post_type=institute',         // اسلاگ پست تایپ
        'نظرات موسسات',              // عنوان صفحه
        'نظرات موسسات',              // عنوان منو
        'manage_options',                       // سطح دسترسی
        'edit-comments.php?post_type=institute' // لینک صفحه نظرات اختصاصی
    );
}
