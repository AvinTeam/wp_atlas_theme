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

        $responsible = get_post_meta($post->ID, '_atlas_responsible', true);
        $mobile      = get_post_meta($post->ID, '_atlas_responsible-mobile', true);
        $center_mode = get_post_meta($post->ID, '_atlas_center-mode', true);
        $center_type = get_post_meta($post->ID, '_atlas_center-type', true);
        $phone       = get_post_meta($post->ID, '_atlas_phone', true);
        $ostan       = get_post_meta($post->ID, '_atlas_ostan', true);
        $city        = get_post_meta($post->ID, '_atlas_city', true);
        $map         = get_post_meta($post->ID, '_atlas_map', true);
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

        $atlas_institute = [

            'responsible'        => ($responsible) ? $responsible : '',
            'responsible-mobile' => ($mobile) ? $mobile : '',
            'center-mode'        => ($center_mode) ? $center_mode : 'public',
            'center-type'        => ($center_type) ? $center_type : 'Institute',
            'phone'              => ($phone) ? $phone : '',
            'ostan'              => ($ostan) ? $ostan : 0,
            'city'               => ($city) ? $city : 0,
            'map'                => (is_array($map)) ? $map : [ 'lat' => '', 'lng' => '' ],
            'address'            => ($address) ? $address : '',
            'link'               => (is_array($link)) ? $link : [ 'site' => '', 'eitaa' => '', 'bale' => '', 'rubika' => '', 'telegram' => '', 'instagram' => '' ],
            'gender'             => (is_array($gender)) ? $gender : [  ],
            'age'                => (is_array($age)) ? $age : [  ],
            'contacts'           => ($contacts) ? $contacts : '',
            'course-type'        => (is_array($course_type)) ? $course_type : [  ],
            'subject'            => ($subject) ? $subject : '',
            'coaches'            => ($coaches) ? $coaches : '',
            'teacher'            => (is_array($teacher)) ? $teacher : [  ],
            'ayeha'              => ($ayeha) ? $ayeha : 'no',
         ];

        $irandb = new Iran_Area;
        $ostan  = $irandb->select(0);
        $city   = (absint($atlas_institute[ 'ostan' ])) ? $irandb->select($atlas_institute[ 'ostan' ]) : [  ];

        include_once ATLAS_VIEWS . 'meta_box.php';

    }

}

add_action('save_post', 'atlas_save_bax', 10, 3);

function atlas_save_bax($post_id, $post, $updata)
{

   

    if (isset($_POST[ 'atlas' ]) && isset($_POST[ 'atlas' ][ 'responsible-mobile' ])) {


  
        // if (is_mobile(sanitize_phone($_POST[ 'atlas' ][ 'responsible-mobile' ]))) {

        //     $args = [
        //         'post_type'      => 'institute',
        //         'post_status'    => [ 'publish', 'draft' ],
        //         'post__not_in'   => [ $post_id ],

        //         'meta_query'     => [
        //             [
        //                 'key'     => '_atlas_responsible-mobile',
        //                 'value'   => sanitize_phone($_POST[ 'atlas' ][ 'responsible-mobile' ]),
        //                 'compare' => '=',
        //              ],
        //          ],
        //         'fields'         => 'ids',
        //         'posts_per_page' => -1,
        //      ];

        //     $query      = new WP_Query($args);
        //     $post_count = $query->found_posts;

        //     if ($post_count >= 1) {

        //         set_transient('atlas_transient', '<p class="button button-primary button-large button-error" >شماره موبایل ' . sanitize_phone($_POST[ 'atlas' ][ 'responsible-mobile' ]) . ' برای مسئول موسسه دیگری ثبت شده است</p>');

        //         $_POST[ 'atlas' ][ 'responsible-mobile' ] = '';

        //     }

        // }

        $atlas_institute = [

            'responsible'        => (isset($_POST[ 'atlas' ][ 'responsible' ]) && $_POST[ 'atlas' ][ 'responsible' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'responsible' ]) : '',
            'responsible-mobile' => (is_mobile(sanitize_phone($_POST[ 'atlas' ][ 'responsible-mobile' ]))) ? sanitize_phone($_POST[ 'atlas' ][ 'responsible-mobile' ]) : '',
            'center-mode'        => (isset($_POST[ 'atlas' ][ 'center-mode' ]) && $_POST[ 'atlas' ][ 'center-mode' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'center-mode' ]) : 'public',
            'center-type'        => (isset($_POST[ 'atlas' ][ 'center-type' ]) && $_POST[ 'atlas' ][ 'center-type' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'center-type' ]) : 'Institute',
            'phone'              => (isset($_POST[ 'atlas' ][ 'phone' ]) && $_POST[ 'atlas' ][ 'phone' ]) ? atlas_to_enghlish($_POST[ 'atlas' ][ 'phone' ]) : '',
            'ostan'              => (isset($_POST[ 'atlas' ][ 'ostan' ]) && $_POST[ 'atlas' ][ 'ostan' ]) ? absint($_POST[ 'atlas' ][ 'ostan' ]) : 0,
            'city'               => (isset($_POST[ 'atlas' ][ 'city' ]) && $_POST[ 'atlas' ][ 'city' ]) ? absint($_POST[ 'atlas' ][ 'city' ]) : 0,
            'map'                => (isset($_POST[ 'atlas' ][ 'map' ]) && is_array($_POST[ 'atlas' ][ 'map' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'map' ]) : [ 'lat' => '', 'lng' => '' ],
            'address'            => (isset($_POST[ 'atlas' ][ 'address' ]) && $_POST[ 'atlas' ][ 'address' ]) ? sanitize_textarea_field($_POST[ 'atlas' ][ 'address' ]) : '',
            'link'               => (isset($_POST[ 'atlas' ][ 'link' ]) && is_array($_POST[ 'atlas' ][ 'link' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'link' ]) : [ 'site' => '', 'eitaa' => '', 'bale' => '', 'rubika' => '', 'telegram' => '', 'instagram' => '' ],
            'gender'             => (isset($_POST[ 'atlas' ][ 'gender' ]) && is_array($_POST[ 'atlas' ][ 'gender' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'gender' ]) : [  ],
            'age'                => (isset($_POST[ 'atlas' ][ 'age' ]) && is_array($_POST[ 'atlas' ][ 'age' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'age' ]) : [  ],
            'contacts'           => (isset($_POST[ 'atlas' ][ 'contacts' ]) && $_POST[ 'atlas' ][ 'contacts' ]) ? absint($_POST[ 'atlas' ][ 'contacts' ]) : '',
            'course-type'        => (isset($_POST[ 'atlas' ][ 'course-type' ]) && is_array($_POST[ 'atlas' ][ 'course-type' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'course-type' ]) : [  ],
            'subject'            => (isset($_POST[ 'atlas' ][ 'subject' ]) && $_POST[ 'atlas' ][ 'subject' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'subject' ]) : '',
            'coaches'            => (isset($_POST[ 'atlas' ][ 'coaches' ]) && $_POST[ 'atlas' ][ 'coaches' ]) ? absint($_POST[ 'atlas' ][ 'coaches' ]) : '',
            'teacher'            => (isset($_POST[ 'atlas' ][ 'teacher' ]) && is_array($_POST[ 'atlas' ][ 'teacher' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'teacher' ]) : [  ],
            'ayeha'              => (isset($_POST[ 'atlas' ][ 'ayeha' ]) && $_POST[ 'atlas' ][ 'ayeha' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'ayeha' ]) : 'no',
         ];

        update_post_meta($post_id, '_atlas_responsible', $atlas_institute[ 'responsible' ]);
        update_post_meta($post_id, '_atlas_responsible-mobile', $atlas_institute[ 'responsible-mobile' ]);
        update_post_meta($post_id, '_atlas_center-mode', $atlas_institute[ 'center-mode' ]);
        update_post_meta($post_id, '_atlas_center-type', $atlas_institute[ 'center-type' ]);
        update_post_meta($post_id, '_atlas_phone', $atlas_institute[ 'phone' ]);
        update_post_meta($post_id, '_atlas_ostan', $atlas_institute[ 'ostan' ]);
        update_post_meta($post_id, '_atlas_city', $atlas_institute[ 'city' ]);
        update_post_meta($post_id, '_atlas_map', $atlas_institute[ 'map' ]);
        update_post_meta($post_id, '_atlas_address', $atlas_institute[ 'address' ]);
        update_post_meta($post_id, '_atlas_link', $atlas_institute[ 'link' ]);
        update_post_meta($post_id, '_atlas_gender', $atlas_institute[ 'gender' ]);
        update_post_meta($post_id, '_atlas_age', $atlas_institute[ 'age' ]);
        update_post_meta($post_id, '_atlas_contacts', $atlas_institute[ 'contacts' ]);
        update_post_meta($post_id, '_atlas_course-type', $atlas_institute[ 'course-type' ]);
        update_post_meta($post_id, '_atlas_subject', $atlas_institute[ 'subject' ]);
        update_post_meta($post_id, '_atlas_coaches', $atlas_institute[ 'coaches' ]);
        update_post_meta($post_id, '_atlas_teacher', $atlas_institute[ 'teacher' ]);
        update_post_meta($post_id, '_atlas_ayeha', $atlas_institute[ 'ayeha' ]);

        $operator = get_post_meta($post_id, '_operator', true);
        if (! $operator) {
            update_post_meta($post_id, '_operator', $post->post_author);

            $post_operator = absint(get_user_meta($post->post_author, 'post_operator', true));

            update_user_meta($post->post_author, 'post_operator', $post_operator++);

        }

    }
}
