

// ایجاد نقشه و تنظیمات اولیه
const map = L.map('map').setView([35.6892, 51.3890], 10); // شروع از تهران
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

let marker = null; // متغیر برای ذخیره مارکر


function atlasMap(state = 'تهران', city = 'تهران') {


    const lat = document.getElementById('map-lat').value;
    const lng = document.getElementById('map-lng').value;

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


    $('#subject').tagsInput({
        'width': '340px',
        'defaultText': 'افزودن',
        'removeWithBackspace': true,
        'interactive': true,
        'delimiter': [','], // جداکننده
    });

    atlasMap();


    let is_city_value = $('#city').val();

    if (is_city_value != 0) {
        let is_city = $('#city option:selected').text();
        let is_ostan = $('#ostan option:selected').text();
        atlasMap(is_ostan, is_city);
    }




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




    const $tagsInput = $('#subject_tagsinput');
    const $spans = $('#all_subject span');
    const $input = $('#subject');


    function checkInputMatches(input = "") {

        const $input = $('#subject');

        const inputValue = $input.val().trim();

        const inputWords = inputValue.split(',').map(word => word.trim());

        if (input != "") {
            inputWords.push(input);
        }



        $spans.each(function () {
            const $span = $(this);
            if (inputWords.includes($span.text())) {
                $span.addClass('active');
            } else {
                $span.removeClass('active');
            }
        });
    }

    checkInputMatches();



    $input.on('input', function () {
        const inputValue = $(this).val().trim();

        // بررسی اینکه آیا مقدار input با یکی از span‌ها برابر است
        $spans.each(function () {
            const $span = $(this);
            if ($span.text() === inputValue) {
                $span.addClass('activ');    // افزودن کلاس activ به span مرتبط
            }
        });
        checkInputMatches();

    });


    // افزودن تگ با کلیک روی span
    $spans.on('click', function () {
        const $this = $(this);
        const text = $this.text().trim();

        // بررسی اینکه آیا تگ از قبل وجود دارد
        const exists = $tagsInput.find(`span.tag:contains('${text}')`).length > 0;
        if (!exists) {
            // افزودن تگ به لیست
            const newTag = `
                    <span class="tag">
                        <span>${text}&nbsp;&nbsp;</span>
                        <a href="#" title="حذف">x</a>
                    </span>
                `;
            $tagsInput.find('#subject_addTag').before(newTag);


            let newimput = $input.val();
            $input.val(newimput + ',' + text);

            // افزودن کلاس active به span
            checkInputMatches();

        }
    });


    $('.tag a').click(function (e) {
        e.preventDefault();


        const $tag = $(this).closest('.tag');
        const tagText = $tag.text().trim();

        // حذف کلاس active از span مرتبط
        $spans.filter(function () {
            return $(this).text().trim() === tagText;
        }).removeClass('active');


        const tagText0 = $tag.find('span:first').text().trim();

        let tags = $input.val();
        tags = tags.split(',') // رشته را به آرایه تقسیم می‌کند
            .filter(tag => tag.trim() !== tagText0) // حذف مقدار مدنظر
            .join(','); // بازگرداندن به فرمت رشته
        $input.val(tags);

        // $tag.remove();


        checkInputMatches();


    });


    // حذف تگ با کلیک روی x
    $tagsInput.on('click', '.tag a', function (e) {
        e.preventDefault();


        const $tag = $(this).closest('.tag');
        const tagText = $tag.text().trim();

        // حذف کلاس active از span مرتبط
        $spans.filter(function () {
            return $(this).text().trim() === tagText;
        }).removeClass('active');


        const tagText0 = $tag.find('span:first').text().trim();

        let tags = $input.val();
        tags = tags.split(',') // رشته را به آرایه تقسیم می‌کند
            .filter(tag => tag.trim() !== tagText0) // حذف مقدار مدنظر
            .join(','); // بازگرداندن به فرمت رشته
        $input.val(tags);

        $tag.remove();

        checkInputMatches();

        // حذف تگ
    });



    $('#subject_tag').on('keydown', function (e) {
        if (e.which === 13) {
            const tagValue = $(this).val().trim(); // مقدار داخل input

            checkInputMatches(tagValue);

        }
    });

    $(document).on("click", ".atlas-teacher-add", function (e) {
        e.preventDefault();
        const newRow = `<div class="atlas-teacher-row"><input class="regular-text" name="atlas[teacher][]" value=""> <button class="button button-primary button-error atlas-teacher-remove">حذف</button></div>`;

        $('.teacher_list').append(newRow);
    });

    $(document).on("click", ".atlas-teacher-remove", function (e) {
        e.preventDefault();
        $(this).closest("div").remove();
    });




    $('.onlyNumbersInput').on('input paste', function () {
        // پاک کردن تمام کاراکترهای غیرعددی
        this.value = this.value.replace(/[^0-9]/g, '');
    });

























































});








