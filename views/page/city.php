<?php

    $inrow     = 18;
    $atlas     = get_query_var('atlas');
    $this_page = explode('=', $atlas);
    $iran      = new Iran_Area;
    $city_id   = 0;

    $atlas_body = 'atlas-city';

    if ($this_page[ 0 ] == 'city') {

        $this_city = $iran->one_city($this_page[ 1 ]);

        $city_id       = $this_city[ 'city_id' ];
        $city_neme     = $this_city[ 'city' ];
        $province_id   = $this_city[ 'province_id' ];
        $province_neme = $this_city[ 'province' ];

        $pagination = "city=$city_id";
        function atlas_title_filter_city($title)
        {
            global $city_neme;

            $title = get_bloginfo('name') . " | شهر " . $city_neme;
            return $title;
        }
        add_filter('wp_title', 'atlas_title_filter_city');

    } elseif ($this_page[ 0 ] == 'province') {
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
        $pagination = "province=$province_id";

        $cites = $iran->select($province_id);
    } else {

        $all_iran      = 'ایران';
        $city_id       = 'all';
        $city_neme     = 'ایران';
        $province_id   = 'all';
        $province_neme = 'ایران';

        $provinces = $iran->select(0);

        $pagination = "all";

    }

    $args = [
        'post_type'      => 'institute',
        'posts_per_page' => -1,
     ];

    if (! isset($all_iran)) {

        $args[ 'meta_query' ] = [
            [
                'key'     => ($this_page[ 0 ] == 'city') ? '_atlas_city' : '_atlas_ostan',
                'value'   => $this_page[ 1 ],
                'compare' => '=',
             ],
         ];
    }

    $query = new WP_Query($args);

    $all_amooz     = 0;
    $all_teacher   = 0;
    $all_institute = [  ];
    $points        = [  ];

    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();

            $coaches  = absint(get_post_meta(get_the_ID(), '_atlas_coaches', true));
            $contacts = absint(get_post_meta(get_the_ID(), '_atlas_contacts', true));
            $city     = absint(get_post_meta(get_the_ID(), '_atlas_city', true));
            $map      = get_post_meta(get_the_ID(), '_atlas_map', true);

            $ins_city = $iran->one_city($city);

            $all_teacher += $coaches;
            $all_amooz += $contacts;

            $img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : atlas_panel_image('default.png');

            $all_institute[  ] = [
                'id'       => get_the_ID(),
                'link'     => esc_url(get_permalink(get_the_ID())),
                'img'      => $img,
                'title'    => get_the_title(),
                'coaches'  => $coaches,
                'contacts' => $contacts,
                'location' => $ins_city[ 'province' ] . ', ' . $ins_city[ 'city' ],
                'map'      => $map,
             ];

            if (! empty($map[ 'lat' ]) && ! empty($map[ 'lng' ])) {

                $info = '<div style="text-align: center;">
																												                            <h5>' . get_the_title() . '</h5>
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
        endwhile;
        wp_reset_postdata();
    endif;

    $total  = ceil(sizeof($all_institute) / $inrow);
    $inpage = (isset($_GET[ 'page' ]) && absint($_GET[ 'page' ]) <= $total) ? absint($_GET[ 'page' ]) : 1;

    $start_show = ($inpage - 1) * $inrow;

    $all_institute_new = array_slice($all_institute, $start_show, $inrow, true);

get_header(); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>


<div class="container-fluid  ">
    <div class=" d-flex flex-column gap-4 px-4 py-3 city-head-box atlas-row rounded-4 ">
        <div class="breadcrumbs text-white">
            <img src="<?php echo atlas_panel_image('home-icone.svg') ?>">
            <a class="text-white" href="/">خانه</a>
            <img src="<?php echo atlas_panel_image('arrow.svg') ?>">
            <a class="text-white"
                href="<?php echo atlas_base_url('province=' . $province_id) ?>/"><?php echo $province_neme ?></a>

            <?php if ($this_page[ 0 ] == 'city'): ?>
            <img class="search-button" src="<?php echo atlas_panel_image('arrow.svg') ?>">
            <a class="text-white" href="<?php echo atlas_base_url('city=' . $city_id) ?>/"><?php echo $city_neme ?></a>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-center my-2">
            <div class=" p-2 rounded-pill page-name-center ">
                <div class="bg-white rounded-pill px-3 py-2 text-center fw-bold">
                    <span><?php echo($this_page[ 0 ] == 'city') ? $city_neme : $province_neme ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <div class="bg-white rounded px-3 py-1 text-center">
                <img src="<?php echo atlas_panel_image('amooz.svg') ?>">
                <span><?php echo number_format(absint($all_amooz)) ?> قرآن آموز</span>
            </div>

            <div class="bg-white rounded px-3 py-1 text-center">
                <img src="<?php echo atlas_panel_image('teacher.svg') ?>">
                <span><?php echo number_format(absint($all_teacher)) ?> مربی</span>
            </div>

        </div>
    </div>
</div>


<div class="container-fluid px-3 ">
    <div class="atlas-row mt-2 row justify-content-between align-content-center ">

        <div class="col-8 d-flex flex-column  p-0 m-0">
            <div
                class="filter-box d-flex flex-row justify-content-start align-items-center gap-2 map-filter p-2 rounded">
                <div class="rounded px-3 py-1 text-center">
                    <span>فیلتر بر اساس:</span>
                </div>




                <?php if (isset($all_iran)): ?>


                <div>
                    <select id="select2" class="form-select" data-goto="province" name="city" style="width: 100%;">
                        <option>استان خود رو انتخاب کنید</option>
                        <?php foreach ($provinces as $province): ?>
                        <option value="<?php echo $province->id ?>">
                            <?php echo $province->name ?>
                        </option>
                        <?php endforeach; ?>

                    </select>
                </div>


                <?php else: ?>


                <div class="rounded px-3 py-1 text-center map-province">
                    <span>استان <?php echo $province_neme ?></span>
                </div>
                <div>
                    <select id="select2" class="form-select" name="city" data-goto="city" style="width: 100%;">
                        <option>شهر خود رو انتخاب کنید</option>
                        <?php foreach ($cites as $city): ?>
                        <option<?php selected($city_id, $city->id)?> value="<?php echo $city->id ?>">
                            <?php echo $city->name ?>
                            </option>
                            <?php endforeach; ?>

                    </select>
                </div>
                <?php endif; ?>


            </div>
            <div class="row p-2">
                <?php if (! sizeof($all_institute)): ?>

                <div class="alert alert-info text-center" role="alert">
                    موسسه ای یافت نشده است
                </div>

                <?php endif; ?>



                <?php $institute_count = 1;foreach ($all_institute_new as $institute): ?>

                <div class="row  col-4 m-0 my-2  p-2">
                    <div class="institute d-flex flex-row">

                        <div class="col-8 d-flex flex-column ">
                            <div class="institute-title"> <a
                                    href="<?php echo $institute[ 'link' ] ?>"><b><?php echo $institute[ 'title' ] ?></b></a>
                            </div>


                            <div class="mt-1">
                                <?php if (! empty($institute[ 'map' ][ 'lat' ]) && ! empty($institute[ 'map' ][ 'lng' ])): ?>

                                <button class="onmap btn btn-new mt-2"
                                    style="padding: 4px;display: inline-flex;background: goldenrod;"
                                    data-lat="<?php echo $institute[ 'map' ][ 'lat' ] ?>"
                                    data-lng="<?php echo $institute[ 'map' ][ 'lng' ] ?>"
                                    data-info="<?php echo $institute[ 'title' ] ?>">

                                    <img src="<?php echo atlas_panel_image('icon-location.png') ?>" style="height: 25px"
                                        alt="">
                                </button>
                                <?php endif; ?>

                                <a href="<?php echo $institute[ 'link' ] ?>" class="btn btn-new mt-2 showLoading">
                                    <span>ورود به صفحه موسسه</span>
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


            <?php
                function paginate($total_pages, $current_page)
                {

                    $output = '';

                    // محاسبه صفحات قابل نمایش
                    $start = max(1, $current_page - 2);
                    $end   = min($total_pages, $current_page + 2);

                    if ($start > 5) {

                        for ($i = 1; $i <= 5; $i++) {
                            if ($i == $current_page) {
                                $output .= "<span  class='current rounded-circle d-block text-center'>$i</span>";
                            } else {
                                $output .= "<a class='d-block text-center' href='?page=$i'>$i</a>";
                            }
                        }

                        $output .= '...';

                    }

                    // نمایش صفحات
                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $current_page) {
                            $output .= "<span class='current rounded-circle d-block text-center'>$i</span>";
                        } else {
                            $output .= "<a class='d-block text-center' href='?page=$i'>$i</a>";
                        }
                    }

                    $end_start = $total_pages - 4;

                    // اگر صفحه فعلی نزدیک انتهای صفحات باشد
                    if ($total_pages - $start >= 10) {

                        $output .= '...';

                        for ($i = $end_start; $i <= $total_pages; $i++) {
                            $output .= "<a class='d-block text-center' href='?page=$i'>$i</a>";

                        }
                    } else {
                        for ($i = $end + 1; $i <= $total_pages; $i++) {
                            $output .= "<a class='d-block text-center' href='?page=$i'>$i</a>";

                        }
                    }

                    return $output;
                }

            ?>
            
            <?php if (sizeof($all_institute) > $inrow): ?>

            <div class=" mt-5 d-flex flex-row justify-content-between   ">
                <?php
                    if ($inpage == 1) {
                        echo '<img src="' . atlas_panel_image('prev-page-no-active.svg') . '">';
                    } else {
                        echo '<a class="" href="' . atlas_base_url($pagination . '/?page=' . ($inpage - 1)) . '"><img
                    src="' . atlas_panel_image('prev-page-active.svg') . '"></a>';

                    }

                ?>
                <div class="atlas-paginate d-flex flex-row justify-content-center">
                    <?php echo paginate($total, $inpage); ?></div>

                <?php
                    if ($inpage == $total) {
                        echo '<img src="' . atlas_panel_image('next-page-no-active.svg') . '">';
                    } else {
                        echo '<a class="" href="' . atlas_base_url($pagination . '/?page=' . ($inpage + 1)) . '"><img
                    src="' . atlas_panel_image('next-page-active.svg') . '"></a>';

                    }

                ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-4 ps-2 p-0 m-0 map-city-parent">
            <div id="map-city" class=" rounded-4" style="height: 100%"></div>
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


<?php if ($this_page[ 0 ] == 'city'): ?>


<script>
city = "<?php echo $city_neme ?>";

query = `${city}, ${province}`;
</script>


<?php endif; ?>



<?php if (isset($all_iran)): ?>


<script>
query = '';
</script>


<?php endif; ?>

<script>
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



        // نمایش اطلاعات در کنسول (می‌توانید به جای این بخش، اطلاعات را به نقشه اضافه کنید)
        console.log({
            title,
            imageSrc,
            lat,
            lng,
            info
        });

        // مثال نمایش اطلاعات روی نقشه
        const marker = L.marker([lat, lng], {
            icon: customIcon
        }).addTo(mapCity);
        marker.bindPopup(`
            <div style="text-align: center;">
                <h5>${title}</h5>
                <img src="${imageSrc}" alt="${title}" style="width: 100%; max-width: 150px; border-radius: 8px;">
            </div>
        `).openPopup();
    });
});
</script>

<?php get_footer(); ?>