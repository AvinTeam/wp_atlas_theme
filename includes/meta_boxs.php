<?php
(defined('ABSPATH')) || exit;

add_action('add_meta_boxes', 'atlas_meta_box');

function atlas_meta_box()
{

    remove_post_type_support('institute', 'editor');

    add_meta_box(
        'atlas_meta_box',
        "اطلاعات موسسه",
        'atlas_file_meta_box_callback',
        'institute',
        'normal',
        'high'
    );

    function atlas_file_meta_box_callback($post)
    {

        $atlas_institute_default = [

            'operator' => '',
            'responsible' => '',
            'responsible-mobile' => '',
            'center-mode' => 'public',
            'center-type' => 'Institute',
            'phone' => '',
            'ostan' => 0,
            'city' => 0,
            'map' => [
                'lat' => '',
                'lng' => '',
             ],
            'address' => '',
            'link' => [
                'site' => '',
                'eitaa' => '',
                'bale' => '',
                'rubika' => '',
                'telegram' => '',
                'instagram' => '',
             ],
            'gender' => [  ],
            'age' => [  ],
            'contacts' => '',
            'course-type' => [  ],
            'subject' => '',
            'coaches' => '',
            'teacher' => [  ],
            'ayeha' => 'yes',
         ];

        $atlas_institute_get = get_post_meta($post->ID, '_atlas_institute', true);

        $atlas_institute_get = ($atlas_institute_get) ? $atlas_institute_get : [  ];

        if (isset($atlas_institute_get[ 'link' ]) && !is_array($atlas_institute_get[ 'link' ])) {unset($atlas_institute_get[ 'link' ]);}

        $atlas_institute = array_merge($atlas_institute_default, $atlas_institute_get);

        $irandb = new Iran_Area;
        $ostan = $irandb->select(0);
        $city = (absint($atlas_institute[ 'ostan' ])) ? $irandb->select($atlas_institute[ 'ostan' ]) : [  ];

        include_once ATLAS_VIEWS . 'meta_box.php';

    }

}

add_action('save_post', 'atlas_save_bax', 10, 3);

function atlas_save_bax($post_id, $post, $updata)
{
    if (isset($_POST[ 'atlas' ])) {

        foreach ($_POST[ 'atlas' ] as $key => $value) {
            if ($key == 'responsible') {$_POST[ 'atlas' ][ $key ] = sanitize_text_field($value);}
            if ($key == 'responsible-mobile') {$_POST[ 'atlas' ][ $key ] = sanitize_phone($value);}
            if ($key == 'center-mode') {$_POST[ 'atlas' ][ $key ] = sanitize_text_field($value);}
            if ($key == 'center-type') {$_POST[ 'atlas' ][ $key ] = sanitize_text_field($value);}
            if ($key == 'phone') {$_POST[ 'atlas' ][ $key ] = atlas_to_enghlish($value);}
            if ($key == 'ostan') {$_POST[ 'atlas' ][ $key ] = absint($value);}
            if ($key == 'city') {$_POST[ 'atlas' ][ $key ] = absint($value);}
            if ($key == 'map') {$_POST[ 'atlas' ][ $key ] = array_map('sanitize_text_field', $value);}
            if ($key == 'address') {$_POST[ 'atlas' ][ $key ] = sanitize_textarea_field($value);}
            if ($key == 'link') {$_POST[ 'atlas' ][ $key ] = array_map('sanitize_url', $value);}
            if ($key == 'gender') {$_POST[ 'atlas' ][ $key ] = array_map('sanitize_text_field', $value);}
            if ($key == 'age') {$_POST[ 'atlas' ][ $key ] = array_map('sanitize_text_field', $value);}
            if ($key == 'contacts') {$_POST[ 'atlas' ][ $key ] = absint($value);}
            if ($key == 'course-type') {$_POST[ 'atlas' ][ $key ] = array_map('sanitize_text_field', $value);}
            if ($key == 'subject') {$_POST[ 'atlas' ][ $key ] = sanitize_text_field($value);}
            if ($key == 'coaches') {$_POST[ 'atlas' ][ $key ] = absint($value);}
            if ($key == 'teacher') {$_POST[ 'atlas' ][ $key ] = array_map('sanitize_text_field', $value);}
            if ($key == 'ayeha') {$_POST[ 'atlas' ][ $key ] = sanitize_text_field($value);}
        }

        update_post_meta($post_id, '_atlas_institute', $_POST[ 'atlas' ]);

        $operator = get_post_meta($post->ID, '_operator', true);
        if (!$operator) {
            $operator_id = ((current_user_can('operator'))) ? get_current_user_id() : 0;

            update_post_meta($post_id, '_operator', $operator_id);
        }

    }
}
