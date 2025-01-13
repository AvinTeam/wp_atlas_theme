<?php

(defined('ABSPATH')) || exit;

function atlas_to_enghlish($text)
{

    $western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $persian = [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ];
    $arabic = [ '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' ];
    $text = str_replace($persian, $western, $text);
    $text = str_replace($arabic, $western, $text);
    return $text;

}

function sanitize_phone($phone)
{

    /**
     * Convert all chars to en digits
     */

    $phone = atlas_to_enghlish($phone);

    //.9158636712   => 09158636712
    if (strpos($phone, '.') === 0) {
        $phone = '0' . substr($phone, 1);
    }

    //00989185223232 => 9185223232
    if (strpos($phone, '0098') === 0) {
        $phone = substr($phone, 4);
    }
    //0989108210911 => 9108210911
    if (strlen($phone) == 13 && strpos($phone, '098') === 0) {
        $phone = substr($phone, 3);
    }
    //+989156040160 => 9156040160
    if (strlen($phone) == 13 && strpos($phone, '+98') === 0) {
        $phone = substr($phone, 3);
    }
    //+98 9156040160 => 9156040160
    if (strlen($phone) == 14 && strpos($phone, '+98 ') === 0) {
        $phone = substr($phone, 4);
    }
    //989152532120 => 9152532120
    if (strlen($phone) == 12 && strpos($phone, '98') === 0) {
        $phone = substr($phone, 2);
    }
    //Prepend 0
    if (strpos($phone, '0') !== 0) {
        $phone = '0' . $phone;
    }
    /**
     * check for all character was digit
     */
    if (!ctype_digit($phone)) {
        return '';
    }

    if (strlen($phone) != 11) {
        return '';
    }

    return $phone;

}


function atlas_transient()
{
    $atlas_transient = get_transient('atlas_transient');

    if ($atlas_transient) {
        delete_transient('atlas_transient');
        return $atlas_transient;
    }

}

function is_mobile($mobile) {
    $pattern = '/^(\+98|0)?9\d{9}$/'; 
    return preg_match($pattern, $mobile);
}