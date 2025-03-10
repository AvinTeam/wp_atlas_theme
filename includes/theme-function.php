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
            // if ($view == 'dashboard' && ! isset($_GET[ 'profile' ])) {
            //     $this_user = wp_get_current_user();

            //     if (! $this_user->first_name || ! $this_user->last_name) {

            //         wp_redirect(atlas_base_url('panel/?profile'));
            //         exit;

            //     }
            // }

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
        return ATLAS_JS . $path . '?ver=' . ATLAS_VERSION;
    }

    function atlas_panel_css($path)
    {
        return ATLAS_CSS . $path . '?ver=' . ATLAS_VERSION;
    }

    function atlas_panel_image($path)
    {
        return ATLAS_IMAGE . $path . '?ver=' . ATLAS_VERSION;
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

    function atlas_cookie(): string
    {

        if (! is_user_logged_in()) {
            if (! isset($_COOKIE[ "setcookie_atlas_nonce" ])) {

                $is_key_cookie = wp_generate_password(15);
                ob_start();

                setcookie("setcookie_atlas_nonce", $is_key_cookie, time() + 1800, "/");
                ob_end_flush();
            } else {
                $is_key_cookie = $_COOKIE[ "setcookie_atlas_nonce" ];
            }
        } else {

            $is_key_cookie = get_current_user_id();

        }
        return $is_key_cookie;
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

 

function tarikh($data, $type = '')
{


    $data_array = explode(" ", $data);


    $data = $data_array[ 0 ];
    $time = (sizeof($data_array) >= 2) ? $data_array[ 1 ] : 0;


    $has_mode = (strpos($data, '-')) ? '-' : '/';


    list($y, $m, $d) = explode($has_mode, $data);


    $ch_date = (strpos($data, '-')) ? gregorian_to_jalali($y, $m, $d, '/') : jalali_to_gregorian($y, $m, $d, '-');


    $has_mode = (strpos($ch_date, '-')) ? '-' : '/';


    list($y, $m, $d) = explode($has_mode, $ch_date);
    if ($m < 10) {$m = '0' . $m;}
    if ($d < 10) {$d = '0' . $d;}


    $ch_date = $y . $has_mode . $m . $has_mode . $d;


    if ($type == 'time') {
        $new_date = $time;
    } elseif ($type == 'date') {
        $new_date = $ch_date;
    } else {
        $new_date = ($time === 0) ? $ch_date : $ch_date . ' ' . $time;
    }


    return $new_date;


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

        return $path_array[ 0 ] . $url;
    }

    function paginate($total_pages, $current_page)
    {

        $output = '';

        // // محاسبه صفحات قابل نمایش
        // $start = max(1, $current_page - 2);
        // $end   = min($total_pages, $current_page + 2);

        // if ($current_page >= 4) {
        //     $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', 1)) . "'>1</a>";

        //     if ($current_page >= 5) {
        //         $output .= '...';
        //     }

        // }

        // if ($start > 5) {

        //     for ($i = 1; $i <= 5; $i++) {
        //         if ($i == $current_page) {
        //             $output .= "<span  class='current rounded-circle d-block text-center'>$i</span>";
        //         } else {
        //             $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', $i)) . "'>$i</a>";
        //         }
        //     }

        //     $output .= '...';

        // }

        // // نمایش صفحات
        // for ($i = $start; $i <= $end; $i++) {
        //     if ($i == $current_page) {
        //         $output .= "<span class='current rounded-circle d-block text-center'>$i</span>";
        //     } else {
        //         $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', $i)) . "'>$i</a>";
        //     }
        // }

        // $end_start = $total_pages - 4;

        // // اگر صفحه فعلی نزدیک انتهای صفحات باشد
        // if ($total_pages - $start >= 10) {

        //     $output .= '...';

        //     for ($i = $end_start; $i <= $total_pages; $i++) {
        //         $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', $i)) . "'>$i</a>";

        //     }
        // } else {
        //     for ($i = $end + 1; $i <= $total_pages; $i++) {
        //         $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', $i)) . "'>$i</a>";

        //     }
        // }

        // if ($current_page >= 4) {

        //     if ($current_page >= 5) {
        //         $output .= '...';
        //     }

        //     $output .= "<a class='d-block text-center' href='" . esc_url(atlas_end_url('page', 1)) . "'>1</a>";

        // }

        $start = $current_page - 1;

        if ($start >= 1) {
            $output .= "<a class='d-block text-center text-primary' href='" . esc_url(atlas_end_url('page', $start)) . "'>$start</a>";
        }

        $output .= "<span  class='current rounded-circle d-block text-center text-white bg-primary'> $current_page</span>";

        $next = $current_page + 1;
        if ($total_pages >= $next) {
            $output .= "<a class='d-block text-center text-primary' href='" . esc_url(atlas_end_url('page', $next)) . "'>$next</a>";
        }

        return $output;
    }
    function atlas_to_enghlish($text)
    {

        $western = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ];
        $persian = [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ];
        $arabic  = [ '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' ];
        $text    = str_replace($persian, $western, $text);
        $text    = str_replace($arabic, $western, $text);
        return $text;

    }
    function atlas_transient()
    {
        $atlas_transient = get_transient('atlas_transient');

        if ($atlas_transient) {
            delete_transient('atlas_transient');
            return $atlas_transient;
        }

    }

    function is_mobile($mobile)
    {
        $pattern = '/^(\+98|0)?9\d{9}$/';
        return preg_match($pattern, $mobile);
    }

    function atlas_upload_file($file, int $oldfile = 0, int $post_id = 0)
    {

        $massage = '';

        // پردازش و ذخیره عکس
        if (! empty($file[ 'name' ])) {

            $maxFileSize = 2048 * 1024 * 1024; // 10MB

            if ($file[ 'size' ] > $maxFileSize) {
                $massage .= '<div class="alert alert-danger" role="alert">خطایی در زمان بارگزاری رخ داده لطفا دوباره تلاش کنید.</div>';
            } else {

                if (! function_exists('wp_handle_upload')) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }
                $uploadedfile = $file;

                $upload_overrides = [ 'test_form' => false ];

                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                if ($movefile && ! isset($movefile[ 'error' ])) {

                    $wp_upload_dir = wp_upload_dir();
                    $attachment    = [
                        'guid'           => $wp_upload_dir[ 'url' ] . '/' . basename($movefile[ 'file' ]),
                        'post_mime_type' => $movefile[ 'type' ],
                        'post_title'     => preg_replace('/\.[^.]+$/', '', basename($movefile[ 'file' ])),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                     ];

                    $attach_id = wp_insert_attachment($attachment, $movefile[ 'file' ], $post_id);
                    require_once ABSPATH . 'wp-admin/includes/image.php';
                    $attach_data = wp_generate_attachment_metadata($attach_id, $movefile[ 'file' ]);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    if (absint($oldfile)) {
                        wp_delete_attachment($oldfile, true);
                    }

                } else {
                    $massage .= '<div class="alert alert-danger" role="alert">خطایی در زمان بارگزاری رخ داده لطفا دوباره تلاش کنید.</div>';

                }
            }
        } else {
            $massage .= '<div class="alert alert-danger" role="alert">لطفاً یک فایل انتخاب کنید.</div>';

        }

        return ($massage == '') ? [ 'code' => 1, 'massage' => $attach_id ] : [ 'code' => 0, 'massage' => $massage ];

    }

    function sanitize_text_no_item($item)
    {
        $new_item = [  ];

        foreach ($item as $value) {
            if (empty($value)) {continue;}
            $new_item[  ] = sanitize_text_field($value);
        }

        return $new_item;

    }
    
    function get_center_type($center_type)
    {

        switch ($center_type) {
            case 'Institute':
                $center_type = 'موسسه';
                break;
            case 'house_of_quran':
                $center_type = 'خانه قرآن';
                break;
            case 'mohfel':
                $center_type = 'محفل زندگی با آیه ها';
                break;
            case 'education':
                $center_type = 'آموزش پرورش';
                break;
            case 'besij':
                $center_type = 'پایگاه قرآنی مساجد';
                break;
            case 'home':
                $center_type = 'جلسات خانگی';
                break;
            default:
                $center_type = 'نامشخص';

                break;
        }

        return $center_type;
    }


    function display_commenters_list($comments)
    {

        if (! empty($comments)) {
            foreach ($comments as $comment) {
                $author_name     = $comment->comment_author;
                $comment_content = $comment->comment_content;
            $rating          = get_comment_meta($comment->comment_ID, 'rating', true); ?>

<div class="d-flex flex-column bg-body rounded p-3 mt-3">
    <div class="p-3 d-flex flex-row justify-content-between align-items-center">
        <b><?php echo $author_name ?></b>
        <div class=" rating-stars" style="direction: ltr;">
            <?php
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