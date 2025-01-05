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
        'نصرالله',
        'نصرالله',
        'manage_options',
        'nasr',
        'atlas_menu_callback',
        'dashicons-hammer',
        55
    );

    add_submenu_page(
        'nasr',
        'لیست',
        'لیست',
        'manage_options',
        'nasr',
        'atlas_menu_callback',
    );

    function atlas_menu_callback()
    {
        $atlas_option = atlas_start_working();

        $nasrListTable = new List_Table;
        $nasrdb = new NasrDB();

        $par_page = 25;

        $offset = (isset($_GET[ 'paged' ])) ? ($par_page * absint($_GET[ 'paged' ])) - 1 : 0;

        $status = (isset($_GET[ 'status' ]) && $_GET[ 'status' ] != "all") ? sanitize_text_field($_GET[ 'status' ]) : "";

        $all_results = $nasrdb->select($par_page, $offset, $status);

        $row = [
            'all_results' => $all_results, //all_results array
            'par_page' => $par_page, //par_page
            'nasrdb' => $nasrdb->num(), //numsql
            'offset' => $offset, //start at m-1
         ];

        require_once ATLAS_VIEWS . 'list.php';

    }

    $setting_suffix = add_submenu_page(
        'nasr',
        'تنظیمات',
        'تنظیمات',
        'manage_options',
        'setting_panels',
        'setting_panels',
    );

    function setting_panels()
    {
        $atlas_option = atlas_start_working();

        require_once ATLAS_VIEWS . 'setting.php';

    }

    $sms_panels_suffix = add_submenu_page(
        'nasr',
        'تنظیمات پنل پیامک',
        'تنظیمات پنل پیامک',
        'manage_options',
        'sms_panels',
        'atlas_sms_panels',
    );

    function atlas_sms_panels()
    {
        $atlas_option = atlas_start_working();

        require_once ATLAS_VIEWS . 'setting_sms_panels.php';

    }

    add_action('load-' . $menu_suffix, 'atlas__submit');
    add_action('load-' . $setting_suffix, 'atlas__submit');
    add_action('load-' . $sms_panels_suffix, 'atlas__submit');

    function atlas__submit()
    {

        if (isset($_POST[ 'atlas_act' ]) && $_POST[ 'atlas_act' ] == 'atlas__submit') {

            if (wp_verify_nonce($_POST[ '_wpnonce' ], 'atlas_nonce' . get_current_user_id())) {
                if (isset($_POST[ 'tsms' ])) {
                    $_POST[ 'tsms' ] = array_map('sanitize_text_field', $_POST[ 'tsms' ]);
                }
                if (isset($_POST[ 'ghasedaksms' ])) {
                    $_POST[ 'ghasedaksms' ] = array_map('sanitize_text_field', $_POST[ 'ghasedaksms' ]);
                }

                atlas_update_option($_POST);

                set_transient('success_mat', 'تغییر با موفقیت ثبت شد');

            } else {
                set_transient('error_mat', 'ذخیره سازی به مشکل خورده دوباره تلاش کنید');

            }

        }

    }

}
