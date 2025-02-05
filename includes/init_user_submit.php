<?php

(defined('ABSPATH')) || exit;

add_action('init', 'atlas_user_submit_init');

function atlas_user_submit_init()
{
    if (isset($_POST[ 'act_user' ]) && isset($_REQUEST[ 'post' ]) && $_POST[ 'act_user' ] == 'form_submit') {
        $post_update = false;

        if (wp_verify_nonce($_POST[ '_wpnonce' ], 'atlas_nonce_user_submit' . get_current_user_id())) {

            $args = [
                'author'         => get_current_user_id(),                                  // شناسه نویسنده
                'p'              => absint($_REQUEST[ 'post' ]),                            // شناسه نویسنده
                'post_type'      => 'institute',                                            // نوع پست (می‌تونی 'page' یا نوع‌های کاستوم هم بگذاری)
                'post_status'    => [ 'publish', 'draft', 'pending', 'private', 'future' ], // همه وضعیت‌ها
                'posts_per_page' => 1,                                                      // تعداد پست‌ها (برای همه پست‌ها: -1)
                'fields'         => 'ids',                                                  // برگرداندن فقط شناسه‌ها
             ];

            // اجرای کوئری
            $query = new WP_Query($args);

            // گرفتن داده‌های پست‌ها
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();

                    $post_id          = get_the_ID();
                    $post_title       = get_the_title();
                    $post_description = get_the_excerpt();
                    $attachment_id    = has_post_thumbnail() ? get_post_thumbnail_id($post_id) : '';

                }

                $atlas_institute_old = [

                    'center-mode' => get_post_meta($post_id, '_atlas_center-mode', true),
                    'center-type' => get_post_meta($post_id, '_atlas_center-type', true),
                    'phone'       => atlas_to_enghlish(get_post_meta($post_id, '_atlas_phone', true)),
                    'ostan'       => absint(get_post_meta($post_id, '_atlas_ostan', true)),
                    'city'        => absint(get_post_meta($post_id, '_atlas_city', true)),
                    'map'         => get_post_meta($post_id, '_atlas_map', true),
                    'address'     => get_post_meta($post_id, '_atlas_address', true),
                    'link'        => get_post_meta($post_id, '_atlas_link', true),
                    'gender'      => get_post_meta($post_id, '_atlas_gender', true),
                    'age'         => get_post_meta($post_id, '_atlas_age', true),
                    'contacts'    => absint(get_post_meta($post_id, '_atlas_contacts', true)),
                    'course-type' => get_post_meta($post_id, '_atlas_course-type', true),
                    'subject'     => get_post_meta($post_id, '_atlas_subject', true),
                    'coaches'     => absint(get_post_meta($post_id, '_atlas_coaches', true)),
                    'teacher'     => get_post_meta($post_id, '_atlas_teacher', true),
                    'ayeha'       => get_post_meta($post_id, '_atlas_ayeha', true),
                 ];

                $atlas_institute = [
                    'center-mode' => (isset($_POST[ 'atlas' ][ 'center-mode' ]) && $_POST[ 'atlas' ][ 'center-mode' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'center-mode' ]) : 'public',
                    'center-type' => (isset($_POST[ 'atlas' ][ 'center-type' ]) && $_POST[ 'atlas' ][ 'center-type' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'center-type' ]) : 'Institute',
                    'phone'       => (isset($_POST[ 'atlas' ][ 'phone' ]) && $_POST[ 'atlas' ][ 'phone' ]) ? atlas_to_enghlish($_POST[ 'atlas' ][ 'phone' ]) : '',
                    'ostan'       => (isset($_POST[ 'atlas' ][ 'ostan' ]) && $_POST[ 'atlas' ][ 'ostan' ]) ? absint($_POST[ 'atlas' ][ 'ostan' ]) : 0,
                    'city'        => (isset($_POST[ 'atlas' ][ 'city' ]) && $_POST[ 'atlas' ][ 'city' ]) ? absint($_POST[ 'atlas' ][ 'city' ]) : 0,
                    'map'         => (isset($_POST[ 'atlas' ][ 'map' ]) && is_array($_POST[ 'atlas' ][ 'map' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'map' ]) : [ 'lat' => '', 'lng' => '' ],
                    'address'     => (isset($_POST[ 'atlas' ][ 'address' ]) && $_POST[ 'atlas' ][ 'address' ]) ? sanitize_textarea_field($_POST[ 'atlas' ][ 'address' ]) : '',
                    'link'        => (isset($_POST[ 'atlas' ][ 'link' ]) && is_array($_POST[ 'atlas' ][ 'link' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'link' ]) : [ 'site' => '', 'eitaa' => '', 'bale' => '', 'rubika' => '', 'telegram' => '', 'instagram' => '' ],
                    'gender'      => (isset($_POST[ 'atlas' ][ 'gender' ]) && is_array($_POST[ 'atlas' ][ 'gender' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'gender' ]) : [  ],
                    'age'         => (isset($_POST[ 'atlas' ][ 'age' ]) && is_array($_POST[ 'atlas' ][ 'age' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'age' ]) : [  ],
                    'contacts'    => (isset($_POST[ 'atlas' ][ 'contacts' ]) && $_POST[ 'atlas' ][ 'contacts' ]) ? absint($_POST[ 'atlas' ][ 'contacts' ]) : '',
                    'course-type' => (isset($_POST[ 'atlas' ][ 'course-type' ]) && is_array($_POST[ 'atlas' ][ 'course-type' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'course-type' ]) : [  ],
                    'subject'     => (isset($_POST[ 'atlas' ][ 'subject' ]) && $_POST[ 'atlas' ][ 'subject' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'subject' ]) : '',
                    'coaches'     => (isset($_POST[ 'atlas' ][ 'coaches' ]) && $_POST[ 'atlas' ][ 'coaches' ]) ? absint($_POST[ 'atlas' ][ 'coaches' ]) : '',
                    'teacher'     => (isset($_POST[ 'atlas' ][ 'teacher' ]) && is_array($_POST[ 'atlas' ][ 'teacher' ])) ? array_map('sanitize_text_field', $_POST[ 'atlas' ][ 'teacher' ]) : [  ],
                    'ayeha'       => (isset($_POST[ 'atlas' ][ 'ayeha' ]) && $_POST[ 'atlas' ][ 'ayeha' ]) ? sanitize_text_field($_POST[ 'atlas' ][ 'ayeha' ]) : 'no',
                 ];

                $differences = [  ];

                foreach ($atlas_institute_old as $key => $value) {
                    if (isset($atlas_institute[ $key ]) && $atlas_institute[ $key ] !== $value) {
                        $differences[ $key ] = [
                            'value1' => $value,
                            'value2' => $atlas_institute[ $key ],
                         ];
                    }
                }

                // نمایش نتیجه
                if (! empty($differences)) {
                    $post_update = true;
                }

                $post_title = get_the_title();

                $new_title = sanitize_text_field($_POST[ 'title' ]);
                if ($post_title != $new_title) {
                    $post_update = true;
                }
                $post_description = get_the_excerpt();

                $new_description = sanitize_textarea_field($_POST[ 'post_content' ]);
                if ($post_description != $new_description) {
                    $post_update = true;
                }
                if ($post_update) {
                    wp_update_post([
                        'ID'           => $post_id,
                        'post_title'   => $new_title,
                        'post_excerpt' => $new_description,
                        'post_status'  => 'pending',
                     ]);

                    if (! empty($_FILES[ 'fileInput' ][ 'name' ])) {

                        $upload = atlas_upload_file($_FILES[ 'fileInput' ], absint($attachment_id));

                        if ($upload[ 'code' ]) {

                            set_post_thumbnail($post_id, $upload[ 'massage' ]);

                        }
                    }

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
                }
            }

            wp_redirect(atlas_base_url('panel'));

        }

    }

    if (isset($_POST[ 'act_user' ]) && isset($_REQUEST[ 'profile' ]) && $_POST[ 'act_user' ] == 'form_submit_profile') {
        if (wp_verify_nonce($_POST[ '_wpnonce' ], 'atlas_nonce_user_submit' . get_current_user_id())) {
            $current_user_id = get_current_user_id();

            $first_name = sanitize_text_field($_POST[ 'first_name' ]);
            $last_name  = sanitize_text_field($_POST[ 'last_name' ]);

            if ($first_name != "") {
                update_user_meta($current_user_id, 'first_name', $first_name);
            }

            if ($last_name != "") {
                update_user_meta($current_user_id, 'last_name', $last_name);
            }

            $full_name = $first_name . ' ' . $last_name;

            update_user_meta($current_user_id, 'nickname', $full_name);

            wp_update_user([
                'ID'           => $current_user_id,
                'display_name' => $full_name,
             ]);

        }
    }

}
