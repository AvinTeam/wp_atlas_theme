<?php

    $atlas     = get_query_var('atlas');
    $this_page = explode('=', $atlas);
    $iran      = new Iran_Area;
    $city_id   = 0;

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

    } else {
        exit;
    }

    $cites = $iran->select($province_id);

    $args = [
        'post_type'      => 'institute', // نوع پست تایپ
        'posts_per_page' => -1,          // تعداد پست‌ها (همه پست‌ها)
        'meta_query'     => [
            [
                'key'     => ($this_page[ 0 ] == 'city') ? '_atlas_city' : '_atlas_ostan', // نام متا
                'value'   => $this_page[ 1 ],                                              // مقدار متا
                'compare' => '=',                                                          // نوع مقایسه
             ],
         ],
     ];

    $query = new WP_Query($args);

    $all_amooz     = 0;
    $all_teacher   = 0;
    $all_institute = [  ];
    $points        = [  ];

    //<a href="<?php echo  " class="location-item">the_title();</a>

    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();

            $coaches  = absint(get_post_meta(get_the_ID(), '_atlas_coaches', true));
            $contacts = absint(get_post_meta($post->ID, '_atlas_contacts', true));
            $city     = absint(get_post_meta($post->ID, '_atlas_city', true));

            $ins_city = $iran->one_city($city);

            $all_teacher += $coaches;
            $all_amooz += $contacts;

            $all_institute[  ] = [
                'id'       => get_the_ID(),
                'link'     => esc_url(get_permalink(get_the_ID())),
                'img'      => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : atlas_panel_image('00.png'),
                'title'    => get_the_title(),
                'coaches'  => $coaches,
                'contacts' => $contacts,
                'location' => $ins_city[ 'province' ] . ', ' . $ins_city[ 'city' ],
             ];

            $map = get_post_meta(get_the_ID(), '_atlas_map', true);

            if (! empty($map)) {
                $points[  ] = [
                    $map[ 'lat' ],
                    $map[ 'lng' ],

                 ];
            }
        endwhile;
        wp_reset_postdata();
    endif;

get_header(); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>


<div class="container-fluid city-head-box p-4">
    <div class=" d-flex flex-column gap-5">
        <div class="breadcrumbs text-white">
            <img src="<?php echo atlas_panel_image('home-icone.svg') ?>">
            <a class="text-white" href="/">خانه</a>
            <img src="<?php echo atlas_panel_image('arrow.svg') ?>">
            <a class="text-white" href="/atlas/province=<?php echo $province_id ?>/"><?php echo $province_neme ?></a>

            <?php if ($this_page[ 0 ] == 'city'): ?>
            <img class="search-button" src="<?php echo atlas_panel_image('arrow.svg') ?>">
            <a class="text-white" href="/atlas/city=<?php echo $city_id ?>/"><?php echo $city_neme ?></a>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-center my-2">
            <div class=" p-2" style="border: 1px solid #E0AD70;">
                <div class="bg-light rounded px-3 py-1 text-center">
                    <span><?php echo($this_page[ 0 ] == 'city') ? $city_neme : $province_neme ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <div class="bg-light rounded px-3 py-1 text-center">
                <img src="<?php echo atlas_panel_image('amooz.svg') ?>">
                <span><?php echo number_format(absint($all_amooz)) ?> قرآن آموز</span>
            </div>

            <div class="bg-light rounded px-3 py-1 text-center">
                <img src="<?php echo atlas_panel_image('teacher.svg') ?>">
                <span><?php echo number_format(absint($all_teacher)) ?> مربی</span>
            </div>

        </div>
    </div>
</div>


<div class="container-fluid px-3 ">
    <div id="map-city" style="height: 500px;"></div>

    <div class="d-flex flex-column gap-1 mt-3 mb-5">
        <div class="filter-box d-flex flex-row justify-content-start align-items-center gap-2 map-filter p-2 rounded">
            <div class="rounded px-3 py-1 text-center">
                <span>فیلتر بر اساس:</span>
            </div>
            <div class="rounded px-3 py-1 text-center map-province">
                <span>استان <?php echo $province_neme ?></span>
            </div>
            <div>
                <select id="select2" class="form-select" name="city" style="width: 100%;">
                    <option>شهر خود رو انتخاب کنید</option>
                    <?php foreach ($cites as $city): ?>
                    <option<?php selected($city_id, $city->id)?> value="<?php echo $city->id ?>">
                        <?php echo $city->name ?>
                        </option>
                        <?php endforeach; ?>

                </select>
            </div>



        </div>
        <div class="row">

            <?php $institute_count = 0;foreach ($all_institute as $institute): ?>
            <div class="institute col-2 my-1 d-flex flex-column p-2 position-relative ">

                <div class=" position-absolute end-0 w-100  d-flex flex-column align-items-end">
                    <div class=" w-25 d-flex flex-column align-items-center">
                        <img src="<?php echo atlas_panel_image('star.svg') ?>">
                        <span>4.5</span>
                    </div>
                </div>
                <div class=" col-12 d-flex flex-column ">

                    <a href="<?php echo $institute[ 'link' ] ?>">
                        <img class="w-100 institute-img" src="<?php echo $institute[ 'img' ] ?>">
                    </a>

                    <div class="institute-title text-center"> <a href="<?php echo $institute[ 'link' ] ?>">
                            <b><?php echo $institute[ 'title' ] ?></b></a></div>
                </div>
                <div class="item-details col-12 row gap-2 py-3 mt-2 ">
                    <div class="col-6 "><img src="<?php echo atlas_panel_image('teacer0.svg') ?>">
                        <span><?php echo $institute[ 'coaches' ] ?> مربی</span>
                    </div>
                    <div class="col-5"><img src="<?php echo atlas_panel_image('amooz0.svg') ?>">
                        <span><?php echo $institute[ 'contacts' ] ?> قرآن آموز</span>
                    </div>
                    <div class="col-6"><img src="<?php echo atlas_panel_image('Frame.svg') ?>">
                        <span><?php echo $institute[ 'location' ] ?></span>
                    </div>
                    <div class="col-5"><img src="<?php echo atlas_panel_image('map.svg') ?>">
                        <span> <a href="<?php echo $institute[ 'link' ] ?>">روی نقشه</a></span>
                    </div>
                </div>

            </div>
            <?php if ($institute_count == 9) {break;}
            $institute_count++;endforeach; ?>
        </div>

    </div>























</div>




<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>




<script>
// 1. ایجاد نقشه
const mapCity = L.map('map-city').setView([35.6892, 51.389], 6); // موقعیت اولیه: ایران

// 2. افزودن لایه OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap'
}).addTo(mapCity);

let query = '';


const province = "<?php echo $province_neme ?>";
const points = <?php echo json_encode($points); ?>;
query = province;
</script>


<?php if ($this_page[ 0 ] == 'city'): ?>


<script>
const city = "<?php echo $city_neme ?>";

query = `${city}, ${province}`;
</script>


<?php endif; ?>

<script>
const url =
    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&polygon_geojson=1&extratags=1`;
fetch(url)
    .then(response => response.json())
    .then(data => {

        if (data.length > 0) {
            const geojson = data[0].geojson;
            const bounds = data[0].boundingbox;
            console.log(points);
            // افزودن مارکرها برای نقاط
            points.forEach(coord => {
                const marker = L.marker(coord).addTo(mapCity);

                // کامنت برای تغییر مارکر:
                /*
                const customIcon = L.icon({
                    iconUrl: 'custom-icon-url.png', // مسیر آیکون سفارشی
                    iconSize: [32, 32],           // اندازه آیکون
                    iconAnchor: [16, 32],         // نقطه لنگر آیکون
                });
                const customMarker = L.marker(coord, { icon: customIcon }).addTo(mapCity);
                */
            });

            // تنظیم نقشه روی محدوده با استفاده از مرز استان
            const layer = L.geoJSON(geojson, {
                style: {
                    color: "transparent",
                    weight: 2,
                    fillColor: "transparent",
                    fillOpacity: 0.5
                }
            }).addTo(mapCity);

            mapCity.fitBounds(layer.getBounds());



            
        } else {
            alert("استان یافت نشد!");
        }
    })
    .catch(error => console.error("خطا در دریافت اطلاعات:", error));
</script>

<?php get_footer(); ?>