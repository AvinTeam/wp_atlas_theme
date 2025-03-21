<?php

    use atlasclass\Iran_Area;

    $inrow       = 30;
    $atlas       = get_query_var('atlas');
    $this_page   = explode('=', $atlas);
    $iran        = new Iran_Area;
    $city_id     = 0;
    $province_id = 0;

    $inpage = (isset($_GET[ 'page' ])) ? absint($_GET[ 'page' ]) : 1;

    $all_iran = '';

    $atlas_body = 'atlas-city';

    $search = $iran->all_city();

    if ($this_page[ 0 ] == 'city') {

        $this_city = $iran->one_city($this_page[ 1 ]);

        $city_id       = $this_city[ 'city_id' ];
        $city_neme     = $this_city[ 'city' ];
        $province_id   = $this_city[ 'province_id' ];
        $province_neme = $this_city[ 'province' ];

        function atlas_title_filter_city($title)
        {
            global $city_neme;

            $title = get_bloginfo('name') . " | شهر " . $city_neme;
            return $title;
        }
        add_filter('wp_title', 'atlas_title_filter_city');
        $cites = $iran->select($province_id);

        $all_iran = 'city';
        $search   = $iran->all_city($province_id);

    } elseif ($this_page[ 0 ] == 'province') {
        $all_iran = 'province';

        $atlas     = get_query_var('atlas');
        $this_page = explode('=', $atlas);

        $this_province = $iran->get('id', absint($this_page[ 1 ]));

        $province_id   = $this_province->id;
        $province_neme = $this_province->name;

        function atlas_title_filter_province($title)
        {
            global $province_neme;

            $title = get_bloginfo('name') . " | استان " . $province_neme;
            return $title;
        }
        add_filter('wp_title', 'atlas_title_filter_province');
        $provinces = $iran->select(0);

        $cites = $iran->select($province_id);

        $search = $iran->all_city($province_id);

    } elseif ($this_page[ 0 ] == 'search') {

        function atlas_title_filter_search($title)
        {
            $title = get_bloginfo('name') . " | جست و جو ";
            return $title;
        }
        add_filter('wp_title', 'atlas_title_filter_search');

        $search_title  = 'جست و جو ';
        $province_neme = '';

    } else {

        function atlas_title_filter_iran($title)
        {
            $title = get_bloginfo('name') . " | ایران ";
            return $title;
        }
        add_filter('wp_title', 'atlas_title_filter_iran');

        $all_iran      = 'ایران';
        $city_id       = 'all';
        $city_neme     = 'ایران';
        $province_id   = 'all';
        $province_neme = 'ایران';

        $provinces = $iran->select(0);
    }

    $args = [
        'post_type'      => 'institute',
        'post_status'    => 'publish',
        'posts_per_page' => $inrow,
        'paged'          => $inpage,

     ];

    if (in_array($all_iran, [ 'city', 'province' ])) {

        $args[ 'meta_query' ] = [
            [
                'key'     => ($this_page[ 0 ] == 'city') ? '_atlas_city' : '_atlas_ostan',
                'value'   => $this_page[ 1 ],
                'compare' => '=',
             ],
         ];
    }

    if ($this_page[ 0 ] == 'search') {

        if (isset($_GET[ 's' ]) && ! empty($_GET[ 's' ])) {
            $word_search = urldecode(sanitize_text_field($_GET[ 's' ]));
            $args[ 's' ] = sanitize_text_field($word_search);

        }

        if (isset($_GET[ 'c' ]) && absint($_GET[ 'c' ])) {
            $args[ 'meta_query' ][  ] = [
                'key'     => '_atlas_city',
                'value'   => absint($_GET[ 'c' ]),
                'compare' => '=',
             ];

            $this_city = $iran->one_city(absint($_GET[ 'c' ]));

            $city_id       = $this_city[ 'city_id' ];
            $city_neme     = $this_city[ 'city' ];
            $province_id   = $this_city[ 'province_id' ];
            $province_neme = $this_city[ 'province' ];
        } else {
            $_GET[ 'c' ] = 0;
        }

        if (isset($_GET[ 'course' ]) && ! empty($_GET[ 'course' ])) {
            $args[ 'meta_query' ][  ] = [
                'key'     => '_atlas_course-type',
                'value'   => sanitize_text_field($_GET[ 'course' ]),
                'compare' => 'LIKE',
             ];
        }

        if (isset($_GET[ 'age' ]) && ! empty($_GET[ 'age' ])) {
            $args[ 'meta_query' ][  ] = [
                'key'     => '_atlas_age',
                'value'   => sanitize_text_field($_GET[ 'age' ]),
                'compare' => 'LIKE',
             ];
        }

        if (isset($_GET[ 'gender' ]) && ! empty($_GET[ 'gender' ])) {
            $args[ 'meta_query' ][  ] = [
                'key'     => '_atlas_gender',
                'value'   => sanitize_text_field($_GET[ 'gender' ]),
                'compare' => 'LIKE',
             ];
        }

        if (isset($_GET[ 'type' ]) && ! empty($_GET[ 'type' ])) {
            $args[ 'meta_query' ][  ] = [
                'key'   => '_atlas_center-type',
                'value' => sanitize_text_field($_GET[ 'type' ]),
                // 'compare' => 'LIKE',
             ];
        }

    }

    $query = new WP_Query($args);

    $all_amooz           = 0;
    $all_teacher         = 0;
    $all_institute       = [  ];
    $total_max_num_pages = $query->max_num_pages;

    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();

            $coaches  = absint(get_post_meta(get_the_ID(), '_atlas_coaches', true));
            $contacts = absint(get_post_meta(get_the_ID(), '_atlas_contacts', true));
            $city     = absint(get_post_meta(get_the_ID(), '_atlas_city', true));
            $map      = get_post_meta(get_the_ID(), '_atlas_map', true);

            $center_type = get_post_meta(get_the_ID(), '_atlas_center-type', true);
            $center_mode = get_post_meta(get_the_ID(), '_atlas_center-mode', true);

            if (! $city) {continue;}

            $ins_city = $iran->one_city($city);

            $img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : atlas_panel_image('default.png');

            $link = esc_url(get_permalink(get_the_ID()));

            $all_institute[  ] = [
                'id'          => get_the_ID(),
                'link'        => $link,
                'img'         => $img,
                'title'       => get_the_title(),
                'coaches'     => $coaches,
                'contacts'    => $contacts,
                'location'    => $ins_city[ 'province' ] . ', ' . $ins_city[ 'city' ],
                'map'         => $map,
                'center_type' => get_center_type($center_type),
                'center_mode' => ($center_mode == 'public') ? 'عمومی' : 'خصوصی',
                'city'        => (empty($city_neme)) ? $ins_city[ 'city' ] : $city_neme,
             ];

        endwhile;
        wp_reset_postdata();
    endif;

    $args[ 'posts_per_page' ] = -1;

    $args[ 'fields' ] = 'ids';

    unset($args[ 'paged' ]);

    // Step 1: دریافت IDهای پست‌ها با WP_Query
    // $args = array(
    //     'post_type'      => 'post', // نوع پست را مشخص کنید
    //     'posts_per_page' => -1,     // تمام پست‌ها را دریافت کنید
    //     'meta_query'     => array(
    //         'relation' => 'OR', // پست‌هایی که حداقل یکی از این متا فیلدها را دارند
    //         array(
    //             'key'     => '_atlas_coaches', // متا فیلد اول
    //             'compare' => 'EXISTS',
    //         ),
    //         array(
    //             'key'     => '_atlas_contacts', // متا فیلد دوم
    //             'compare' => 'EXISTS',
    //         ),
    //     ),
    //     'fields'         => 'ids', // فقط IDهای پست‌ها را دریافت کنید
    // );

    $query_sum = new WP_Query($args);
    $post_ids  = $query_sum->posts; // دریافت IDهای پست‌ها

    // Step 2: جمع‌آوری مقادیر متا فیلدها با wpdb
    if (! empty($post_ids)) {
        global $wpdb;

        // تبدیل آرایه IDها به رشته برای استفاده در کوئری SQL
        $post_ids_str = implode(',', array_map('intval', $post_ids));

        // کوئری برای جمع‌آوری مقادیر _atlas_coaches
        $all_teacher = $wpdb->get_var(
            "SELECT SUM(meta_value)
         FROM {$wpdb->postmeta}
         WHERE meta_key = '_atlas_coaches'
         AND post_id IN ($post_ids_str)"
        );

        // کوئری برای جمع‌آوری مقادیر _atlas_contacts
        $all_amooz = $wpdb->get_var(
            "SELECT SUM(meta_value)
         FROM {$wpdb->postmeta}
         WHERE meta_key = '_atlas_contacts'
         AND post_id IN ($post_ids_str)"
        );

        $all_teacher ? $all_teacher : 0;
        $all_amooz ? $all_amooz : 0;
    }

    $args[ 'meta_query' ][  ] =
        [
        'key'     => '_atlas_map',                               // متا کلید مورد نظر
        'value'   => 'a:2:{s:3:"lat";s:0:"";s:3:"lng";s:0:"";}', // مقدار متا که نباید باشد
        'compare' => '!=',                                       // عملگر مقایسه (نابرابر)

     ];

    $query_points = new WP_Query($args);

    $points = [  ];

    if ($query_points->have_posts()) {
        while ($query_points->have_posts()) {
            $query_points->the_post();

            $map = get_post_meta(get_the_ID(), '_atlas_map', true);

            if (! empty($map[ 'lat' ]) && ! empty($map[ 'lng' ])) {

                $coaches  = absint(get_post_meta(get_the_ID(), '_atlas_coaches', true));
                $contacts = absint(get_post_meta(get_the_ID(), '_atlas_contacts', true));

                $img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : atlas_panel_image('default.png');

                $link = esc_url(get_permalink(get_the_ID()));

                $info = '<div style="text-align: center;">
                            <h5><a href="' . $link . '">' . get_the_title() . '</a></h5>
                            <img src="' . $img . '" alt="' . get_the_title() . '" style="width: 100%; max-width: 150px; border-radius: 8px;">
                            <p>' . $coaches . ' مربی</p>
                            <p>' . $contacts . ' قرآن‌آموز</p>
                        </div>';

                $points[  ] = [
                    "lat"  => $map[ 'lat' ],
                    "lng"  => $map[ 'lng' ],
                    "info" => $info,

                 ];
            }

        }
    }

    // بازنشانی پست‌ها
    wp_reset_postdata();

    $all_institute_new = $all_institute;

get_header(); ?>
<?php if ($this_page[ 0 ] != 'search'): ?>
<style>
.city-head-box {
    background-image: url('<?php echo atlas_panel_image('province/bg' . $province_id . '.png') ?>');
}
</style>
<?php endif; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
<div class="container-fluid">
    <div class="institute-head-box  d-flex flex-column gap-4 px-4 py-3 city-head-box mx-auto atlas-row rounded-4">
        <div class="breadcrumbs text-white">
            <img src="<?php echo atlas_panel_image('home-icone.svg') ?>">
            <a class="text-white" href="<?php echo site_url() ?>">خانه</a>
            <img src="<?php echo atlas_panel_image('arrow.svg') ?>">
            <?php if ($this_page[ 0 ] == 'search'): ?>
            <span class="text-white"><?php echo $search_title ?></span>
            <?php else: ?>

            <a class="text-white"
                href="<?php echo atlas_base_url('province=' . $province_id) ?>/"><?php echo $province_neme ?></a>

            <?php if ($this_page[ 0 ] == 'city'): ?>
            <img class="search-button" src="<?php echo atlas_panel_image('arrow.svg') ?>">
            <a class="text-white" href="<?php echo atlas_base_url('city=' . $city_id) ?>/"><?php echo $city_neme ?></a>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-center my-2">
            <div class="p-2 rounded-pill page-name-center">
                <div class="bg-white rounded-pill px-3 py-2 text-center fw-bold">
                    <?php if ($this_page[ 0 ] == 'search'): ?>
                    <span><?php echo $search_title ?></span>
                    <?php else: ?>
                    <span><?php echo($this_page[ 0 ] == 'city') ? $city_neme : $province_neme ?></span>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2  align-content-center">
            <div class="bg-white rounded px-3 py-1 text-center col-12 col-sm-4 col-lg-2 col-md-3">
                <img src="<?php echo atlas_panel_image('amooz.svg') ?>">
                <span><?php echo number_format(absint($all_amooz)) ?> قرآن آموز</span>
            </div>

            <div class="bg-white rounded px-3 py-1 text-center col-12 col-sm-4 col-lg-2 col-md-3">
                <img src="<?php echo atlas_panel_image('teacher.svg') ?>">
                <span><?php echo number_format(absint($all_teacher)) ?> مربی</span>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid px-3">
    <div
        class="atlas-row mx-auto mt-2 row justify-content-between align-content-center d-flex flex-column-reverse flex-md-row">
        <div class="col-12 col-md-8 d-flex flex-column p-0 m-0">
            <div
                class="filter-box d-flex flex-column  justify-content-start align-items-center gap-1 map-filter p-1 rounded ">
                <div class="d-flex flex-row align-items-center">
                    <img class="search-button me-1" src="<?php echo atlas_panel_image('btn-filter.svg') ?>">

                    <div class="rounded px-3 py-1 text-center">
                        <span class="text-nowrap">فیلتر بر اساس:</span>
                    </div>
                </div>
                <div
                    class="w-100 row row-cols-1 row-cols-lg-4 justify-content-lg-start justify-content-center  align-items-center ">
                    <?php if ($this_page[ 0 ] == 'search') {

                            foreach ($_GET as $key => $value) {

                                if ($key == 'c') {continue;}
                                if ($key == 'page') {continue;}

                                if ($key == 's' && ! empty($value)) {
                                    $value = urldecode(sanitize_text_field($value));
                                    $value = sanitize_text_field($value);

                                }

                                if ($key == 'course' && ! empty($value)) {
                                    $value = ($_GET[ 'course' ] == 'online') ? 'حضوری' : 'مجازی';

                                }

                                if ($key == 'age' && ! empty($value)) {
                                    $translations_age = [
                                        '7'   => 'زیر 7 سال',
                                        '12'  => '7 تا 12 سال',
                                        '18'  => '12 تا 18 سال',
                                        'old' => '18 سال به بالا',
                                     ];
                                    $value = $translations_age[ $_GET[ 'age' ] ];

                                }
                                if ($key == 'gender' && ! empty($value)) {

                                    $value = ($_GET[ 'gender' ] == 'woman') ? 'خواهران' : 'برادران';

                                }

                                if ($key == 'type' && ! empty($value)) {

                                    $value = get_center_type($_GET[ 'type' ]);

                                }

                            ?>


                    <div class="col p-1">
                        <div
                            class="d-flex flex-row justify-content-between align-content-center gap-2 rounded px-2 py-1 text-center map-province">
                            <b class="text-nowrap"><?php echo $value ?></b>
                            <div class="vr"></div>
                            <img id="<?php echo $key ?>" class="close-search"
                                src="<?php echo atlas_panel_image('btn-close-filter.svg') ?>">
                        </div>
                    </div>
                    <?php
                        }
                    }?>

                </div>

                <select id="select2searchcity" class="form-select form-select w-100" name="select2modal">
                    <option></option>
                    <?php foreach ($search as $key => $value): ?>
                    <option<?php selected(absint($city_id), $key)?> value="<?php echo $key ?>"><?php echo $value ?>
                        </option>
                        <?php endforeach; ?>
                </select>
            </div>

            <div class="row p-2">
                <?php if (! sizeof($all_institute)): ?>
                <div class="alert alert-info text-center" role="alert">
                    مرکز قرآنی ای یافت نشده است
                </div>
                <?php endif; ?>

                <?php $institute_count = 1;foreach ($all_institute_new as $institute): ?>
                <div class="row col-12 col-md-6 col-lg-4 m-0 my-2 p-2">
                    <div class="institute d-flex flex-row">
                        <div class="col-8 d-flex flex-column">
                            <div class="institute-title">
                                <a
                                    href="<?php echo $institute[ 'link' ] ?>"><b><?php echo $institute[ 'title' ] ?></b></a>
                            </div>
                            <div class="d-flex flex-column gap-1 mt-1">
                                <span class="f-12px">نوع مرکر: <b><?php echo $institute[ 'center_type' ] ?></b></span>
                                <span class="f-12px">حالت مرکز: <b><?php echo $institute[ 'center_mode' ] ?></b></span>
                                <span class="f-12px">شهر: <b><?php echo $institute[ 'city' ] ?></b></span>
                            </div>
                            <div class="mt-1">
                                <?php if (! empty($institute[ 'map' ][ 'lat' ]) && ! empty($institute[ 'map' ][ 'lng' ])): ?>
                                <button class="onmap btn btn-new mt-2"
                                    style="padding: 4px; display: inline-flex; background: goldenrod;"
                                    data-lat="<?php echo $institute[ 'map' ][ 'lat' ] ?>"
                                    data-lng="<?php echo $institute[ 'map' ][ 'lng' ] ?>"
                                    data-info="<?php echo $institute[ 'title' ] ?>">
                                    <img src="<?php echo atlas_panel_image('icon-location.png') ?>" style="height: 25px"
                                        alt="">
                                </button>
                                <?php endif; ?>
                                <a href="<?php echo $institute[ 'link' ] ?>"
                                    class="btn btn-new mt-2 showLoading institute-link">
                                    <span>ورود به صفحه مرکز قرآنی</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-4">
                            <a href="<?php echo $institute[ 'link' ] ?>">
                                <img class="w-100 institute-img" src="<?php echo $institute[ 'img' ] ?>">
                            </a>
                        </div>
                    </div>
                </div>
                <?php if ($institute_count == $inrow) {break;}
                $institute_count++;endforeach; ?>
            </div>




            <?php if (absint($total_max_num_pages) > 1): ?>
            <div class="mt-5 d-flex flex-row justify-content-between">
                <?php $prev_disabled = ($inpage == 1) ? 'disabled' : ''; ?>

                <a class="btn btn-outline-primary d-flex flex-row justify-content-center align-items-center gap-2                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php echo $prev_disabled ?>"
                    href="<?php echo esc_url(atlas_end_url('page', ($inpage - 1))) ?>">
                    <i class="bi bi-arrow-right"></i>
                    <div>|</div>
                    <span>صفحه قبلی</span>
                </a>

                <div class="atlas-paginate d-flex flex-row justify-content-center gap-1 gap-md-3">
                    <?php echo paginate($total_max_num_pages, $inpage); ?>
                </div>

                <?php $next_disabled = ($inpage == $total_max_num_pages) ? 'disabled' : ''; ?>

                <a class="btn btn-outline-primary d-flex flex-row justify-content-center align-items-center gap-2                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php echo $next_disabled ?>"
                    href="<?php echo esc_url(atlas_end_url('page', ($inpage + 1))) ?>">
                    <i class="bi bi-arrow-left"></i>
                    <div>|</div>
                    <span>صفحه بعد</span>
                </a>

            </div>
            <?php endif; ?>
        </div>

        <div class="col-12 col-md-4 ps-2 p-0  my-2 m-md-0 map-city-parent">
            <div id="map-city" class="rounded-4" style="height: 100%"></div>
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



let query = '';

const markerImage = "<?php echo atlas_panel_image('marker.png') ?>";

// کامنت برای تغییر مارکر:
const customIcon = L.icon({
    iconUrl: markerImage, // مسیر آیکون سفارشی
    iconSize: [48, 48], // اندازه آیکون
    iconAnchor: [24, 48], // نقطه لنگر آیکون
});
let city = "";
const province = "<?php echo $province_neme ?>";
const points = <?php echo json_encode($points); ?>;
query = province;
</script>


<?php if (absint($city_id)): ?>


<script>
city = "<?php echo $city_neme ?>";

query = `${city}, ${province}`;
</script>


<?php endif; ?>



<?php if ($all_iran != "" && $this_page[ 0 ] != 'search'): ?>


<script>
query = '';
</script>


<?php endif; ?>

<script>
// فعال کردن زوم اسکرول فقط با دکمه کنترل روی کامپیوتر
// mapCity.on('keydown', (event) => {
//     if (event.originalEvent.key === "Control") {
//         mapCity.scrollWheelZoom.enable(); // فعال کردن زوم با اسکرول
//     }
// });

// غیرفعال کردن زوم اسکرول بعد از برداشتن کلید کنترل
// mapCity.on('keyup', (event) => {
//     if (event.originalEvent.key === "Control") {
//         mapCity.scrollWheelZoom.disable(); // غیرفعال کردن زوم با اسکرول
//     }
// });

// فعال کردن زوم لمسی فقط با دو انگشت
// mapCity.touchZoom.enable(); // فعال کردن زوم لمسی
// mapCity.touchZoom = {
//     pinchZoomOnly: true // فقط با دو انگشت
// };

if (query != "") {
    const url =
        `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&polygon_geojson=1&extratags=1`;
    fetch(url)
        .then(response => response.json())
        .then(data => {

            if (data.length > 0) {
                const {
                    lat,
                    lon
                } = data[0];

                if (city != "") {
                    mapCity.setView([lat, lon], 13);

                } else {

                    mapCity.setView([lat, lon], 7);
                }



                // افزودن مارکرها برای نقاط
                points.forEach(point => {


                    const marker = L.marker([point.lat, point.lng], {
                        icon: customIcon
                    }).addTo(mapCity);


                    marker.bindPopup(point.info);
                    // نمایش اطلاعات هنگام کلیک روی مارکر
                    marker.on('click', () => {
                        marker.openPopup();
                    })
                });
            } else {
                alert("استان یافت نشد!");
            }
        })
        .catch(error => console.error("خطا در دریافت اطلاعات:", error));


} else {
    // افزودن مارکرها برای نقاط
    points.forEach(point => {


        const marker = L.marker([point.lat, point.lng], {
            icon: customIcon
        }).addTo(mapCity);


        marker.bindPopup(point.info);
        // نمایش اطلاعات هنگام کلیک روی مارکر
        marker.on('click', () => {
            marker.openPopup();
        })

    });
}




document.querySelectorAll('.onmap').forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault(); // جلوگیری از رفتار پیش‌فرض لینک

        // پیدا کردن والد اصلی (کلاس institute)
        const institute = link.closest('.institute');

        // استخراج اطلاعات
        const title = institute.querySelector('.institute-title b').innerText;
        const imageSrc = institute.querySelector('.institute-img').getAttribute('src');
        const instituteLink = institute.querySelector('.institute-link').getAttribute('href');

        // داده‌های مارکر
        const lat = link.getAttribute('data-lat');
        const lng = link.getAttribute('data-lng');
        const info = link.getAttribute('data-info');

        // رفتن به بخش نقشه
        const mapSection = document.getElementById('map-city');
        mapSection.scrollIntoView({
            behavior: 'smooth'
        });

        mapCity.setView([lat, lng], 16); // تنظیم زوم روی نقطه


        // مثال نمایش اطلاعات روی نقشه
        const marker = L.marker([lat, lng], {
            icon: customIcon
        }).addTo(mapCity);
        marker.bindPopup(`
            <div style="text-align: center;">
                <h5><a href="${instituteLink}" >${title}</a></h5>
                <img src="${imageSrc}" alt="${title}" style="width: 100%; max-width: 150px; border-radius: 8px;">
            </div>
        `).openPopup();
    });
});
</script>

<?php get_footer(); ?>