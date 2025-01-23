<?php get_header();

    $iran = new Iran_Area;

    if (have_posts()) {
        while (have_posts()) {
            the_post();

            $post_id = get_the_ID();

            // گرفتن اطلاعات پست
            $post = get_post($post_id);

            if ($post) {
                $img = has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'medium') : atlas_panel_image('default.png');

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
                $operator    = get_post_meta($post_id, '_operator', true);
                $content     = sanitize_text_field(apply_filters('the_content', $post->post_content));

                $this_city = $iran->one_city(absint($city));

                $city_id       = $this_city[ 'city_id' ];
                $city_neme     = $this_city[ 'city' ];
                $province_id   = $this_city[ 'province_id' ];
                $province_neme = $this_city[ 'province' ];

                $responsible = ($responsible) ? $responsible : '';
                $mobile      = ($mobile) ? $mobile : '';
                $center_mode = ($center_mode) ? $center_mode : 'public';
                $center_type = ($center_type) ? $center_type : 'Institute';
                $phone       = ($phone) ? $phone : '';
                $ostan       = ($ostan) ? $ostan : 0;
                $city        = ($city) ? $city : 0;
                $address     = ($address) ? $address : '';
                $link        = (is_array($link)) ? $link : [ 'site' => '', 'eitaa' => '', 'bale' => '', 'rubika' => '', 'telegram' => '', 'instagram' => '' ];
                $gender      = (is_array($gender)) ? $gender : [  ];
                $age         = (is_array($age)) ? $age : [  ];
                $contacts    = ($contacts) ? $contacts : '';
                $course_type = (is_array($course_type)) ? $course_type : [  ];
                $subject     = ($subject) ? $subject : '';
                $coaches     = ($coaches) ? $coaches : '';
                $teacher     = (is_array($teacher)) ? $teacher : '';
                $ayeha       = ($ayeha) ? $ayeha : 'no';

                switch ($center_type) {
                    case 'Institute':
                        $center_type = 'موسسه';
                        break;
                    case 'house_of_quran':
                        $center_type = 'خانه قرآن';
                        break;
                    case 'mohfel':
                        $center_type = 'محفل';
                        break;
                    case 'education':
                        $center_type = 'آموزش پرورش';
                        break;
                    case 'besij':
                        $center_type = 'دارالقرآن بسیج';
                        break;

                    default:
                        $center_type = 'نامشخص';

                        break;
                }

                $translations_course_type = [
                    'online'  => 'حضوری',
                    'offline' => 'مجازی',
                 ];
                if (is_array($course_type)) {
                    $translated_course_type = array_map(function ($word) use ($translations_course_type) {
                        return $translations_course_type[ $word ] ?? $word;
                    }, $course_type);
                }

                $translations_age = [
                    '7'   => 'زیر 7 سال',
                    '12'  => '7 تا 12 سال',
                    '18'  => '12 تا 18 سال',
                    'old' => '18 سال به بالا',
                 ];

                if (is_array($age)) {
                    $translated_age = array_map(function ($word) use ($translations_age) {
                        return $translations_age[ $word ] ?? $word;
                    }, $age);
                }

                $translations_age = [
                    'woman' => 'خواهران',
                    'man'   => 'برادران',
                 ];

                if (is_array($gender)) {

                    $translated_gender = array_map(function ($word) use ($translations_age) {
                        return $translations_age[ $word ] ?? $word;
                    }, $gender);
                }

                $points = [  ];

                if (! empty($map[ 'lat' ]) && ! empty($map[ 'lng' ])) {

                    $info = '<div style="text-align: center;">
                                <h5>' . get_the_title() . '</h5>
                                <img src="' . $img . '" alt="' . get_the_title() . '" style="width: 100%; max-width: 150px; border-radius: 8px;">
                                <p>تعداد مربی:' . $coaches . '</p>
                                <p>تعداد قرآن‌آموز:' . $contacts . '</p>
                            </div>';

                    $points = [
                        "lat"  => $map[ 'lat' ],
                        "lng"  => $map[ 'lng' ],
                        "info" => $info,

                     ];
                }

                // گرفتن نظرات مرتبط با پست
                $comments = get_comments([
                    'post_id' => $post_id,
                    'status'  => 'approve', // فقط نظرات تایید شده
                 ]);
                $sum_rating = 0;
                foreach ($comments as $comment) {

                    $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
                    $sum_rating += $rating ;
                }

                $avg_rating =($sum_rating) ? ceil(($sum_rating / count($comments))) : 0;
            ?>



<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>

<div class="container-fluid">
    <div class="institute-head-box px-4 py-3 atlas-row d-flex flex-column gap-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between">
            <div class="breadcrumbs text-white d-flex flex-wrap gap-2  align-content-center justify-content-center">
                <img src="<?php echo atlas_panel_image('home-icone.svg') ?>">
                <a class="text-white" href="/">خانه</a>
                <img src="<?php echo atlas_panel_image('arrow.svg') ?>">
                <a class="text-white"
                    href="<?php echo atlas_base_url('province=' . $province_id) ?>"><?php echo $province_neme ?></a>
                <img class="search-button" src="<?php echo atlas_panel_image('arrow.svg') ?>">
                <a class="text-white"
                    href="<?php echo atlas_base_url('city=' . $city_id) ?>/"><?php echo $city_neme ?></a>
                <img class="search-button" src="<?php echo atlas_panel_image('arrow.svg') ?>">
                <span class="text-white-50"><?php echo get_the_title($post_id) ?></span>
            </div>
        </div>

        <div class="d-flex justify-content-center my-2">
            <div class="p-2">
                <img class="mb-2 rounded-circle w-100" src="<?php echo $img ?>">

                <div class="px-3 py-2 text-center fw-bold text-white">
                    <span><?php echo get_the_title($post_id) ?></span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between">
            <div class="d-flex gap-2 flex-wrap  col-12 col-sm-12 col-md-6 align-content-center">
                <div class="bg-white rounded px-3 py-1 text-center col-12 col-sm-6 col-md-4">
                    <img src="<?php echo atlas_panel_image('amooz.svg') ?>">
                    <span><?php echo number_format(absint($contacts)) ?> قرآن آموز</span>
                </div>

                <div class="bg-white rounded px-3 py-1 text-center col-12 col-sm-6 col-md-4">
                    <img src="<?php echo atlas_panel_image('teacher.svg') ?>">
                    <span><?php echo number_format(absint($coaches)) ?> مربی</span>
                </div>
            </div>

            <div class="d-flex flex-row align-items-center justify-content-center text-white mt-2 mt-sm-0">
                <span><?php echo count($comments)?> نظر, <b><?=$avg_rating?></b></span>
                <img src="<?php echo atlas_panel_image('star.svg') ?>" style="width: 26px;">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-2">

    <div class="atlas-row d-flex flex-row gap-2">
        <div class="col-3 d-none d-sm-block px-2 ">

            <div class="border border-1 border-warning rounded-2 p-3 position-sticky atlas-table-parent">
                <div data-block="info" class="rounded px-3 py-2 mb-2 atlas-table-list atlas-active">
                    <img src="<?php echo atlas_panel_image('info-t.svg') ?>"> <b>درباره موسسه</b>
                </div>
                <div data-block="subject" class="rounded px-3 py-2  mb-2 atlas-table-list">
                    <img src="<?php echo atlas_panel_image('bord-t.svg') ?>"> <b>دوره ها</b>
                </div>

                <div data-block="teacher" class="rounded px-3 py-2  mb-2 atlas-table-list">
                    <img src="<?php echo atlas_panel_image('teacher-t.svg') ?>"> <b>مربیان</b>
                </div>

                <div data-block="contact" class="rounded px-3 py-2  mb-2 atlas-table-list">
                    <img src="<?php echo atlas_panel_image('contact-t.svg') ?>"> <b>ارتباط با موسسه</b>
                </div>

                <div data-block="address" class="rounded px-3 py-2  mb-2 atlas-table-list">
                    <img src="<?php echo atlas_panel_image('address-t.svg') ?>"> <b>آدرس موسسه</b>
                </div>

                <div data-block="comment" class="rounded px-3 py-2  mb-2 atlas-table-list">
                    <img src="<?php echo atlas_panel_image('comment-t.svg') ?>"> <b>نظرات</b>
                </div>
            </div>
        </div>
        <div class="col-md-9  col-12 ">

            <div id="info" class="mt-2">
                <div class="institute-info px-4 py-3">
                    <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('info.svg') ?>"> درباره موسسه
                    </p>
                    <hr class="">

                    <div class="row atlas-block-info">
                        <div
                            class="col-12 col-md-4 d-flex flex-row justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1 ">نام مسئول </b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo $responsible; ?>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-12 col-md-4 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1 ">حالت مرکز </b>
                            <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo($center_mode == 'public') ? 'عمومی' : 'خصوصی'; ?>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-12 col-md-4 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1 ">نوع مرکز </b>
                            <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo $center_type; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (! empty($content)): ?>
                    <hr class="">

                    <div class="row atlas-block-info">
                        <div
                            class="col-12 d-flex flex-row justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1 ">توضیحات بیشتر</b>
                            <div class="d-flex flex-row justify-content-start align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold text-justify">
                                    <?php echo $content; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="subject" class="mt-3">
                <div class="institute-info px-4 py-3">
                    <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('bord.svg') ?>"> دوره ها</p>
                    <hr class="">

                    <div class="row atlas-block-info">
                        <div
                            class="col-12 col-sm-6 col-md-5 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">جنسیت قرآن آموزان: </b>
                            <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                                <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique($translated_gender))) ?>
                            </div>
                        </div>
                        <div
                            class="col-12 col-sm-6 col-md-7 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">مقاطع سنی: </b>
                            <div
                                class="d-flex flex-wrap gap-2 justify-content-end justify-content-md-start align-items-center">
                                <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique($translated_age))) ?>
                            </div>
                        </div>
                        <div
                            class="col-12 col-sm-6 col-md-6 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1 ">نحوه برگزاری کلاس ها: </b>
                            <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                                <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique($translated_course_type))) ?>
                            </div>
                        </div>


                    </div>
                    <hr class="">
                    <div class="row atlas-block-info">
                        <?php foreach (sanitize_text_no_item(array_unique(explode(',', $subject))) as $value): ?>
                        <div class=" atlas-subject d-flex flex-column p-5 gap-3 m-1 rounded-3 align-content-center">
                            <div class="atlas-subject-head">
                                <img src="<?php echo atlas_panel_image('subject-active.svg') ?>">
                                <b>دوره فعال</b>
                            </div>
                            <div class="atlas-subject-title text-white fw-bold"><?php echo $value ?></div>
                        </div>
                        <?php endforeach; ?>

                    </div>


                </div>
            </div>

            <div id="teacher" class="mt-3">
                <div class="institute-info px-4 py-3">
                    <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('teacher-i.svg') ?>"> مربیان
                    </p>
                    <hr class="">

                    <div class="row atlas-block-info">
                        <div
                            class="col-12 col-sm-6 col-md-4 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">تعداد مربیان</b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo number_format(absint($coaches)) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="">
                    <?php if (! empty($teacher)): ?>
                    <div class="row atlas-block-info">
                        <div
                            class="col-12 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <div class="d-flex flex-row justify-content-center align-items-center gap-1">
                                <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique($teacher))) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="contact" class="mt-3">
                <div class="institute-info px-4 py-3">
                    <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('phone-i.png') ?>"> ارتباط با
                        موسسه</p>
                    <hr class="">

                    <div class="row atlas-block-info">
                        <div
                            class="col-12 col-sm-6 col-md-4 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">شماره موبایل مسئول</b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo $mobile ?>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-12 col-sm-6 col-md-4 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">شماره ارتباط با موسسه</b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo $phone ?>
                                </div>
                            </div>
                        </div>
                        <?php if (! empty($link[ 'site' ])): ?>

                        <div
                            class="col-12 col-sm-6 col-md-4 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">ادرس وب سایت</b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <a href="<?php echo $link[ 'site' ] ?> "
                                        title="وب سایت"><?php echo $link[ 'site' ] ?></a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <hr class="">

                    <div class="row atlas-block-info">
                        <div
                            class="col-12 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">شبکه های مجازی موسسه</b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div
                                    class="text-white atlas-share d-flex flex-wrap gap-2 align-content-center justify-content-center justify-content-md-start">
                                    <?php if (! empty($link[ 'instagram' ])): ?>
                                    <a href="<?php echo $link[ 'instagram' ] ?>" title="کانال اینستاگرام"
                                        class="p-1 border border-warning rounded-circle"><img class="m-1"
                                            src="<?php echo atlas_panel_image('instagram.png') ?>"></a>
                                    <?php endif; ?>
                                    <?php if (! empty($link[ 'telegram' ])): ?>
                                    <a href="<?php echo $link[ 'telegram' ] ?>" title="کانال تلگرام"
                                        class="p-1 border border-warning rounded-circle"><img class="m-1"
                                            src="<?php echo atlas_panel_image('telegram.png') ?>"></a>
                                    <?php endif; ?>
                                    <?php if (! empty($link[ 'rubika' ])): ?>
                                    <a href="<?php echo $link[ 'rubika' ] ?>" title="کانال روبیکا"
                                        class="p-1 border border-warning rounded-circle"><img class="m-1"
                                            src="<?php echo atlas_panel_image('rubika.png') ?>"></a>
                                    <?php endif; ?>
                                    <?php if (! empty($link[ 'bale' ])): ?>
                                    <a href="<?php echo $link[ 'bale' ] ?>" title="کانال بله"
                                        class="p-1 border border-warning rounded-circle"><img class="m-1"
                                            src="<?php echo atlas_panel_image('bale.png ') ?>"></a>
                                    <?php endif; ?>
                                    <?php if (! empty($link[ 'eitaa' ])): ?>
                                    <a href="<?php echo $link[ 'eitaa' ] ?>" title="کانال ایتا"
                                        class="p-1 border border-warning rounded-circle"><img class="m-1"
                                            src="<?php echo atlas_panel_image('eitaa.png') ?>"></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="address" class="mt-3">
                <div class="institute-info px-4 py-3">
                    <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('address.png') ?>"> آدرس موسسه
                    </p>
                    <hr class="">

                    <div class="row atlas-block-info">
                        <div
                            class="col-12 col-sm-6 col-md-3 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">استان</b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo $province_neme ?>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-12 col-sm-6 col-md-3 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">شهر</b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo $city_neme ?>
                                </div>
                            </div>
                        </div>

                        <div
                            class="col-12 col-sm-6 col-md-6 d-flex flex-row gap-2 justify-content-between justify-content-md-start align-items-center mb-3">
                            <b class="px-3 py-1">آدرس</b>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                                    <?php echo $address ?>
                                </div>
                            </div>
                        </div>




                    </div>
                    <hr class="">

                    <div class="row atlas-block-info">
                        <div id="map-city" class="rounded-4" style="height: 300px; max-height: 500px;"></div>

                    </div>
                </div>
            </div>

            <div id="comment" class="mt-3">
                <div class="institute-info px-4 py-3">
                    <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('comment.png') ?>"> نظرات</p>
                    <hr class="">
                    <div class="row atlas-block-info">
                        <?php comment_form(); ?>
                        <hr>
                        <?php display_commenters_list($comments); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>












<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>




<script>
// نقشه را مقداردهی اولیه کنید
const mapCity = L.map('map-city', {
    center: [35.6892, 51.3890], // مختصات اولیه
    zoom: 6, // زوم اولیه
    scrollWheelZoom: false, // غیرفعال کردن زوم با اسکرول
    touchZoom: true // فعال کردن زوم لمسی (موبایل)
});

// اضافه کردن لایه نقشه
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
}).addTo(mapCity);




const markerImage = "<?php echo atlas_panel_image('marker.png') ?>";

const province = "<?php echo $province_neme ?>";

const points = <?php echo json_encode($points); ?>;

const city = "<?php echo $city_neme ?>";

const query = `${city}, ${province}`;

// فعال کردن زوم اسکرول فقط با دکمه کنترل روی کامپیوتر
mapCity.on('keydown', (event) => {
    if (event.originalEvent.key === "Control") {
        mapCity.scrollWheelZoom.enable(); // فعال کردن زوم با اسکرول
    }
});

// غیرفعال کردن زوم اسکرول بعد از برداشتن کلید کنترل
mapCity.on('keyup', (event) => {
    if (event.originalEvent.key === "Control") {
        mapCity.scrollWheelZoom.disable(); // غیرفعال کردن زوم با اسکرول
    }
});

// فعال کردن زوم لمسی فقط با دو انگشت
mapCity.touchZoom.enable(); // فعال کردن زوم لمسی
mapCity.touchZoom = {
    pinchZoomOnly: true // فقط با دو انگشت
};


const url =
    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&polygon_geojson=1&extratags=1`;

fetch(url)
    .then(response => response.json())
    .then(data => {


        if (data.length > 0) {

            if (points.lat === undefined) {
                const {
                    lat,
                    lon
                } = data[0];

                mapCity.setView([lat, lon], 13);

            } else {

                // کامنت برای تغییر مارکر:
                const customIcon = L.icon({
                    iconUrl: markerImage, // مسیر آیکون سفارشی
                    iconSize: [48, 48], // اندازه آیکون
                    iconAnchor: [24, 48], // نقطه لنگر آیکون
                });

                const marker = L.marker([points.lat, points.lng], {
                    icon: customIcon
                }).addTo(mapCity);

                mapCity.setView([points.lat, points.lng], 16); // تنظیم زوم روی نقطه

            }

        } else {
            alert("استان یافت نشد!");
        }
    })
    .catch(error => console.error("خطا در دریافت اطلاعات:", error));





document.querySelectorAll('.atlas-table-list').forEach(item => {
    item.addEventListener('click', function() {
        const dataBlock = this.getAttribute('data-block');
        console.log(dataBlock);



        document.querySelectorAll('.atlas-table-list').forEach(el => {
            el.classList.remove('atlas-active');
        });

        this.classList.add('atlas-active');





        const mapSection = document.getElementById(dataBlock);
        mapSection.scrollIntoView({
            behavior: 'smooth'
        });
    });
});
</script>



<?php

            } else {
                echo '<p>پستی با این آیدی پیدا نشد.</p>';
        }?>
<?php
    }
    }
    function custom_institute_comment_form($defaults)
    {
        $defaults[ 'fields' ][ 'stars' ] = '
            <p class="comment-form-stars">
                <label for="rating">امتیاز شما:</label>
                <span class="rating">
                    <input type="radio" name="rating" value="5" id="star5"><label for="star5">☆</label>
                    <input type="radio" name="rating" value="4" id="star4"><label for="star4">☆</label>
                    <input type="radio" name="rating" value="3" id="star3"><label for="star3">☆</label>
                    <input type="radio" name="rating" value="2" id="star2"><label for="star2">☆</label>
                    <input type="radio" name="rating" value="1" id="star1"><label for="star1">☆</label>
                </span>
            </p>';
        return $defaults;
    }
    add_filter('comment_form_defaults', 'custom_institute_comment_form');

?>








<?php get_footer(); ?>