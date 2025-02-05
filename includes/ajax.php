<?php

add_action('wp_ajax_atlas_get_city', 'atlas_get_city');
add_action('wp_ajax_nopriv_atlas_get_city', 'atlas_get_city');

function atlas_get_city()
{

    $city_row = '<option  value="0" selected>انتخاب شهرستان</option>';

    if ($_POST[ 'type' ] == 'city' && absint($_POST[ 'ostanId' ])) {

        $irandb = new Iran_Area;
        $ostan  = $irandb->select(absint($_POST[ 'ostanId' ]));

        foreach ($ostan as $value) {
            if ($value->province_id == absint($_POST[ 'ostanId' ])) {

                $city_row .= '<option value="' . $value->id . '">' . $value->name . '</option>' . PHP_EOL;

            }

        }

        wp_send_json_success($city_row);

    }

}

add_action('wp_ajax_atlas_delete_city', 'handle_atlas_delete_city');

function handle_atlas_delete_city()
{

    if (current_user_can('manage_options')) {
        check_ajax_referer('ajax-nonce', 'nonce');

        $iran = new Iran_Area();

        $res = $iran->delete(absint($_POST[ 'city_id' ]));

        if ($res) {
            wp_send_json_success($_POST);

        }
    }
    wp_send_json_error('حذف انجام نشد', 403);

}

add_action('wp_ajax_nopriv_atlas_sent_sms', 'atlas_sent_sms');

function atlas_sent_sms()
{

    if (wp_verify_nonce($_POST[ 'nonce' ], 'ajax-nonce' . atlas_cookie())) {
        if ($_POST[ 'mobileNumber' ] !== "") {
            $mobile = sanitize_text_field($_POST[ 'mobileNumber' ]);

            $args = [
                'post_type'      => 'institute',
                'post_status'    => [ 'pending', 'publish', 'draft' ],
                'meta_query'     => [
                    [
                        'key'     => '_atlas_responsible-mobile',
                        'value'   => $mobile,
                        'compare' => '=',
                     ],
                 ],
                'fields'         => 'ids',
                'posts_per_page' => -1,
             ];

            $query      = new WP_Query($args);
            $post_count = $query->found_posts;

            if ($post_count >= 1) {

                $atlas_send_sms = atlas_send_sms($mobile, 'otp');

                if ($atlas_send_sms[ 'code' ] == 1) {
                    wp_send_json_success($atlas_send_sms[ 'massage' ]);
                }
                wp_send_json_error($atlas_send_sms[ 'massage' ], 403);

            }
            wp_send_json_error('شما مجاز به وارد شوید', 403);

        }
        wp_send_json_error('شماره شما به درستی وارد نشده است', 403);

    } else {
        wp_send_json_error('لطفا یکبار صفحه را بروزرسانی کنید', 403);

    }

}

add_action('wp_ajax_nopriv_atlas_sent_verify', 'atlas_sent_verify');

function atlas_sent_verify()
{
    if (wp_verify_nonce($_POST[ 'nonce' ], 'ajax-nonce' . atlas_cookie())) {

        if ($_POST[ 'mobileNumber' ] !== "" && $_POST[ 'otpNumber' ] !== "") {

            $mobile = sanitize_text_field($_POST[ 'mobileNumber' ]);
            $otp    = sanitize_text_field($_POST[ 'otpNumber' ]);

            // دریافت کد ذخیره‌شده
            $saved_otp = get_transient('otp_' . $mobile);

            if (! $saved_otp || $saved_otp !== $otp) {
                wp_send_json_error('کد تأیید اشتباه یا منقضی شده است. ', 403);
            } else {

                $user_query = new WP_User_Query([
                    'meta_key'   => 'mobile',
                    'meta_value' => $mobile,
                    'number'     => 1,
                 ]);

                if (! empty($user_query->get_results())) {
                    $user = $user_query->get_results()[ 0 ];
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID, true);

                    $massage = 'خوش آمدید، شما وارد شدید!';

                } else {

                    $args = [
                        'post_type'      => 'institute',
                        'post_status'    => [ 'pending', 'publish', 'draft' ],
                        'meta_query'     => [
                            [
                                'key'     => '_atlas_responsible-mobile',
                                'value'   => $mobile,
                                'compare' => '=',
                             ],
                         ],
                        'fields'         => 'ids',
                        'posts_per_page' => -1,
                     ];

                    $query = new WP_Query($args);

                    $post_count = $query->found_posts;
                    if ($post_count >= 1 && $query->have_posts()) {

                        $post_id = $query->posts[ 0 ];

                        $full_name = get_post_meta($post_id, '_atlas_responsible', true);

                        $user_id = wp_create_user($mobile, wp_generate_password(), $mobile . '@example.com');

                        if (! is_wp_error($user_id)) {
                            update_user_meta($user_id, 'nickname', $full_name);
                            update_user_meta($user_id, 'mobile', $mobile);
                            update_user_meta($user_id, 'first_name', $full_name);

                            $user = new WP_User($user_id);
                            $user->set_role('responsible');

                            wp_update_user([
                                'ID'           => $user_id,
                                'display_name' => $full_name,
                             ]);

                            wp_set_current_user($user_id);
                            wp_set_auth_cookie($user_id, true);

                            while ($query->have_posts()) {
                                $query->the_post();

                                $post_data = [
                                    'ID'          => get_the_ID(),
                                    'post_author' => $user_id,
                                 ];

                                update_post_meta(get_the_ID(), '_atlas_responsible', $full_name);

                                wp_update_post($post_data, true);

                            }

                            $massage = 'ثبت‌ نام با موفقیت انجام شد و شما وارد شدید!';
                        }

                    }

                }

                delete_transient('otp_' . $mobile);

                wp_send_json_success($massage);

            }
        }
    } else {
        wp_send_json_error('لطفا یکبار صفحه را بروزرسانی کنید', 403);

    }
    wp_send_json_error('لطفا دوباره تلاش کنید', 403);

}

// add_action('wp_ajax_atlas_update_row', 'atlas_update_row');

// function atlas_update_row()
// {
//     $nasrdb = new NasrDB();

//     if (intval($_POST[ 'dataId' ]) && in_array($_POST[ 'dataType' ], [ 'successful', 'failed', 'delete' ])) {

//         if (sanitize_text_field($_POST[ 'dataType' ]) == 'delete') {

//             $delete_row = $nasrdb->delete(intval($_POST[ 'dataId' ]));
//             if ($delete_row) {

//                 wp_send_json_success($delete_row);

//             } else {
//                 wp_send_json_error('حذف انجام نشد', 403);

//             }

//         } else {
//             $data = [ 'status' => sanitize_text_field($_POST[ 'dataType' ]) ];
//             $where = [ 'ID' => intval($_POST[ 'dataId' ]) ];
//             $format = [ '%s' ];
//             $where_format = [ '%d' ];

//             $rest_update = $nasrdb->update($data, $where, $format, $where_format);
//             if ($rest_update) {
//                 wp_send_json_success($rest_update);

//             } else {
//                 wp_send_json_error('بروزرسانی انجام نشد', 403);

//             }
//         }

//     } else {
//         wp_send_json_error('خطا در ارسال اطلاعات', 403);
//     }

// }
