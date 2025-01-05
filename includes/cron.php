<?php
(defined('ABSPATH')) || exit;

// افزودن اکشن کرون
add_action('avin_it_cron_job', 'avin_it_cron_function');

function avin_it_cron_function()
{
    $atlas_option = get_option('atlas_option');

    $nasrdb = new NasrDB();

    $url = add_query_arg([
        'username' => $atlas_option[ 'tsms' ][ 'username' ],
        'password' => $atlas_option[ 'tsms' ][ 'password' ],
        'from' => $atlas_option[ 'tsms' ][ 'number' ],
     ], 'http://www.tsms.ir/url/recived_sms_xml.php');

    $response = wp_remote_get($url, [
        'timeout' => 15,
     ]);

    if (is_wp_error($response)) {
        error_log('خطا در درخواست: ' . $response->get_error_message());
    }

    $xml_string = wp_remote_retrieve_body($response);

    if (simplexml_load_string($xml_string)) {
        $xml_content = simplexml_load_string($xml_string);

        foreach ($xml_content->msg as $value) {

            if (atlas_to_enghlish($value->text) == atlas_to_enghlish($atlas_option[ 'target_word' ])) {

                $mobile = sanitize_phone($value->from);

                $num = $nasrdb->num($mobile);

                if (!absint($num)) {

                    $frm = [
                        'mobile' => $mobile,
                        'status' => 'sms',

                     ];

                    $nasrdb->insert($frm, [ '%s', '%s' ]);

                }

            }

        }
    }

}
