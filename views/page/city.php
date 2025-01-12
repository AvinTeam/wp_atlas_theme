<?php

$atlas = get_query_var('atlas');
$page = explode('=', $atlas);

$iran = new Iran_Area;

$this_city = $iran->one_city($page[ 1 ]);

function atlas_title_filter_city($title)
{
    global $this_city;

    $title = get_bloginfo('name') . " | شهر ".$this_city[ 'city' ];
    return $title;
}
add_filter('wp_title', 'atlas_title_filter_city');

?>
<?php get_header(); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>


<style>
body {
    margin: 0;
    display: flex;
    height: 100vh;
    font-family: Arial, sans-serif;
}

#map {
    flex: 2;
    height: 100%;
}

#locations {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    background-color: #f9f9f9;
    border-left: 1px solid #ccc;
    direction: rtl;
}

.location-item {
    width: 100%;
    margin-bottom: 10px;
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
    display: block;
    transition: background-color 0.3s;
}

.location-item:hover {
    background-color: #f0f0f0;
}
</style>

<div id="map"></div>
<div id="locations">
    <h3>لیست</h3>
    <div id="location-list">



        <?php
$args = [
    'post_type' => 'institute', // نوع پست تایپ
    'posts_per_page' => -1, // تعداد پست‌ها (همه پست‌ها)
    'meta_query' => [
        [
            'key' => '_atlas_city', // نام متا
            'value' => $page[ 1 ], // مقدار متا
            'compare' => '=', // نوع مقایسه
         ],
     ],
 ];

$query = new WP_Query($args);

if ($query->have_posts()):
    while ($query->have_posts()): $query->the_post();
        ?>
        <a href="<?=atlas_pane_base_url('institute='. get_the_ID())?>" class="location-item"><?php the_title(); ?></a>

        <?php
    endwhile;
    wp_reset_postdata(); // ریست کردن کوئری
else:
    echo '<p>هیچ موسسه ای یافت نشد.</p>';
endif;
?>

    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ایجاد نقشه و تنظیمات اولیه
const map = L.map('map').setView([35.6892, 51.3890], 13); // شروع از تهران
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

let marker = null; // متغیر برای ذخیره مارکر


let state = ' <?=$this_city[ 'province' ]?>';
let city = '<?=$this_city[ 'city' ]?>';


// 2. هندل دکمه نمایش روی نقشه
const url =
    `https://nominatim.openstreetmap.org/search?city=${encodeURIComponent(city)}&state=${encodeURIComponent(state)}&format=json`;

if (url) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const {
                    lat,
                    lon
                } = data[0];
                map.setView([lat, lon], 13);

            } else {
                alert("مکان مورد نظر یافت نشد!");
            }
        })
        .catch(error => console.error("Error:", error));
} else {

    map.setView([35.6892, 51.3890], 13);



}
</script>

<?php get_footer(); ?>