<?php
(defined('ABSPATH')) || exit;

function atlas_title_filter($title) {
    if (is_home() || is_front_page()) {
        $title = get_bloginfo('name') ." | صفحه اصلی";
    } elseif (is_single()) {
        $title = get_the_title() . " | " . get_bloginfo('name');
    } elseif (is_category()) {
        $title = single_cat_title('', false) . " | دسته‌بندی";
    } elseif (is_tag()) {
        $title = single_tag_title('', false) . " | برچسب";
    } elseif (is_search()) {
        $title = "نتایج جستجو برای " . get_search_query();
    } elseif (is_404()) {
        $title =get_bloginfo('name'). "صفحه پیدا نشد | ";
    } else {
        $title = get_bloginfo('name');
    }
    return $title;
}
add_filter('wp_title', 'atlas_title_filter');

function custom_institute_template($template) {
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

    $defaults[ 'fields' ] = [
        'author' => '
                <div class="col-4 order-1">
                    <label for="author" class="form-label">نام و نام خانوادگی</label>
                    <input class="form-control" id="author" name="author" type="text" value="" size="30" maxlength="245" autocomplete="name" required="required"
                        placeholder="نام و نام خانوادگی خود را وارد نمایید">
                </div>',
        'email'  => '
                <div class="col-4 order-2">
                    <label for="email" class="form-label">ایمیل</label>
                    <input class="form-control" id="email" name="email" type="text" value="" size="30" maxlength="100" aria-describedby="email-notes" autocomplete="email" required="required"
                        placeholder="ایمیل خود را وارد نمایید">
                </div>',
        'rating' => '
                <div class="col-4 order-3">
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

    return $defaults;
}
add_filter('comment_form_defaults', 'custom_comment_form_with_hoverable_stars');


function display_comment_rating($comment_text, $comment)
{
    $rating = get_comment_meta($comment->comment_ID, 'rating', true);
    if ($rating) {
        $stars = str_repeat('⭐', $rating);
        $comment_text .= '<p class="comment-rating">امتیاز: ' . $stars . '</p>';
    }else{
        $comment_text .= '<p class="comment-rating">امتیاز: صفر</p>';
    }
    return $comment_text;
}
add_filter('comment_text', 'display_comment_rating', 10, 2);
