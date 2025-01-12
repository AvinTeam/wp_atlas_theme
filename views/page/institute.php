<?php

$atlas = get_query_var('atlas');
$institute = explode('=', $atlas);

function atlas_title_filter_institute($title)
{
    $atlas = get_query_var('atlas');
    $institute = explode('=', $atlas);

    $title = get_bloginfo('name') . " | " . get_the_title($institute[ 1 ]);
    return $title;
}
add_filter('wp_title', 'atlas_title_filter_institute');
?>

<?php get_header(); ?>





<div class="container" style="direction: rtl;">



<?php
$post_id = $institute[ 1 ]; // آیدی پست

// گرفتن اطلاعات پست
$post = get_post($post_id);

if ($post) {

    $responsible = get_post_meta($post_id, '_atlas_responsible', true);
    $mobile = get_post_meta($post_id, '_atlas_responsible-mobile', true);
    $center_mode = get_post_meta($post_id, '_atlas_center-mode', true);
    $center_type = get_post_meta($post_id, '_atlas_center-type', true);
    $phone = get_post_meta($post_id, '_atlas_phone', true);
    $ostan = get_post_meta($post_id, '_atlas_ostan', true);
    $city = get_post_meta($post_id, '_atlas_city', true);
    $map = get_post_meta($post_id, '_atlas_map', true);
    $address = get_post_meta($post_id, '_atlas_address', true);
    $link = get_post_meta($post_id, '_atlas_link', true);
    $gender = get_post_meta($post_id, '_atlas_gender', true);
    $age = get_post_meta($post_id, '_atlas_age', true);
    $contacts = get_post_meta($post_id, '_atlas_contacts', true);
    $course_type = get_post_meta($post_id, '_atlas_course-type', true);
    $subject = get_post_meta($post_id, '_atlas_subject', true);
    $coaches = get_post_meta($post_id, '_atlas_coaches', true);
    $teacher = get_post_meta($post_id, '_atlas_teacher', true);
    $ayeha = get_post_meta($post_id, '_atlas_ayeha', true);
    $operator = get_post_meta($post_id, '_operator', true); ?>






<h1>عنوان موسسه : <?=get_the_title($post_id)?></h1>
<p>نام مسئول : <?=$responsible?></p>
<p>حالت مرکز : <?=$center_mode?></p>
<p>نوع مرکز : <?=$center_type?></p>
<p>شماره ارتباط با مرکز : <?=$phone?></p>
<p>استان : <?=$ostan?></p>
<p>شهر : <?=$city?></p>
<p>نقشه : <?=implode(",", $map)?></p>
<p>آدرس : <?=$address?></p>
<p>لینک سایت / فضای مجازی : <?=print_r($link, true)?></p>
<p>جنسیت هدف : <?=print_r($gender, true)?></p>
<p>گروه سنی : <?=print_r($age, true)?></p>
<p>تعداد مخاطبین  : <?=$contacts?></p>
<p>قالب برگزیده دوره ها   : <?=print_r($course_type, true)?></p>
<p>محتوا و موضوع    : <?=$subject?></p>
<p>تعداد مربیان : <?=$subject?></p>
<p>اساتید برجسته :  <?=print_r($teacher, true)?></p>
<p>مرکز در سایت زندگی با آیه ها نمایش  :  <?=$ayeha?></p>
<p>توضیحات بیشتر  :  <?=apply_filters('the_content', $post->post_content)?></p>











<?php

    // // // گرفتن اطلاعات اصلی پست
    // $title = get_the_title($post_id); // عنوان پست
    // $content = apply_filters('the_content', $post->post_content); // محتوای پست
    // $excerpt = $post->post_excerpt; // خلاصه پست
    // $author_id = $post->post_author; // آیدی نویسنده
    // $publish_date = $post->post_date; // تاریخ انتشار

    // // گرفتن متاهای پست
    // $meta_value = get_post_meta($post_id, '_atlas_city', true); // مقدار متای خاص

    // // نمایش اطلاعات
    // echo '<h2>' . $title . '</h2>';
    // echo '<p>' . $content . '</p>';
    // echo '<p><strong>خلاصه:</strong> ' . $excerpt . '</p>';
    // echo '<p><strong>نویسنده:</strong> ' . get_the_author_meta('display_name', $author_id) . '</p>';
    // echo '<p><strong>تاریخ انتشار:</strong> ' . $publish_date . '</p>';
    // echo '<p><strong>مقدار متای _atlas_city:</strong> ' . $meta_value . '</p>';

} else {
    echo '<p>پستی با این آیدی پیدا نشد.</p>';
}
?>

</div>


<?php get_footer(); ?>