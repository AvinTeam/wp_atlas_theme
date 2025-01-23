<?php

    (defined('ABSPATH')) || exit;

    function atlas_template_path($atlas_page = false)
    {
        if (! $atlas_page) {return;}

        if ($atlas_page) {
            $atlas_page = ($_SERVER[ 'REQUEST_METHOD' ] === 'POST' && isset($_POST[ 'act_user' ]) && $_POST[ 'act_user' ] == 'form_submit') ? 'panel' : $atlas_page;
            $page       = explode('=', $atlas_page);

            switch ($page[ 0 ]) {
                case 'city':
                    $view = 'city';
                    break;
                case 'province':
                    $view = 'city';
                case 'all':
                    $view = 'city';
                case 'search':
                    $view = 'city';
                    break;
                case 'logout':
                    $view = 'logout';
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

    function atlas_base_url($base = '')
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
                'code'   => 1,
                'result' => $res->get_error_message(),
             ];
        } else {
            $result = [
                'code'   => 0,
                'result' => json_decode($res[ 'body' ]),
             ];
        }

        return $result;
    }

    function atlas_start_working(): array
    {

        if (! isset($_GET[ 'avin_cron' ])) {
            atlas_cookie();
        }
        $atlas_option = get_option('atlas_option');

        if (! isset($atlas_option[ 'version' ]) || version_compare(ATLAS_VERSION, $atlas_option[ 'version' ], '>')) {

            update_option(
                'atlas_option',
                [
                    'version'           => ATLAS_VERSION,
                    'tsms'              => (isset($atlas_option[ 'tsms' ])) ? $atlas_option[ 'tsms' ] : [ 'username' => '', 'password' => '', 'number' => '' ],
                    'ghasedaksms'       => (isset($atlas_option[ 'ghasedaksms' ])) ? $atlas_option[ 'ghasedaksms' ] : [ 'ApiKey' => '', 'number' => '' ],
                    'sms_text_otp'      => (isset($atlas_option[ 'sms_text_otp' ])) ? $atlas_option[ 'sms_text_otp' ] : 'کد تأیید شما: %otp%',
                    'set_timer'         => (isset($atlas_option[ 'set_timer' ])) ? $atlas_option[ 'set_timer' ] : 1,
                    'set_code_count'    => (isset($atlas_option[ 'set_code_count' ])) ? $atlas_option[ 'set_code_count' ] : 4,
                    'sms_type'          => (isset($atlas_option[ 'sms_type' ])) ? $atlas_option[ 'sms_type' ] : 'tsms',
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
            'version'           => ATLAS_VERSION,
            'tsms'              => (isset($data[ 'tsms' ])) ? $data[ 'tsms' ] : $atlas_option[ 'tsms' ],
            'ghasedaksms'       => (isset($data[ 'ghasedaksms' ])) ? $data[ 'ghasedaksms' ] : $atlas_option[ 'ghasedaksms' ],
            'set_timer'         => (isset($data[ 'set_timer' ])) ? absint($data[ 'set_timer' ]) : $atlas_option[ 'set_timer' ],
            'set_code_count'    => (isset($data[ 'set_code_count' ])) ? absint($data[ 'set_code_count' ]) : $atlas_option[ 'set_code_count' ],
            'sms_text_otp'      => (isset($data[ 'sms_text_otp' ])) ? sanitize_textarea_field($data[ 'sms_text_otp' ]) : $atlas_option[ 'sms_text_otp' ],
            'sms_type'          => (isset($data[ 'sms_type' ])) ? sanitize_text_field($data[ 'sms_type' ]) : $atlas_option[ 'sms_type' ],
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
        $massage      = $finalMessage . PHP_EOL . $server_name;

        return $massage;

    }

    function notificator($mobile, $massage)
    {
        global $atlas_option;

        $data = [
            'to'   => $atlas_option[ 'notificator_token' ],
            'text' => $mobile . PHP_EOL . $massage,
         ];

        // درخواست POST با wp_remote_post
        $response = wp_remote_post('https://notificator.ir/api/v1/send', [
            'body' => $data,
         ]);

        $result = json_decode(wp_remote_retrieve_body($response));

        $result = [
            'code'    => $result->success,
            'massage' => ($result->success) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است ',
         ];

        return $result;

    }

    function tsms($mobile, $massage)
    {

        global $atlas_option;

        $msg_array = [ $massage ];

        $data = [
            'method'     => 'sendSms',
            'username'   => $atlas_option[ 'tsms' ][ 'username' ],
            'password'   => $atlas_option[ 'tsms' ][ 'password' ],
            'sms_number' => [ $atlas_option[ 'tsms' ][ 'number' ] ],
            'mobile'     => [ $mobile ],
            'msg'        => $msg_array,
            'mclass'     => [ '' ],
            'messagid'   => rand(),
         ];

        $response = wp_remote_post('https://www.tsms.ir/json/json.php', [
            'body' => http_build_query($data),
         ]);

        $response = json_decode(wp_remote_retrieve_body($response));

        $result = [
            'code'    => ($response->code == 200) ? 1 : $response->code,
            'massage' => ($response->code == 200) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است',
         ];
        return $result;

    }

    function ghasedaksms($mobile, $massage)
    {

        global $atlas_option;
        $data = [
            'message'  => $massage,
            'sender'   => $atlas_option[ 'ghasedaksms' ][ 'number' ],
            'receptor' => $mobile,
         ];
        $header = [
            'ApiKey' => $atlas_option[ 'ghasedaksms' ][ 'ApiKey' ],
         ];

        $response = wp_remote_post('http://api.ghasedaksms.com/v2/sms/send/bulk2', [
            'headers' => $header,
            'body'    => http_build_query($data),
         ]);

        $response = json_decode(wp_remote_retrieve_body($response));

        $result = [
            'code'    => ($response->result == 'success' && strlen($response->messageids) > 5) ? 1 : $response->messageids,
            'massage' => ($response->result == 'success' && strlen($response->messageids) > 5) ? 'پیام با موفقیت ارسال شد' : 'پیام به خطا خورده است',
         ];
        return $result;

    }

    function atlas_send_sms($mobile, $type, $data = [  ])
    {

        global $atlas_option;
        $massage = '';

        $result = [
            'code'    => 0,
            'massage' => $mobile,
         ];

        // بررسی فرمت شماره موبایل
        if (! preg_match('/^09[0-9]{9}$/', $mobile)) {
            $result = [
                'code'    => -1,
                'massage' => 'شماره موبایل معتبر نیست.',
             ];
        }

        if ($type == 'otp') {
            if (get_transient('otp_' . $mobile)) {
                $result = [
                    'code'    => -2,
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

        if (! isset($_COOKIE[ "setcookie_atlas_nonce" ])) {

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
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // اعداد و حروف
        $charactersLength = strlen($characters);
        $randomString     = '';
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
        if (! empty($data)) {
            $arr  = explode(" ", $data);
            $data = $arr[ 0 ];

            $arrayData = [ '/', '-' ];

            foreach ($arrayData as $arrayData) {
                $x = explode($arrayData, $data);
                if (sizeof($x) == 3) {

                    list($gy, $gm, $gd) = explode($arrayData, $data);

                    if ($arrayData == '/') {
                        $tagir = '-';
                        $chen  = 'jalali_to_gregorian';
                    }
                    if ($arrayData == '-') {
                        $tagir = '/';
                        $chen  = 'gregorian_to_jalali';
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

    function get_name_by_id($data, $id)
    {
        $filtered = array_filter($data, function ($item) use ($id) {
            return $item->id == $id;
        });

        // برگرداندن اولین مقدار پیدا شده
        if (! empty($filtered)) {
            return array_values($filtered)[ 0 ]->name;
        }
        return null;
    }

    function atlas_page_item($item)
    {
        return '<div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">' . $item . '</div>';
    }

    function get_current_relative_url()
    {
        // گرفتن مسیر فعلی بدون دامنه
        $path = esc_url_raw(wp_unslash($_SERVER[ 'REQUEST_URI' ]));

                                                    // حذف دامنه و فقط نگه داشتن مسیر نسبی + پارامترها
        $relative_url = strtok($path, '?');         // مسیر قبل از پارامترها
        $query_string = $_SERVER[ 'QUERY_STRING' ]; // پارامترهای GET

        // اگر پارامتر وجود داره، به مسیر اضافه کن
        if ($query_string) {
            $relative_url .= '?' . $query_string;
        }

        return $relative_url;
    }

    function atlas_end_url($item, $added)
    {

        $path       = esc_url_raw(wp_unslash($_SERVER[ 'REQUEST_URI' ]));
        $path_array = explode('?', $path);

        $url = '';

        $query_string = $_SERVER[ 'QUERY_STRING' ];
        $query_array  = explode('&', $query_string);

        $nat_has_in_query = true;
        foreach ($query_array as $index => $query) {
            $url .= ($index == 0) ? '?' : '&';

            $query = explode('=', $query);

            if ($query[ 0 ] == $item) {
                $query[ 1 ]       = $added;
                $nat_has_in_query = false;

            }
            $url .= implode('=', $query);

        }

        if ($url == '?') {$url = '';}

        if ($nat_has_in_query) {

            $url .= ($url == '') ? '?' : '&';

            $url .= $item . '=' . $added;
        }

        return home_url() . $path_array[ 0 ] . $url;
    }

    function paginate($total_pages, $current_page)
    {

        $output = '';

        // محاسبه صفحات قابل نمایش
        $start = max(1, $current_page - 2);
        $end   = min($total_pages, $current_page + 2);

        if ($start > 5) {

            for ($i = 1; $i <= 5; $i++) {
                if ($i == $current_page) {
                    $output .= "<span  class='current rounded-circle d-block text-center'>$i</span>";
                } else {
                    $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', $i)) . "'>$i</a>";
                }
            }

            $output .= '...';

        }

        // نمایش صفحات
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $current_page) {
                $output .= "<span class='current rounded-circle d-block text-center'>$i</span>";
            } else {
                $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', $i)) . "'>$i</a>";
            }
        }

        $end_start = $total_pages - 4;

        // اگر صفحه فعلی نزدیک انتهای صفحات باشد
        if ($total_pages - $start >= 10) {

            $output .= '...';

            for ($i = $end_start; $i <= $total_pages; $i++) {
                $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', $i)) . "'>$i</a>";

            }
        } else {
            for ($i = $end + 1; $i <= $total_pages; $i++) {
                $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', $i)) . "'>$i</a>";

            }
        }

        return $output;
    }

    function display_commenters_list($post_id)
    {
        // گرفتن نظرات مرتبط با پست
        $comments = get_comments([
            'post_id' => $post_id,
            'status'  => 'approve', // فقط نظرات تایید شده
         ]);

        if (! empty($comments)) {
            foreach ($comments as $comment) {
                $author_name     = $comment->comment_author;
                $comment_content = $comment->comment_content;
                $rating= get_comment_meta($comment->comment_ID, 'rating', true); ?>
                <div class="d-flex flex-column bg-body rounded p-3 mt-3">
                    <div class="p-3 d-flex flex-row justify-content-between align-items-center">
                        <b><?php echo $author_name ?></b>
                        <div class=" rating-stars" style="direction: ltr;"> <?php 
                            for ($i = 1; $i < 6; $i++) {
                                if (absint($rating) >= $i) {
                                    echo '<i class="bi bi-star-fill"></i>';
                                } else {
                                    echo '<i class="bi bi-star"></i>';
                                }
                            }?>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3 px-3 atlas-comment-content fw-bold">
                        <p><?php echo $comment_content ?></p>
                    </div>
                </div>       <?php
           }
        } else {
                echo '<p>هنوز نظری ثبت نشده است.</p>';
        }
    }