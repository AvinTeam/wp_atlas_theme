<?php

    function atlas_title_filter_dashboard($title)
    {

        $title = get_bloginfo('name') . " | پنل کاربری";
        return $title;
    }
    add_filter('wp_title', 'atlas_title_filter_dashboard');

    $args = [
        'author'         => get_current_user_id(),                                  // شناسه نویسنده
        'post_type'      => 'institute',                                            // نوع پست (می‌تونی 'page' یا نوع‌های کاستوم هم بگذاری)
        'post_status'    => [ 'publish', 'draft', 'pending', 'private', 'future' ], // همه وضعیت‌ها
        'posts_per_page' => 1,                                                      // تعداد پست‌ها (برای همه پست‌ها: -1)
        'fields'         => 'ids',                                                  // برگرداندن فقط شناسه‌ها
     ];

    // اجرای کوئری
    $query = new WP_Query($args);

    // گرفتن داده‌های پست‌ها
    if ($query->have_posts()) {
        $posts = [  ];

        while ($query->have_posts()) {
            $query->the_post();

            $post_id          = get_the_ID();
            $post_title       = get_the_title();
            $post_description = get_the_excerpt();
            $status           = get_post_status();

        }

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

        // ریست کردن کوئری
        wp_reset_postdata();
    } else {
        echo 'پستی برای این کاربر وجود ندارد.';
    }

    $irandb = new Iran_Area;
    $ostan  = $irandb->select(0);
    $city   = (absint($atlas_institute[ 'ostan' ])) ? $irandb->select($atlas_institute[ 'ostan' ]) : [  ];

get_header(); ?>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
<style>
.tagify__input {
    direction: rtl;
    text-align: right;
}

.tagify__tag {
    direction: rtl;
    text-align: right;
}
</style>



<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <a class="navbar-brand" href="#">داشبورد</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">بازگشت به صفحه نخست</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?=atlas_base_url('logout')?>">خروج</a>
            </li>
        </ul>
    </div>
</nav>

<?php if ($status != "publish"):

        switch ($status) {
            case 'draft':
                $status_message = 'پیش‌نویس';
                break;
            case 'pending':
                $status_message = 'در انتظار بررسی';
                break;
            case 'private':
                $status_message = 'خصوصی';
                break;
            case 'future':
                $status_message = 'منتظر زمان‌بندی';
                break;
            default:
                $status_message = 'نامشخص';
        }

    ?>


<div class="container mt-5">
    <div class="alert alert-warning" role="alert">
    مرکز قرآنی شما در وضعیت <b><?php echo $status_message ?></b> میباشد بعد از تایید مدیریت نمایش داده خواهد شد.
    </div>
</div>




<?php endif; ?>
<div class="container mt-5">
    <h2 class="mb-3">مرکز قرآنی <?php echo $post_title ?> </h2>
    <form action="" method="post">
        <div class="form-group mt-2">
            <label for="title">عنوان مرکز قرآنی</label>
            <input type="text" class="form-control mt-2" id="title" name="title" value="<?php echo $post_title ?>">
        </div>
        <div class="form-group mt-2">
            <label for="title">نام مسئول</label>
            <input type="text" class="form-control mt-2" id="title" name="atlas[responsible]"
                value="<?php echo $atlas_institute[ 'responsible' ] ?>">
        </div>
        <div class="form-group mt-2">
            <label for="center-mode">حالت مرکز</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="atlas[center-mode]" value="public" id="public"
                        <?php echo checked('public', $atlas_institute[ 'center-mode' ]) ?>>
                    <label class="form-check-label" for="public">عمومی</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="atlas[center-mode]" value="private" id="private"
                        <?php echo checked('private', $atlas_institute[ 'center-mode' ]) ?>>
                    <label class="form-check-label" for="private">خصوصی</label>
                </div>
            </div>
        </div>
        <div class="form-group mt-2">
            <label for="title">نوع مرکز</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="Institute" name="atlas[center-type]"
                        value="Institute" <?php echo checked('Institute', $atlas_institute[ 'center-type' ]) ?>>
                    <label class="form-check-label" for="Institute">موسسه</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="house_of_quran" name="atlas[center-type]"
                        value="house_of_quran"
                        <?php echo checked('house_of_quran', $atlas_institute[ 'center-type' ]) ?>>
                    <label class="form-check-label" for="house_of_quran">خانه قرآن</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="mohfel" name="atlas[center-type]" value="mohfel"
                        <?php echo checked('mohfel', $atlas_institute[ 'center-type' ]) ?>>
                    <label class="form-check-label" for="mohfel">محفل</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="education" name="atlas[center-type]"
                        value="education" <?php echo checked('education', $atlas_institute[ 'center-type' ]) ?>>
                    <label class="form-check-label" for="education">آموزش پرورش</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="besij" name="atlas[center-type]" value="besij"
                        <?php echo checked('besij', $atlas_institute[ 'center-type' ]) ?>>
                    <label class="form-check-label" for="besij">پایگاه قرآنی مساجد</label>
                </div>
            </div>
        </div>
        <div class="form-group mt-2">
            <label for="phone">شماره ارتباط با مرکز</label>
            <input type="text" class="form-control mt-2 onlyNumbersInput" id="phone" name="atlas[phone]"
                value="<?php echo $atlas_institute[ 'phone' ] ?>" aria-describedby="phone-description">
            <p class="description" id="phone-description">با کد استان و بدون اعلائم اضافه ثبت شود</p>
        </div>

        <div class="form-group mt-2">
            <label for="ostan">استان</label>
            <div>
                <select name="atlas[ostan]" id="ostan" class="form-select select2map">
                    <option value="0">انتخاب استان</option>
                    <?php foreach ($ostan as $key => $value): ?>
                    <option value="<?php echo $value->id ?>"
                        <?php echo selected($value->id, $atlas_institute[ 'ostan' ]) ?>>
                        <?php echo $value->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group mt-2">
            <label for="city">شهر</label>
            <div>
                <select name="atlas[city]" id="city" class="form-select select2map">
                    <option value="0">انتخاب شهرستان</option>
                    <?php foreach ($city as $key => $value): ?>
                    <option value="<?php echo $value->id ?>"
                        <?php echo selected($value->id, $atlas_institute[ 'city' ]) ?>>
                        <?php echo $value->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group mt-2">
            <label for="city">موقعیت مکانی</label>
            <div class="mt-2">
                <input type="hidden" name="atlas[map][lat]" id="map-lat"
                    value="<?php echo $atlas_institute[ 'map' ][ 'lat' ] ?>">
                <input type="hidden" name="atlas[map][lng]" id="map-lng"
                    value="<?php echo $atlas_institute[ 'map' ][ 'lng' ] ?>">
                <div style=" width: 100%;height: 500px;" id="map"></div>
            </div>
        </div>
        <div class="form-group mt-2">
            <label for="atlas-address">آدرس</label>
            <textarea class="form-control large-text" name="atlas[address]" rows="5" cols="50"
                id="atlas-address"><?php echo $atlas_institute[ 'address' ] ?></textarea>
        </div>
        <div class="form-group mt-2">
            <label for="link">لینک سایت / فضای مجازی</label>

            <div class="form-group mt-2">
                <label for="site">آدرس سایت</label>
                <input type="text" class="form-control mt-2" id="site" name="atlas[link][site]"
                    value="<?php echo $atlas_institute[ 'link' ][ 'site' ] ?> ">
            </div>
            <div class="form-group mt-2">
                <label for="eitaa">کانال ایتا</label>
                <input type="text" class="form-control mt-2" id="eitaa" name="atlas[link][eitaa]"
                    value="<?php echo $atlas_institute[ 'link' ][ 'eitaa' ] ?>">
            </div>
            <div class="form-group mt-2">
                <label for="bale">کانال بله</label>
                <input type="text" class="form-control mt-2" id="bale" name="atlas[link][bale]"
                    value="<?php echo $atlas_institute[ 'link' ][ 'bale' ] ?>">
            </div>
            <div class="form-group mt-2">
                <label for="rubika">کانال روبیکا</label>
                <input type="text" class="form-control mt-2" id="rubika" name="atlas[link][rubika]"
                    value="<?php echo $atlas_institute[ 'link' ][ 'rubika' ] ?>">
            </div>
            <div class="form-group mt-2">
                <label for="telegram">کانال تلگرام</label>
                <input type="text" class="form-control mt-2" id="telegram" name="atlas[link][telegram]"
                    value="<?php echo $atlas_institute[ 'link' ][ 'telegram' ] ?>">
            </div>
            <div class="form-group mt-2">
                <label for="instagram">کانال اینستاگرام</label>
                <input type="text" class="form-control mt-2" id="instagram" name="atlas[link][instagram]"
                    value="<?php echo $atlas_institute[ 'link' ][ 'instagram' ] ?>">
            </div>
        </div>
        <div class="form-group mt-2">
            <label for="gender">جنسیت هدف</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="atlas[gender][]" type="checkbox" id="gender-woman"
                        value="woman" <?php if (in_array('woman', $atlas_institute[ 'gender' ])) {echo 'checked';}?>>
                    <label class="form-check-label" for="gender-woman">خانم</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="atlas[gender][]" type="checkbox" id="gender-man" value="man"
                        <?php if (in_array('man', $atlas_institute[ 'gender' ])) {echo 'checked';}?>>
                    <label class="form-check-label" for="gender-man">آقا</label>
                </div>
            </div>
        </div>
        <div class="form-group mt-2">
            <label for="title">گروه سنی</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="atlas[age][]" type="checkbox" id="age-7" value="7"
                        <?php if (in_array('7', $atlas_institute[ 'age' ])) {echo 'checked';}?>>
                    <label class="form-check-label" for="age-7">زیر 7 سال</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="atlas[age][]" type="checkbox" id="age-12" value="12"
                        <?php if (in_array('12', $atlas_institute[ 'age' ])) {echo 'checked';}?>>
                    <label class="form-check-label" for="age-12">7 تا 12 سال</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="atlas[age][]" type="checkbox" id="age-18" value="18"
                        <?php if (in_array('18', $atlas_institute[ 'age' ])) {echo 'checked';}?>>
                    <label class="form-check-label" for="age-18">12 تا 18 سال</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="atlas[age][]" type="checkbox" id="age-old" value="old"
                        <?php if (in_array('old', $atlas_institute[ 'age' ])) {echo 'checked';}?>>
                    <label class="form-check-label" for="old">18 سال به بالا</label>
                </div>
            </div>
        </div>


        <div class="form-group mt-2">
            <label for="contacts">تعداد مخاطبین</label>
            <input type="text" id="contacts" class="form-control mt-2 onlyNumbersInput" name="atlas[contacts]"
                value="<?php echo $atlas_institute[ 'contacts' ] ?>">
        </div>


        <div class="form-group mt-2">
            <label for="title">قالب برگزیده دوره ها</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="atlas[course-type][]" type="checkbox" id="course-type-online"
                        value="online"
                        <?php if (in_array('online', $atlas_institute[ 'course-type' ])) {echo 'checked';}?>>
                    <label class="form-check-label" for="course-type-online">حضوری</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="atlas[course-type][]" type="checkbox" id="course-type-offline"
                        value="offline"
                        <?php if (in_array('offline', $atlas_institute[ 'course-type' ])) {echo 'checked';}?>>
                    <label class="form-check-label" for="course-type-offline">مجازی</label>
                </div>
            </div>
        </div>






        <div class="form-group mt-2">
            <label for="subject">محتوا و موضوع</label>
            <div class="row">
                <div class="col-12 mt-2">
                    <input type="text" class="form-control mt-2" name="atlas[subject]" id="subject"
                        value="<?php echo $atlas_institute[ 'subject' ] ?>">
                </div>
                <div class=" col-12 mt-2" id="all_subject">
                    <span>روخوانی</span>
                    <span>حفظ</span>
                    <span>ترجمه</span>
                    <span>تدبر و تفسیر</span>
                    <span>قرائت</span>
                    <span>تجوید</span>
                    <span>صوت و لحن</span>
                    <span>مفاهیم و موضوعات</span>
                </div>
            </div>
        </div>


        <div class="form-group mt-2">
            <label for="coaches">تعداد مربیان</label>
            <input type="text" id="coaches" class="form-control mt-2 onlyNumbersInput" name="atlas[coaches]"
                value="<?php echo $atlas_institute[ 'coaches' ] ?>">
        </div>

        <div class="form-group mt-2">
            <label for="coaches">اساتید برجسته</label>
            <div class="row">
                <div class="col-12 teacher_list">
                    <?php foreach ($atlas_institute[ 'teacher' ] as $teacher): ?>
                    <div class="atlas-teacher-row row mb-2">
                        <div class="col-10"><input class=" form-control " name="atlas[teacher][]"
                                value="<?php echo $teacher ?>"></div>
                        <button type="button" class="btn btn-danger atlas-teacher-remove col-2">حذف</button>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
            <div class="col-12">

                <button type="button" class="btn btn-success atlas-teacher-add">افزودن</button>
            </div>
        </div>




        <div class="form-group mt-2">
            <label for="ayeha">مرکز در سایت زندگی با آیه ها نمایش</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="yes" name="atlas[ayeha]" value="yes"
                        <?php echo checked('yes', $atlas_institute[ 'ayeha' ]) ?>>
                    <label class="form-check-label" for="yes">بله</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="no" name="atlas[ayeha]" value="no"
                        <?php echo checked('no', $atlas_institute[ 'ayeha' ]) ?>>
                    <label class="form-check-label" for="no">خیر</label>
                </div>
            </div>
        </div>

        <div class="form-group mt-2">
            <label for="atlas-address">توضیحات بیشتر</label>
            <?php
                wp_editor($post_description, 'content', [
                    'textarea_name' => 'post_content',
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                 ]);
            ?>
        </div>
        <?php wp_nonce_field('atlas_nonce_user_submit' . get_current_user_id()); ?>

        <div class="form-group mt-2">
            <button type="submit" class="btn btn-primary" name="act_user" value="form_submit">ارسال</button>
        </div>
    </form>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-white mt-5">
    <a class="navbar-brand" href="#">1404</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

</nav>
<?php get_footer(); ?>