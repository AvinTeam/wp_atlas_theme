<?php

(defined('ABSPATH')) || exit;

function atlas_template_path($atlas_page = false)
{

    if (!$atlas_page) {return;}

    if ($atlas_page) {

        $page = explode('=', $atlas_page);

        switch ($page[ 0 ]) {
            case 'city':
                $view = 'city';
                break;
            case 'institute':
                $view = 'institute';
                break;
            case 'panel':
                $view = (is_user_logged_in()) ? 'dashboard' : 'login';
                break;
            default:
                $view = '404';
                break;
        }

        return ATLAS_VIEWS . 'page/' . $view . '.php';

    }

    return false;

}

function atlas_pane_base_url($base = '')
{
    return site_url() . '/' . ATLAS_PAGE_BASE . '/' . $base;

}

function atlas_panel_js($path)
{
    return ATLAS_JS . $path;
}

function atlas_panel_css($path)
{
    return ATLAS_CSS . $path;
}

function atlas_panel_image($path)
{
    return ATLAS_IMAGE . $path;
}

function atlas_remote(string $url)
{
    $res = wp_remote_get(
        $url,
        [
            'timeout' => 1000,
         ]);

    if (is_wp_error($res)) {
        $result = [
            'code' => 1,
            'result' => $res->get_error_message(),
         ];
    } else {
        $result = [
            'code' => 0,
            'result' => json_decode($res[ 'body' ]),
         ];
    }

    return $result;
}

function atlas_start_working(): array
{

    if (!isset($_GET[ 'avin_cron' ])) {
        atlas_cookie();
    }
    $atlas_option = get_option('atlas_option');

    if (!isset($atlas_option[ 'version' ]) || version_compare(ATLAS_VERSION, $atlas_option[ 'version' ], '>')) {

        update_option(
            'atlas_option',
            [
                'version' => ATLAS_VERSION,
                'tsms' => (isset($atlas_option[ 'tsms' ])) ? $atlas_option[ 'tsms' ] : [ 'username' => '', 'password' => '', 'number' => '' ],
                'ghasedaksms' => (isset($atlas_option[ 'ghasedaksms' ])) ? $atlas_option[ 'ghasedaksms' ] : [ 'ApiKey' => '', 'number' => '' ],
                'sms_text_otp' => (isset($atlas_option[ 'sms_text_otp' ])) ? $atlas_option[ 'sms_text_otp' ] : 'کد تأیید شما: %otp%',
                'set_timer' => (isset($atlas_option[ 'set_timer' ])) ? $atlas_option[ 'set_timer' ] : 1,
                'set_code_count' => (isset($atlas_option[ 'set_code_count' ])) ? $atlas_option[ 'set_code_count' ] : 4,
                'sms_type' => (isset($atlas_option[ 'sms_type' ])) ? $atlas_option[ 'sms_type' ] : 'tsms',
                'notificator_token' => (isset($atlas_option[ 'notificator_token' ])) ? $atlas_option[ 'notificator_token' ] : '',

             ]

        );

    }

    return get_option('atlas_option');

}

function atlas_update_option($data)
{

    $atlas_option = get_option('atlas_option');

    $atlas_option = [
        'version' => ATLAS_VERSION,
        'tsms' => (isset($data[ 'tsms' ])) ? $data[ 'tsms' ] : $atlas_option[ 'tsms' ],
        'ghasedaksms' => (isset($data[ 'ghasedaksms' ])) ? $data[ 'ghasedaksms' ] : $atlas_option[ 'ghasedaksms' ],
        'set_timer' => (isset($data[ 'set_timer' ])) ? absint($data[ 'set_timer' ]) : $atlas_option[ 'set_timer' ],
        'set_code_count' => (isset($data[ 'set_code_count' ])) ? absint($data[ 'set_code_count' ]) : $atlas_option[ 'set_code_count' ],
        'sms_text_otp' => (isset($data[ 'sms_text_otp' ])) ? sanitize_textarea_field($data[ 'sms_text_otp' ]) : $atlas_option[ 'sms_text_otp' ],
        'sms_type' => (isset($data[ 'sms_type' ])) ? sanitize_text_field($data[ 'sms_type' ]) : $atlas_option[ 'sms_type' ],
        'notificator_token' => (isset($data[ 'notificator_token' ])) ? sanitize_text_field($data[ 'notificator_token' ]) : $atlas_option[ 'notificator_token' ],

     ];

    update_option('atlas_option', $atlas_option);

}

function atlas_massage_otp($otp)
{
    global $atlas_option;

    $server_name = $_SERVER[ 'SERVER_NAME' ];

    $finalMessage = str_replace('%otp%', $otp, $atlas_option[ 'sms_text_otp' ]);

    //$massage = $finalMessage . PHP_EOL . "@" . $server_name . " #" . $otp;
    $massage = $finalMessage;

    return $massage;

}

function atlas_massage_format($data)
{
    global $atlas_option;
    $server_name = $_SERVER[ 'SERVER_NAME' ];

    $finalMessage = str_replace([ '%username%', '%password%', '%url%' ], $data, $atlas_option[ 'sms_text_format' ]);
    $massage = $finalMessage . PHP_EOL . $server_name;

    return $massage;

}

function notificator($mobile, $massage)
{
    global $atlas_option;

    $data = [
        'to' => $atlas_option[ 'notificator_token' ],
        'text' => $mobile . PHP_EOL . $massage,
     ];

    // درخواست POST با wp_remote_post
    $response = wp_remote_post('https://notificator.ir/api/v1/send', [
        'body' => $data,
     ]);

    $result = json_decode(wp_remote_retrieve_body($response));

    $result = [
        'code' => $result->success,
        'massage' => ($result->success) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است ',
     ];

    return $result;

}

function tsms($mobile, $massage)
{

    global $atlas_option;

    $msg_array = [ $massage ];

    $data = [
        'method' => 'sendSms',
        'username' => $atlas_option[ 'tsms' ][ 'username' ],
        'password' => $atlas_option[ 'tsms' ][ 'password' ],
        'sms_number' => array($atlas_option[ 'tsms' ][ 'number' ]),
        'mobile' => [ $mobile ],
        'msg' => $msg_array,
        'mclass' => array(''),
        'messagid' => rand(),
     ];

    $response = wp_remote_post('https://www.tsms.ir/json/json.php', [
        'body' => http_build_query($data),
     ]);

    $response = json_decode(wp_remote_retrieve_body($response));

    $result = [
        'code' => ($response->code == 200) ? 1 : $response->code,
        'massage' => ($response->code == 200) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است',
     ];
    return $result;

}

function ghasedaksms($mobile, $massage)
{

    global $atlas_option;
    $data = [
        'message' => $massage,
        'sender' => $atlas_option[ 'ghasedaksms' ][ 'number' ],
        'receptor' => $mobile,
     ];
    $header = [
        'ApiKey' => $atlas_option[ 'ghasedaksms' ][ 'ApiKey' ],
     ];

    $response = wp_remote_post('http://api.ghasedaksms.com/v2/sms/send/bulk2', [
        'headers' => $header,
        'body' => http_build_query($data),
     ]);

    $response = json_decode(wp_remote_retrieve_body($response));

    $result = [
        'code' => ($response->result == 'success' && strlen($response->messageids) > 5) ? 1 : $response->messageids,
        'massage' => ($response->result == 'success' && strlen($response->messageids) > 5) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است',
     ];
    return $result;

}

function atlas_send_sms($mobile, $type, $data = [  ])
{

    global $atlas_option;
    $massage = '';

    $result = [
        'code' => 0,
        'massage' => $mobile,
     ];

    // بررسی فرمت شماره موبایل
    if (!preg_match('/^09[0-9]{9}$/', $mobile)) {
        $result = [
            'code' => -1,
            'massage' => 'شماره موبایل معتبر نیست.',
         ];
    }

    if ($type == 'otp') {
        if (get_transient('otp_' . $mobile)) {
            $result = [
                'code' => -2,
                'massage' => 'لطفا چند دقیقه دیگر تلاش کنید.',
             ];
        }

        $otp = '';

        for ($i = 0; $i < $atlas_option[ 'set_code_count' ]; $i++) {
            $otp .= rand(0, 9);
        }
        set_transient('otp_' . $mobile, $otp, $atlas_option[ 'set_timer' ] * MINUTE_IN_SECONDS);

        if ($result[ 'code' ] == 0) {
            $result = $atlas_option[ 'sms_type' ]($mobile, atlas_massage_otp($otp));
            if ($result[ 'code' ] != 1) {
                delete_transient('otp_' . $mobile);

            }

        }
    }

    if ($type == 'foratlas_art') {
        $result = $atlas_option[ 'sms_type' ]($mobile, atlas_massage_format($data));

    }

    return $result;
}

function atlas_cookie(): string
{

    if (!isset($_COOKIE[ "setcookie_atlas_nonce" ])) {

        $is_key_cookie = atlas_rand_string(15);
        ob_start();

        setcookie("setcookie_atlas_nonce", $is_key_cookie, time() + 1800, "/");

        ob_end_flush();

        header("Refresh:0");
        exit;

    } else {
        $is_key_cookie = $_COOKIE[ "setcookie_atlas_nonce" ];
    }

    return $is_key_cookie;
}

function atlas_rand_string($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // اعداد و حروف
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[ rand(0, $charactersLength - 1) ];
    }
    return $randomString;
}

function atlas_mask_mobile($mobile)
{
    // بررسی طول شماره موبایل
    if (strlen($mobile) === 11) {
        $lastFour = substr($mobile, -4); // گرفتن 4 رقم آخر

        $masked = $lastFour . "*****" . substr($mobile, 0, 4);

        return $masked;
    }
    return "شماره موبایل نامعتبر است.";
}

function tarikh($data, $time = "")
{
    $data1 = "";
    if (!empty($data)) {
        $arr = explode(" ", $data);
        $data = $arr[ 0 ];

        $arrayData = [ '/', '-' ];

        foreach ($arrayData as $arrayData) {
            $x = explode($arrayData, $data);
            if (sizeof($x) == 3) {

                list($gy, $gm, $gd) = explode($arrayData, $data);

                if ($arrayData == '/') {
                    $tagir = '-';
                    $chen = 'jalali_to_gregorian';
                }
                if ($arrayData == '-') {
                    $tagir = '/';
                    $chen = 'gregorian_to_jalali';
                }

                $data1 = $chen($gy, $gm, $gd, $tagir);

                break;
            }

        }

        if ($time == "d") {
            $data1 = $data1;
        } elseif ($time == "t") {
            $data1 = $arr[ 1 ];
        } else {
            $data1 = $data1 . " " . $arr[ 1 ];
        }
    }
    return $data1;
}

function get_current_relative_url()
{
    // گرفتن مسیر فعلی بدون دامنه
    $path = esc_url_raw(wp_unslash($_SERVER[ 'REQUEST_URI' ]));

    // حذف دامنه و فقط نگه داشتن مسیر نسبی + پارامترها
    $relative_url = strtok($path, '?'); // مسیر قبل از پارامترها
    $query_string = $_SERVER[ 'QUERY_STRING' ]; // پارامترهای GET

    // اگر پارامتر وجود داره، به مسیر اضافه کن
    if ($query_string) {
        $relative_url .= '?' . $query_string;
    }

    return $relative_url;
}

function get_name_by_id($data, $id)
{
    $filtered = array_filter($data, function ($item) use ($id) {
        return $item->id == $id;
    });

    // برگرداندن اولین مقدار پیدا شده
    if (!empty($filtered)) {
        return array_values($filtered)[ 0 ]->name;
    }
    return null;
}
