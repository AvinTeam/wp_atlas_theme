<?php
(defined('ABSPATH')) || exit;

add_action('admin_menu', 'mph_admin_menu');

/**
 * Fires before the administration menu loads in the admin.
 *
 * @param string $context Empty context.
 */
function mph_admin_menu(string $context): void
{

    $menu_suffix = add_menu_page(
        'اطلس',
        'اطلس',
        'manage_options',
        'province',
        'atlas_menu_callback',
        'dashicons-hammer',
        55
    );

    add_submenu_page(
        'nasr',
        'استان ها',
        'استان ها',
        'manage_options',
        'province',
        'atlas_menu_callback',
    );

    function atlas_menu_callback()
    {

        $iran = new Iran_Area();

        require_once ATLAS_VIEWS . 'menu/list.php';

    }

    // $setting_suffix = add_submenu_page(
    //     'nasr',
    //     'تنظیمات',
    //     'تنظیمات',
    //     'manage_options',
    //     'setting_panels',
    //     'setting_panels',
    // );

    // function setting_panels()
    // {
    //     $atlas_option = atlas_start_working();

    //     require_once ATLAS_VIEWS . 'setting.php';

    // }

    // $sms_panels_suffix = add_submenu_page(
    //     'nasr',
    //     'تنظیمات پنل پیامک',
    //     'تنظیمات پنل پیامک',
    //     'manage_options',
    //     'sms_panels',
    //     'atlas_sms_panels',
    // );

    // function atlas_sms_panels()
    // {
    //     $atlas_option = atlas_start_working();

    //     require_once ATLAS_VIEWS . 'setting_sms_panels.php';

    // }

    add_action('load-' . $menu_suffix, 'atlas__submit');
    // add_action('load-' . $setting_suffix, 'atlas__submit');
    // add_action('load-' . $sms_panels_suffix, 'atlas__submit');

    function atlas__submit()
    {

        if (isset($_POST[ 'atlas_act' ]) && $_POST[ 'atlas_act' ] == 'atlas__submit') {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'atlas_nonce' . get_current_user_id())) {

                $iran = new Iran_Area();

                $data = [ 'description' => wp_kses_post(wp_unslash(nl2br($_REQUEST[ 'description' ]))) ];
                $where = [ 'id' => $_REQUEST[ 'province' ] ];
                $format = [ '%s' ];
                $where_format = [ '%d' ];

                $res = $iran->update($data, $where, $format, $where_format);
          
                wp_admin_notice(
                   'تغییر شما با موفقیت ثبت شد',
                    array(
                        'id'                 => 'message',
                        'type'               => 'success',
                        'additional_classes' => array( 'updated' ),
                        'dismissible'        => true,
                    )
                );









            } else {
                set_transient('error_mat', 'ذخیره سازی به مشکل خورده دوباره تلاش کنید');

            }

        }

    }

}
