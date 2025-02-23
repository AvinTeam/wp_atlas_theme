<?php

use atlasclass\Iran_Area;

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
