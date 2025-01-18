<?php get_header(); ?>

<?php

    if (have_posts()) {
        while (have_posts()) {
            the_post();
        ?>



<div class="container" style="direction: rtl;">



    <?php $post_id = get_the_ID();

                // گرفتن اطلاعات پست
                $post = get_post($post_id);

                if ($post) {

                    $responsible = get_post_meta($post_id, '_atlas_responsible', true);
                    $mobile      = get_post_meta($post_id, '_atlas_responsible-mobile', true);
                    $center_mode = get_post_meta($post_id, '_atlas_center-mode', true);
                    $center_type = get_post_meta($post_id, '_atlas_center-type', true);
                    $phone       = get_post_meta($post_id, '_atlas_phone', true);
                    $ostan       = get_post_meta($post_id, '_atlas_ostan', true);
                    $city        = get_post_meta($post_id, '_atlas_city', true);
                    $map         = get_post_meta($post_id, '_atlas_map', true);
                    $address     = get_post_meta($post_id, '_atlas_address', true);
                    $link        = get_post_meta($post_id, '_atlas_link', true);
                    $gender      = get_post_meta($post_id, '_atlas_gender', true);
                    $age         = get_post_meta($post_id, '_atlas_age', true);
                    $contacts    = get_post_meta($post_id, '_atlas_contacts', true);
                    $course_type = get_post_meta($post_id, '_atlas_course-type', true);
                    $subject     = get_post_meta($post_id, '_atlas_subject', true);
                    $coaches     = get_post_meta($post_id, '_atlas_coaches', true);
                    $teacher     = get_post_meta($post_id, '_atlas_teacher', true);
                    $ayeha       = get_post_meta($post_id, '_atlas_ayeha', true);
                $operator    = get_post_meta($post_id, '_operator', true); ?>






    <h1>عنوان موسسه :                                <?php echo get_the_title($post_id) ?></h1>
    <p>نام مسئول :                           <?php echo $responsible ?></p>
    <p>حالت مرکز :                           <?php echo $center_mode ?></p>
    <p>نوع مرکز :                         <?php echo $center_type ?></p>
    <p>شماره ارتباط با مرکز :                                               <?php echo $phone ?></p>
    <p>استان :                    <?php echo $ostan ?></p>
    <p>شهر :                <?php echo $city ?></p>
    <p>نقشه :                  <?php echo implode(",", $map) ?></p>
    <p>آدرس :                  <?php echo $address ?></p>
    <p>لینک سایت / فضای مجازی :                                                 <?php echo print_r($link, true) ?></p>
    <p>جنسیت هدف :                           <?php echo print_r($gender, true) ?></p>
    <p>گروه سنی :                         <?php echo print_r($age, true) ?></p>
    <p>تعداد مخاطبین :                                   <?php echo $contacts ?></p>
    <p>قالب برگزیده دوره ها :                                               <?php echo print_r($course_type, true) ?></p>
    <p>محتوا و موضوع :                                  <?php echo $subject ?></p>
    <p>تعداد مربیان :                                 <?php echo $subject ?></p>
    <p>اساتید برجسته :                                   <?php echo print_r($teacher, true) ?></p>
    <p>مرکز در سایت زندگی با آیه ها نمایش :                                                                       <?php echo $ayeha ?></p>
    <p>توضیحات بیشتر :                                   <?php echo apply_filters('the_content', $post->post_content) ?></p>











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
            }?>

</div>













<?php
    }
    }

?>








<?php get_footer(); ?>