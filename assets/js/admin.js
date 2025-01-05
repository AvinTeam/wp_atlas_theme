

// ایجاد نقشه و تنظیمات اولیه
const map = L.map('map').setView([35.6892, 51.3890], 10); // شروع از تهران
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

let marker = null; // متغیر برای ذخیره مارکر


function atlasMap(state = 'تهران', city = 'تهران') {


    const lat = document.getElementById('map-lat').value;
    const lng = document.getElementById('map-lng').value;

    console.log(lat);
    console.log(lng);

    if (lat != "" && lng != "") {
        marker = L.marker([lat, lng]).addTo(map).bindPopup("اینجا را انتخاب کردید").openPopup();
        map.setView([lat, lng], 13);

    } else {

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
    }

}


















jQuery(document).ready(function ($) {


//   // Jquery Tag Input Starts
//   $('#subject').tagsInput({
//     'width': '100%',
//     'height': '75%',
//     'interactive': true,
//     'defaultText': 'افزودن',
//     'removeWithBackspace': true,
//     'minChars': 0,
//     'maxChars': 20, // if not provided there is no limit
//     'placeholderColor': '#666666'
//   });

$('#subject').tagsInput({
    'defaultText': 'تگ جدید',
    'maxChars': 10,
    'delimiter': [','], // جداکننده
});

    atlasMap();


    $('#ostan').on('change', function () {

        let formData = {
            action: 'atlas_get_city',
            ostanId: $(this).val(),
            type: 'city',
        };
        $.ajax({
            url: atlas_js.ajax_url,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                $('#city').html(response.data);
            }
        });

    });


    $('#city').on('change', function () {

        let ostan = $('#ostan option:selected').text();
        let shahr = $('#city option:selected').text();

        $('#map-lat').val('');
        $('#map-lng').val('');

        atlasMap(ostan, shahr);


    });

    // 3. رویداد کلیک روی نقشه با مارکر
    map.on('click', function (e) {
        console.log('click map');
        const {
            lat,
            lng
        } = e.latlng;

        // اگر مارکر قبلی وجود داره، حذفش کن
        if (marker) {
            map.removeLayer(marker);
        }

        // ایجاد مارکر جدید
        marker = L.marker([lat, lng]).addTo(map).bindPopup("اینجا را انتخاب کردید").openPopup();



        // درخواست به API نوماتیم

        const urlAddres = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=fa`;


        fetch(urlAddres)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    const addressParts = data.display_name.split(',').map(part => part.trim());

                    const filteredParts = addressParts.filter((part) => {

                        const ignoreKeywords = ['ایران', 'استان', 'شهرستان'];

                        const isPostalCode = /^\d{4,10}(-\d{4,10})?$/.test(part); // کد پستی ساده یا ترکیبی

                        return !ignoreKeywords.some(keyword => part.includes(keyword)) && !isPostalCode;
                    });

                    const filteredAddress = filteredParts.reverse().join('، ');

                    $('#atlas-address').text(filteredAddress || "جزئیات آدرس یافت نشد!");
                }


            })
            .catch(error => {
                console.error("Error fetching address:", error);
                document.getElementById('address').textContent = "خطا در دریافت آدرس!";
            });

        $('#map-lat').val(lat);
        $('#map-lng').val(lng);
    });


});








