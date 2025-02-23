<?php
(defined('ABSPATH')) || exit;

function atlas_title_filter($title)
{
    if (is_home() || is_front_page()) {
        $title = get_bloginfo('name') . " | صفحه اصلی";
    } elseif (is_single()) {
        $title = get_the_title() . " | " . get_bloginfo('name');
    } elseif (is_category()) {
        $title = single_cat_title('', false) . " | دسته‌بندی";
    } elseif (is_tag()) {
        $title = single_tag_title('', false) . " | برچسب";
    } elseif (is_search()) {
        $title = "نتایج جستجو برای " . get_search_query();
    } elseif (is_404()) {
        $title = get_bloginfo('name') . "صفحه پیدا نشد | ";
    } else {
        $title = get_bloginfo('name');
    }
    return $title;
}
add_filter('wp_title', 'atlas_title_filter');

function custom_institute_template($template)
{
    if (is_singular('institute')) {
        $custom_template = locate_template('institute.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'custom_institute_template');

function custom_comment_form_with_hoverable_stars($defaults)
{
    $this_user_mobile = $this_user_nickname = "";
    if (is_user_logged_in()) {

        $this_user          = wp_get_current_user();
        $this_user_nickname = $this_user->nickname;
        $this_user_mobile   = $this_user->mobile;

    }

    $defaults[ 'fields' ] = [
        'author' => '
                <div class="col-12 col-md-4 order-1 mt-2">
                    <label for="author" class="form-label">نام و نام خانوادگی</label>
                    <input class="form-control" id="author" name="author" type="text" value="" size="30" maxlength="245" autocomplete="name" required="required"
                        placeholder="نام و نام خانوادگی خود را وارد نمایید">
                </div>',
        'email'  => '<input class="form-control" id="email" name="email" type="hidden" value="' . time() . '@email.com" size="30" maxlength="100" aria-describedby="email-notes" autocomplete="email" required="required"
                        placeholder="ایمیل خود را وارد نمایید">',

        'mobile' => '
        <div class="col-12 col-md-4 order-2 mt-2">
            <label for="mobile" class="form-label">شماره موبایل</label>
            <input class="form-control" id="" name="mobile" type="text" value="" size="30" maxlength="11" placeholder="شماره موبایل خود را وارد نمایید">
        </div>',

        'rating' => '
                <div class="col-12 col-md-4 order-3 mt-2">
                    <label for="rating" class="form-label">امتیاز
                        دهی</label>
                    <div class="rating d-flex gap-2 align-items-center rating-stars">
                        <input type="radio" name="rating" value="5" id="star5" class="d-none">
                        <label for="star5" id="s5" class="star"><i class="bi bi-star"></i></label>

                        <input type="radio" name="rating" value="4" id="star4" class="d-none">
                        <label for="star4" id="s4" class="star"><i class="bi bi-star"></i></label>

                        <input type="radio" name="rating" value="3" id="star3" class="d-none">
                        <label for="star3" id="s3" class="star"><i class="bi bi-star"></i></label>

                        <input type="radio" name="rating" value="2" id="star2" class="d-none">
                        <label for="star2" id="s2" class="star"><i class="bi bi-star"></i></label>

                        <input type="radio" name="rating" value="1" id="star1" class="d-none">
                        <label for="star1" id="s1" class="star"><i class="bi bi-star"></i></label>
                    </div>
                </div>',
     ];
    $defaults[ 'comment_field' ] = '
                            <div class="mb-3 col-12 order-4">
                                <label for="comment" class="form-label">متن نظر</label>
                                <textarea class="form-control" id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" placeholder="متن نظر خود را بنویسید"></textarea>
                            </div>';

    $defaults[ 'class_form' ]           = 'mb-3 row';
    $defaults[ 'submit_field' ]         = '<p class="form-submit col-12 mt-2  order-5">%1$s %2$s</p>';
    $defaults[ 'title_reply' ]          = '';
    $defaults[ 'comment_notes_before' ] = '';
    $defaults[ 'class_submit' ]         = 'btn btn-primary ';
    $defaults[ 'label_submit' ]         = 'ارسال دیدگاه';
    $defaults[ 'logged_in_as' ]         = '
                <div class="col-12 col-md-4 order-1 mt-2">
                    <label for="author" class="form-label">نام و نام خانوادگی <a href="' . atlas_base_url('panel/?profile') . '">(ویرایش)</a></label>
                    <input class="form-control" value="' . $this_user_nickname . '" disabled>
                </div>

                <div class="col-12 col-md-4 order-2 mt-2">
                    <label for="mobile" class="form-label">شماره موبایل</label>
                    <input class="form-control" type="text" value="' . $this_user_mobile . '" disabled>
                </div>
                <div class="col-12 col-md-4 order-3 mt-2">
                    <label for="rating" class="form-label">امتیاز
                        دهی</label>
                    <div class="rating d-flex gap-2 align-items-center rating-stars">
                        <input type="radio" name="rating" value="5" id="star5" class="d-none">
                        <label for="star5" id="s5" class="star"><i class="bi bi-star"></i></label>

                        <input type="radio" name="rating" value="4" id="star4" class="d-none">
                        <label for="star4" id="s4" class="star"><i class="bi bi-star"></i></label>

                        <input type="radio" name="rating" value="3" id="star3" class="d-none">
                        <label for="star3" id="s3" class="star"><i class="bi bi-star"></i></label>

                        <input type="radio" name="rating" value="2" id="star2" class="d-none">
                        <label for="star2" id="s2" class="star"><i class="bi bi-star"></i></label>

                        <input type="radio" name="rating" value="1" id="star1" class="d-none">
                        <label for="star1" id="s1" class="star"><i class="bi bi-star"></i></label>
                    </div>
                </div>


                ';
    // $defaults[ 'logged_in_as' ]         = '<p class="logged-in-as">به عنوان محمد رضایی پور وارد شده‌اید. <a href="https://atlas.test/wp-admin/profile.php">نمایهٔ خود را ویرایش نمایید</a>. <a href="https://atlas.test/wp-login.php?action=logout&amp;redirect_to=https%3A%2F%2Fatlas.test%2Finstitute%2F63444%2F&amp;_wpnonce=44cf937064">بیرون رفتن؟</a> <span class="required-field-message">بخش‌های موردنیاز علامت‌گذاری شده‌اند <span class="required">*</span></span></p>';

//print_r($defaults);

// exit;

    return $defaults;
}
add_filter('comment_form_defaults', 'custom_comment_form_with_hoverable_stars');

function display_comment_rating($comment_text, $comment)
{
    if (! $comment || ! isset($comment->comment_ID)) {
        return $comment_text;
    }

    $rating = get_comment_meta($comment->comment_ID, 'rating', true);
    if ($rating) {
        $stars = str_repeat('⭐', $rating);
        $comment_text .= '<p class="comment-rating">امتیاز: ' . $stars . '</p>';
    } else {
        $comment_text .= '<p class="comment-rating">امتیاز: صفر</p>';
    }
    $mobile = get_comment_meta($comment->comment_ID, 'mobile', true);
    if ($mobile) {
        $comment_text .= '<p class="comment-rating">شماره موبایل: ' . $mobile . '</p>';
    } else {
        $comment_text .= '<p class="comment-rating">شماره موبایل:  - </p>';
    }
    return $comment_text;
}
add_filter('comment_text', 'display_comment_rating', 10, 2);

function custom_comment_redirect($location, $comment)
{
    if ($comment->comment_approved == 0) {
        return add_query_arg('comment-status', 'pending', get_permalink($comment->comment_post_ID));
    }
    return $location;
}
add_filter('comment_post_redirect', 'custom_comment_redirect', 10, 2);

// فیلتر کردن لینک پست برای پست تایپ institute
function custom_institute_permalink($post_link, $post)
{
    if ('institute' === $post->post_type) {
        // ساختار لینک به صورت institute/{post_id}
        $post_link = home_url('institute/' . $post->ID . '/');
    }
    return $post_link;
}
add_filter('post_type_link', 'custom_institute_permalink', 10, 2);

add_filter('mrsms_user_role', function ($role) {

    return 'responsible';

});

add_action('mrsms_after_send_sms', 'atlas_sent_verify', 10, 2);

function atlas_sent_verify($user_id, $mobile)
{
    $args = [
        'post_type'      => 'institute',
        'post_status'    => [ 'publish' , 'pending' ],
        'meta_query'     => [
            [
                'key'     => '_atlas_responsible-mobile',
                'value'   => $mobile,
                'compare' => '=',
             ],
         ],
        'fields'         => 'ids',
        'posts_per_page' => -1,
     ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        $post_id = $query->posts[ 0 ];

        $full_name = get_post_meta($post_id, '_atlas_responsible', true);

        update_user_meta($user_id, 'first_name', $full_name);

        update_user_meta($user_id, 'nickname', $full_name);

        wp_update_user([
            'ID'           => $user_id,
            'display_name' => $full_name,
         ]);

        while ($query->have_posts()) {
            $query->the_post();

            $post_data = [
                'ID'          => get_the_ID(),
                'post_author' => $user_id,
             ];

            update_post_meta(get_the_ID(), '_atlas_responsible', $full_name);

            wp_update_post($post_data, true);

        }

    }

}