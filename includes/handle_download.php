<?php

(defined('ABSPATH')) || exit;

add_action('admin_init', 'handle_download');

function handle_download()
{

    if (isset($_GET[ 'action' ])) {

        if ($_GET[ 'action' ] === 'art_exel') {

            $args = [
                'post_type'      => 'institute',
                'post_status'    => 'any',
                'posts_per_page' => -1,
             ];

            if (isset($_GET[ 's' ]) && ! empty($_GET[ 's' ])) {
                $args[ 's' ] = sanitize_text_field($_GET[ 's' ]);
            }

            if (isset($_GET[ 'format_art' ])) {
                $args[ 'tax_query' ][  ] = [
                    'taxonomy' => 'format_art',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET[ 'format_art' ]),
                 ];
            }

            $query = new WP_Query($args);

            $data   = [  ];
            $irandb = new Iran_Area;

            foreach ($query->posts as $post) {

                $responsible = get_post_meta($post->ID, '_atlas_responsible', true);
                $mobile      = get_post_meta($post->ID, '_atlas_responsible-mobile', true);
                $center_mode = get_post_meta($post->ID, '_atlas_center-mode', true);
                $center_type = get_post_meta($post->ID, '_atlas_center-type', true);
                $phone       = get_post_meta($post->ID, '_atlas_phone', true);
                $ostan       = get_post_meta($post->ID, '_atlas_ostan', true);
                $city        = get_post_meta($post->ID, '_atlas_city', true);
                $address     = get_post_meta($post->ID, '_atlas_address', true);
                $link        = get_post_meta($post->ID, '_atlas_link', true);
                $gender      = get_post_meta($post->ID, '_atlas_gender', true);
                $age         = get_post_meta($post->ID, '_atlas_age', true);
                $contacts    = get_post_meta($post->ID, '_atlas_contacts', true);
                $course_type = get_post_meta($post->ID, '_atlas_course-type', true);
                $subject     = get_post_meta($post->ID, '_atlas_subject', true);
                $coaches     = get_post_meta($post->ID, '_atlas_coaches', true);
                $teacher     = get_post_meta($post->ID, '_atlas_teacher', true);
                $ayeha       = get_post_meta($post->ID, '_atlas_ayeha', true);
                $operator    = get_post_meta($post->ID, '_operator', true);

                $responsible = ($responsible) ? $responsible : '';
                $mobile      = ($mobile) ? $mobile : '';
                $center_mode = ($center_mode) ? $center_mode : 'public';
                $center_type = ($center_type) ? $center_type : 'Institute';
                $phone       = ($phone) ? $phone : '';
                $ostan       = ($ostan) ? $ostan : 0;
                $city        = ($city) ? $city : 0;
                $address     = ($address) ? $address : '';
                $link        = (is_array($link)) ? $link : [ 'site' => '', 'eitaa' => '', 'bale' => '', 'rubika' => '', 'telegram' => '', 'instagram' => '' ];
                $gender      = (is_array($gender)) ? $gender : [  ];
                $age         = (is_array($age)) ? $age : [  ];
                $contacts    = ($contacts) ? $contacts : '';
                $course_type = (is_array($course_type)) ? $course_type : [  ];
                $subject     = ($subject) ? $subject : '';
                $coaches     = ($coaches) ? $coaches : '';
                $teacher     = (is_array($teacher)) ? $teacher : '';
                $ayeha       = ($ayeha) ? $ayeha : 'no';

                $ostan = ($ostan) ? $irandb->get('id', $ostan)->name : 0;
                $city  = ($city) ? $irandb->get('id', $city)->name : 0;

                $translations_course_type = [
                    'online'  => 'آنلاین',
                    'offline' => 'آفلاین',
                 ];
                if (is_array($course_type)) {
                    $translated_course_type = array_map(function ($word) use ($translations_course_type) {
                        return $translations_course_type[ $word ] ?? $word;
                    }, $course_type);
                }
                $translations_age = [
                    '7'   => 'زیر 7 سال',
                    '12'  => '7 تا 12 سال',
                    '18'  => '12 تا 18 سال',
                    'old' => '18 سال به بالا',
                 ];

                if (is_array($age)) {
                    $translated_age = array_map(function ($word) use ($translations_age) {
                        return $translations_age[ $word ] ?? $word;
                    }, $age);
                }

                $translations_age = [
                    'woman' => 'خانم',
                    'man'   => 'آقا',
                 ];

                if (is_array($gender)) {

                    $translated_gender = array_map(function ($word) use ($translations_age) {
                        return $translations_age[ $word ] ?? $word;
                    }, $gender);
                }


                switch ($center_type) {
                    case 'Institute':
                        $center_type = 'موسسه';
                        break;
                    case 'house_of_quran':
                        $center_type = 'خانه قرآن';
                        break;
                    case 'mohfel':
                        $center_type = 'محفل';
                        break;
                    case 'education':
                        $center_type = 'آموزش پرورش';
                        break;
                    case 'besij':
                        $center_type = 'پایگاه قرآنی مساجد';
                        break;

                    default:
                        $center_type = 'نامشخص';

                        break;
                }

                $user_info = get_userdata($operator);

                if ($user_info) {
                    $operator = $user_info->user_login;
                } else {
                    $operator = "نامشخص";
                }
                $row[ 'عنوان مرکز قرآنی' ]                 = $post->post_title;
                $row[ 'نام مسئول' ]                     = $responsible;
                $row[ 'شماره موبایل مسئول' ]    = $mobile;
                $row[ 'حالت مرکز' ]                     = ($center_mode == 'public') ? 'عمومی' : 'خصوصی';
                $row[ 'نوع مرکز' ]                       = $center_type;
                $row[ 'شماره ارتباط با مرکز' ] = $phone;
                $row[ 'استان' ]                            = $ostan;
                $row[ 'شهر' ]                                = $city;
                $row[ 'آدرس' ]                              = $address;
                $row[ 'آدرس سایت' ]                     = $link[ 'site' ];
                $row[ 'کانال ایتا' ]                   = $link[ 'eitaa' ];
                $row[ 'کانال بله' ]                     = $link[ 'bale' ];
                $row[ 'کانال روبیکا' ]               = $link[ 'rubika' ];
                $row[ 'کانال تلگرام' ]               = $link[ 'telegram' ];
                $row[ 'کانال اینستاگرام' ]       = $link[ 'instagram' ];
                $row[ 'جنسیت هدف' ]                     = implode('، ', array_unique($translated_gender));
                $row[ 'گروه سنی' ]                       = implode('، ', array_unique($translated_age));
                $row[ 'تعداد مخاطبین ' ]            = $contacts;
                $row[ 'قالب برگزیده دوره ها' ] = implode('، ', array_unique($translated_course_type));
                $row[ 'محتوا و موضوع ' ]             = $subject;
                $row[ 'تعداد مربیان ' ]              = $coaches;
                $row[ 'اساتید برجسته' ]             = (is_array($teacher)) ? implode(' | ', $teacher) : '';
                $row[ ' زندگی با آیه ها ' ]         = ($ayeha == 'yes') ? 'بله' : 'خیر';
                $row[ 'توضیحات ' ]                       = sanitize_text_field(apply_filters('the_content', $post->post_content));
                $row[ 'نام کاربری اپراتور' ]    = $operator;

                $data[  ] = $row;
            }

            function filterData(&$str)
            {
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if (strstr($str, '"')) {
                    $str = '"' . str_replace('"', '""', $str) . '"';
                }

            }

            // file name for download
            $fileName = "institute.xls";

            // headers for download
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Content-type: application/octet-stream");
            header('Content-Transfer-Encoding: binary');
            header("Pragma: no-cache");
            header("Expires: 0");

            $flag = false;
            foreach ($data as $row) {
                if (! $flag) {
                    // display column names as first row
                    $key1 = implode("\t", array_keys($row)) . "\n";

                    echo chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE", $key1);
                    $flag = true;
                }
                // filter data
                array_walk($row, 'filterData');
                $key2 = implode("\t", array_values($row)) . "\n";
                echo chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE", $key2);
            }

            exit;

        }

    }

}
