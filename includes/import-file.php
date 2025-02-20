<?php

use atlasclass\Iran_Area;
use PhpOffice\PhpSpreadsheet\IOFactory;

$irandb = new Iran_Area;

$count_row = 0;

function fun_center_mode($item)
{
    if ($item == null) {return 'private';}
    switch (trim($item)) {
        case 'عمومی':
            return 'public';
            break;
        case 'خصوصی':
            return 'private';
            break;
        default:
            return 'private';
            break;
    }
}

function fun_gender($item)
{
    if ($item == null) {
        $gender = [  ];
    } elseif (trim($item) == "خواهران") {
        $gender = [ 'woman' ];
    } elseif (trim($item) == "برادران") {
        $gender = [ 'man' ];
    } else {
        $gender = [ 'woman', 'man' ];
    }
    return $gender;

}
function fun_age($item)
{
    if ($item == null) {return [  ];}

    $age_item = sanitize_text_no_item(explode(',', $item));

    $age_item = array_map('trim', $age_item);

    if (in_array('عمومی', $age_item)) {
        return [ 7, 12, 18, 'old' ];
    }
    $age = [  ];

    if (in_array('خردسال', $age_item)) {
        $age[  ] = 7;
    }

    if (in_array('کودک', $age_item)) {
        $age[  ] = 12;
    }

    if (in_array('نوجوان', $age_item)) {
        $age[  ] = 18;
    }

    if (in_array('بزرگسال', $age_item) || in_array('جوان', $age_item)) {
        $age[  ] = 'old';
    }

    return $age;

}

// بررسی آپلود فایل
if (isset($_FILES[ 'excel_file' ]) && $_FILES[ 'excel_file' ][ 'error' ] === UPLOAD_ERR_OK) {
    $fileTmpPath   = $_FILES[ 'excel_file' ][ 'tmp_name' ];
    $fileName      = $_FILES[ 'excel_file' ][ 'name' ];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // بررسی فرمت فایل
    $allowedExtensions = [ 'xls', 'xlsx' ];
    if (! in_array($fileExtension, $allowedExtensions)) {
        die("فرمت فایل پشتیبانی نمی‌شود. لطفاً یک فایل اکسل انتخاب کنید.");
    }

    try {
        // خواندن فایل اکسل
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet       = $spreadsheet->getActiveSheet();          // شیت فعال
        $data        = $sheet->toArray(null, true, true, true); // تبدیل به آرایه

        foreach ($data as $rowIndex => $row) {
            $ostan = 0;
            $city  = 0;

            if ($rowIndex === 1) {continue;}

            $row[ 'F' ] = explode('-', $row[ 'F' ]);
            $row[ 'F' ] = $row[ 'F' ][ 0 ];

            $row[ 'F' ] = preg_replace('/[\x00-\x1F\x7F]/', '', $row[ 'F' ]);

            $ostan = $irandb->new_get([
                'name'        => trim($row[ 'F' ]),
                'province_id' => 0,
             ]);

            if ($ostan != null) {

                $ostan = $ostan->id;

                $city = $irandb->new_get([
                    'name'        => $row[ 'G' ],
                    'province_id' => $ostan,
                 ]);

                if ($city != null) {
                    $city = $city->id;
                } else {
                    $city = $irandb->new_insert([
                        'name'        => $row[ 'G' ],
                        'province_id' => $ostan,
                     ]);

                    if (! $city) {
                        $city = 0;
                    }
                }

            }

            try {

                $atlas_institute = [
                    'responsible-mobile' => (is_mobile(sanitize_phone($row[ 'C' ]))) ? sanitize_phone($row[ 'C' ]) : '',
                    'link'               => [
                        'site'      => sanitize_text_field($row[ 'I' ]),
                        'eitaa'     => sanitize_text_field($row[ 'J' ]),
                        'bale'      => sanitize_text_field($row[ 'K' ]),
                        'rubika'    => sanitize_text_field($row[ 'L' ]),
                        'telegram'  => sanitize_text_field($row[ 'M' ]),
                        'instagram' => sanitize_text_field($row[ 'N' ]),
                     ],
                    'contacts'           => ($row[ 'Q' ] == null) ? 0 : absint($row[ 'Q' ]),
                    'subject'            => ($row[ 'R' ] == null) ? '' : sanitize_text_field($row[ 'R' ]),
                    'coaches'            => ($row[ 'S' ] == null) ? 0 : absint($row[ 'S' ]),
                    'teacher'            => ($row[ 'T' ] == null) ? [  ] : sanitize_text_no_item(explode(',', $row[ 'T' ])),
                 ];

                error_log('row exel: ' . $rowIndex . ' is ok');

                $post_id = wp_insert_post([
                    'post_title'   => sanitize_text_field($row[ 'A' ]),
                    'post_content' => sanitize_textarea_field($row[ 'U' ]),
                    'post_status'  => 'publish',
                    'post_type'    => 'institute',
                 ]);
                if ($post_id) {

                    update_post_meta($post_id, '_atlas_responsible', sanitize_text_field($row[ 'B' ]));
                    update_post_meta($post_id, '_atlas_responsible-mobile', $atlas_institute[ 'responsible-mobile' ]);
                    update_post_meta($post_id, '_atlas_center-mode', fun_center_mode($row[ 'D' ]));
                    update_post_meta($post_id, '_atlas_center-type', 'home');
                    update_post_meta($post_id, '_atlas_phone', atlas_to_enghlish($row[ 'E' ]));
                    update_post_meta($post_id, '_atlas_ostan', $ostan);
                    update_post_meta($post_id, '_atlas_city', $city);
                    update_post_meta($post_id, '_atlas_map', [ 'lat' => '', 'lng' => '' ]);
                    update_post_meta($post_id, '_atlas_address', sanitize_textarea_field($row[ 'H' ]));
                    update_post_meta($post_id, '_atlas_link', $atlas_institute[ 'link' ]);
                    update_post_meta($post_id, '_atlas_gender', fun_gender($row[ 'O' ]));
                    update_post_meta($post_id, '_atlas_age', fun_age($row[ 'P' ]));
                    update_post_meta($post_id, '_atlas_contacts', $atlas_institute[ 'contacts' ]);
                    update_post_meta($post_id, '_atlas_course-type', 'online');
                    update_post_meta($post_id, '_atlas_subject', $atlas_institute[ 'subject' ]);
                    update_post_meta($post_id, '_atlas_coaches', $atlas_institute[ 'coaches' ]);
                    update_post_meta($post_id, '_atlas_teacher', $atlas_institute[ 'teacher' ]);
                    update_post_meta($post_id, '_atlas_ayeha', 'no');
                    update_post_meta($post_id, '_operator', get_current_user_id());

                    if (! absint($ostan)) {error_log('Error row exel: ' . $rowIndex . ' not  province');}
                    if (! absint($city)) {error_log('Error row exel: ' . $rowIndex . ' not  city');}
                    if (empty($atlas_institute[ 'responsible-mobile' ])) {error_log('Error row exel: ' . $rowIndex . ' not  responsible mobile');}

                    error_log('----------------------------- post_id : ' . $post_id . ' -----------------------------');

                } else {
                    error_log('Error row exel: ' . $rowIndex . ' not insert institute');
                }
            } catch (Exception $e) {

                error_log('Error row exel: ' . $rowIndex . ' is nat ok catch');
                error_log("Error processing item: " . $e->getMessage());
            }

        }

        // var_dump($mappedRow);

    } catch (Exception $e) {
        die("خطا در خواندن فایل اکسل: " . $e->getMessage());
    }

} else {
    die("لطفاً یک فایل اکسل آپلود کنید.");
}
