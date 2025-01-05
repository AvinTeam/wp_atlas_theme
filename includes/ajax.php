<?php

add_action('wp_ajax_atlas_get_city', 'atlas_get_city');
add_action('wp_ajax_nopriv_atlas_get_city', 'atlas_get_city');

function atlas_get_city()
{

    $city_row = '<option  value="0" selected>انتخاب شهرستان</option>';

    if ($_POST[ 'type' ] == 'city' && absint($_POST[ 'ostanId' ])) {

        $irandb = new Iran_Area;
        $ostan = $irandb->select(absint($_POST[ 'ostanId' ]));

        foreach ($ostan as $value) {
            if ($value->province_id == absint($_POST[ 'ostanId' ])) {

                $city_row .= '<option value="' . $value->id . '">' . $value->name . '</option>' . PHP_EOL;

            }

        }

        wp_send_json_success($city_row);

    }

}

add_action('wp_ajax_atlas_sent_sms', 'atlas_sent_sms');
add_action('wp_ajax_nopriv_atlas_sent_sms', 'atlas_sent_sms');

function atlas_sent_sms()
{

    if (wp_verify_nonce($_POST[ 'wpnonce' ], 'atlas_login_page' . atlas_cookie())) {
        if ($_POST[ 'mobileNumber' ] !== "") {
            $mobile = sanitize_text_field($_POST[ 'mobileNumber' ]);

            $nasrdb = new NasrDB();
            $mobile_num = $nasrdb->num($mobile);

            if (!absint($mobile_num)) {

                $atlas_send_sms = atlas_send_sms($mobile, 'otp');

                if ($atlas_send_sms[ 'code' ] == 1) {
                    wp_send_json_success($atlas_send_sms[ 'massage' ]);
                }
                wp_send_json_error($atlas_send_sms[ 'massage' ], 403);

            } else {
                wp_send_json_error('شماره شما قبلا ثبت شده است', 403);

            }

        } else {
            wp_send_json_error('شماره شما به درستی وارد نشده است', 403);

        }
    } else {
        wp_send_json_error('لطفا یکبار صفحه را بروزرسانی کنید', 403);

    }

}

add_action('wp_ajax_atlas_sent_verify', 'atlas_sent_verify');
add_action('wp_ajax_nopriv_atlas_sent_verify', 'atlas_sent_verify');

function atlas_sent_verify()
{
    $mobile = sanitize_phone($_POST[ 'mobileNumber' ]);

    if (wp_verify_nonce($_POST[ 'wpnonce' ], 'atlas_login_page' . atlas_cookie())) {

        if ($_POST[ 'mobileNumber' ] !== "" && $_POST[ 'otpNumber' ] !== "") {

            $otp = sanitize_text_field($_POST[ 'otpNumber' ]);

            // دریافت کد ذخیره‌شده
            $saved_otp = get_transient('otp_' . $mobile);

            if (!$saved_otp || $saved_otp !== $otp) {
                wp_send_json_error('کد تأیید اشتباه یا منقضی شده است. ', 403);
            } else {
                set_transient('atlas_transient', '<div id="alert_danger" class="alert alert-success" role="alert">درخواست شما با موفقیت ثبت شد</div>');
                delete_transient('otp_' . $mobile);
                wp_send_json_success('');
            }
        }
    } else {
        delete_transient('otp_' . $mobile);

        wp_send_json_error('لطفا یکبار صفحه را بروزرسانی کنید', 403);

    }
    wp_send_json_error('لطفا دوباره تلاش کنید', 403);
}

add_action('wp_ajax_atlas_get_count', 'atlas_get_count');
add_action('wp_ajax_nopriv_atlas_get_count', 'atlas_get_count');

function atlas_get_count()
{
    $atlas_option = get_option('atlas_option');
    $nasrdb = new NasrDB();
    $num = $nasrdb->num('', 'all');
    $num += $atlas_option[ 'start_signature' ];

    if ($num > intval($_POST[ 'nowCount' ])) {
        wp_send_json_success($num);

    } else {
        wp_send_json_error(intval($_POST[ 'nowCount' ]));
    }

}

add_action('wp_ajax_atlas_update_row', 'atlas_update_row');

function atlas_update_row()
{
    $nasrdb = new NasrDB();

    if (intval($_POST[ 'dataId' ]) && in_array($_POST[ 'dataType' ], [ 'successful', 'failed', 'delete' ])) {

        if (sanitize_text_field($_POST[ 'dataType' ]) == 'delete') {

            $delete_row = $nasrdb->delete(intval($_POST[ 'dataId' ]));
            if ($delete_row) {

                wp_send_json_success($delete_row);

            } else {
                wp_send_json_error('حذف انجام نشد', 403);

            }

        } else {
            $data = [ 'status' => sanitize_text_field($_POST[ 'dataType' ]) ];
            $where = [ 'ID' => intval($_POST[ 'dataId' ]) ];
            $format = [ '%s' ];
            $where_format = [ '%d' ];

            $rest_update = $nasrdb->update($data, $where, $format, $where_format);
            if ($rest_update) {
                wp_send_json_success($rest_update);

            } else {
                wp_send_json_error('بروزرسانی انجام نشد', 403);

            }
        }

    } else {
        wp_send_json_error('خطا در ارسال اطلاعات', 403);
    }

}

add_action('wp_ajax_nast_loadSignature', 'nast_loadSignature');
add_action('wp_ajax_nopriv_nast_loadSignature', 'nast_loadSignature');

function nast_loadSignature()
{

    $nasrdb = new NasrDB();

    $atlas_option = get_option('atlas_option');

    $par_page = $atlas_option[ 'show_signature' ];
    $offset = $par_page * (absint($_POST[ 'thisPage' ]) - 1);

    $status = 'successful';

    $all_results = $nasrdb->select($par_page, $offset, $status, $_POST[ 'date' ]);

    $num = $nasrdb->num('', $status);

    $end = (($offset + $par_page >= $num)) ? 'end' : '';

    wp_send_json_success([
        'results' => $all_results,
        'end' => $end,

     ]);
}
