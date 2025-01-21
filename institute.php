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
            <div class="text-white atlas-share d-flex flex-wrap gap-2 align-content-center justify-content-center">
                <?php if (! empty($link[ 'instagram' ])): ?>
                <a href="<?php echo $link[ 'instagram' ] ?>" class="p-1 border border-warning rounded-circle"><img
                        class="m-1" src="<?php echo atlas_panel_image('instagram.png') ?>"></a>
                <?php endif; ?>
                <?php if (! empty($link[ 'telegram' ])): ?>
                <a href="<?php echo $link[ 'telegram' ] ?>" class="p-1 border border-warning rounded-circle"><img
                        class="m-1" src="<?php echo atlas_panel_image('telegram.png') ?>"></a>
                <?php endif; ?>
                <?php if (! empty($link[ 'rubika' ])): ?>
                <a href="<?php echo $link[ 'rubika' ] ?>" class="p-1 border border-warning rounded-circle"><img
                        class="m-1" src="<?php echo atlas_panel_image('rubika.png') ?>"></a>
                <?php endif; ?>
                <?php if (! empty($link[ 'bale' ])): ?>
                <a href="<?php echo $link[ 'bale' ] ?>" class="p-1 border border-warning rounded-circle"><img
                        class="m-1" src="<?php echo atlas_panel_image('bale.png') ?>"></a>
                <?php endif; ?>
                <?php if (! empty($link[ 'eitaa' ])): ?>
                <a href="<?php echo $link[ 'eitaa' ] ?>" class="p-1 border border-warning rounded-circle"><img
                        class="m-1" src="<?php echo atlas_panel_image('eitaa.png') ?>"></a>
                <?php endif; ?>
                <?php if (! empty($link[ 'site' ])): ?>
                <a href="<?php echo $link[ 'site' ] ?>" class="p-1 border border-warning rounded-circle"><img
                        class="m-1" src="<?php echo atlas_panel_image('web.png') ?>"></a>
                <?php endif; ?>
                <?php if (! empty($phone)): ?>
                <a href="tel:<?php echo $phone ?>" class="p-1 border border-warning rounded-circle"><img class="m-1"
                        src="<?php echo atlas_panel_image('phone.png') ?>"></a>
                <?php endif; ?>
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
                <span>20 نظر, (از 40رای) <b>4.5</b></span>
                <img src="<?php echo atlas_panel_image('star.svg') ?>" style="width: 26px;">
            </div>
        </div>
    </div>
</div>



<div class="container-fluid mt-2 institute-info">
    <div class="atlas-row px-4 py-3">
        <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('info.svg') ?>"> درباره موسسه</p>
        <hr class="">

        <div class="row">
            <div class="col-12 col-sm-6 col-md-6 d-flex flex-row gap-2 justify-content-start align-items-center mb-3">
                <b class="px-3 py-1 ">نوع موسسه: </b>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <div class="px-3 py-1 rounded bg-white atlas_page_item fw-bold">
                        <?php echo($center_mode == 'public') ? 'عمومی' : 'خصوصی'; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 d-flex flex-row gap-2 justify-content-start align-items-center mb-3">
                <b class="px-3 py-1 ">نحوه برگزاری کلاس ها: </b>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique($translated_course_type))) ?>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 d-flex flex-row gap-2 justify-content-start align-items-center mb-3">
                <b class="px-3 py-1">مقاطع سنی: </b>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique($translated_age))) ?>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 d-flex flex-row gap-2 justify-content-start align-items-center mb-3">
                <b class="px-3 py-1">جنسیت قرآن آموزان: </b>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique($translated_gender))) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-2 institute-block">
    <div class="atlas-row px-4 py-3">
        <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('edu.svg') ?>">اساتید برجسته</p>
        <hr class="">

        <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center">
            <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique($teacher))) ?>
        </div>
    </div>
</div>



<!-- دوره های فعال -->
<div class="container-fluid mt-2 institute-block">
    <div class="atlas-row px-4 py-3">
        <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('edu.svg') ?>"> دوره های فعال</p>
        <hr class="">
        <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center">
            <?php echo implode(' <div class="vr"></div> ', array_map('atlas_page_item', array_unique(explode(',', $subject)))) ?>
        </div>
    </div>
</div>

<!-- راه های ارتباطی با موسسه -->
<div class="container-fluid mt-2 institute-block mb-5">
    <div class="atlas-row px-4 py-3">
        <p class="atlas-title fw-bold"><img src="<?php echo atlas_panel_image('text.svg') ?>"> راه های ارتباطی با موسسه
        </p>
        <hr class="">
        <div class="row">
            <!-- ستونی برای اطلاعات تماس -->
            <div class="col-12 col-md-6 atlas-contact d-flex flex-column gap-4">
                <div>
                    <img class="py-1 border border-warning rounded-circle bg-black"
                        src="<?php echo atlas_panel_image('phone.png') ?>">
                    <span><a href="tel:<?php echo $phone ?>" class="py-1 fw-bold"><?php echo $phone ?></a></span>
                </div>
                <div>
                    <img class="py-1 border border-warning rounded-circle bg-black"
                        src="<?php echo atlas_panel_image('icon-location.png') ?>">
                    <span><?php echo $address ?></span>
                </div>
            </div>

            <!-- ستونی برای نقشه -->
            <div class="col-12 col-md-6">
                <div id="map-city" class="rounded-4" style="height: 300px; max-height: 500px;"></div>
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
</script>



<?php

            } else {
                echo '<p>پستی با این آیدی پیدا نشد.</p>';
        }?>














<?php
    }
    }

?>








<?php get_footer(); ?>