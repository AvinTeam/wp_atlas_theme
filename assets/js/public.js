

function updateImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        const fileImage = document.getElementById('fileImage');
        fileImage.src = e.target.result; // Update the profile image
    }

    if (file) {
        reader.readAsDataURL(file); // Read the file as a data URL
    }
}

let isMap = document.getElementById('map');

if (isMap) {

    //
    // ایجاد نقشه و تنظیمات اولیه
    var map = L.map('map').setView([35.6892, 51.3890], 10); // شروع از تهران
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);


    var marker = null; // متغیر برای ذخیره مارکر


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

}




jQuery(document).ready(function ($) {


    // $('.rating-stars .star').click(function (e) {
    //     e.preventDefault();

    //     // مقدار ویژگی 'for' را دریافت کن
    //     const forValue = $(this).attr('for');

    //     // استخراج عدد از for (مانند "star3" -> "3")
    //     const ratingValue = forValue.replace('star', '');

    //     for (let index = 1; index < 6; index++) {

    //         $('.rating-stars #s' + index).removeClass('bi-star-fill');
    //         $('.rating-stars #s' + index).removeClass('bi-star');
    //         if (ratingValue <= index) {
    //             $('.rating-stars ').addClass('bi-star-fill');
    //         }
    //         else {
    //             $('.rating-stars ').addClass('bi-star');

    //         }


    //     }

    //     // نمایش مقدار در کنسول
    //     console.log(ratingValue);

    // });


    $('.rating .star').click(function () {
        // مقدار ویژگی 'for' را دریافت کن
        const forValue = $(this).attr('for');
        const ratingValue = parseInt(forValue.replace('star', ''), 10); // استخراج عدد از for

        // ستاره‌های قبل و برابر را به حالت پر تغییر بده
        $('.rating .star i').each(function (index) {
            if (index < ratingValue) {
                $('.rating-stars #s' + (index + 1) + ' i').removeClass('bi-star').addClass('bi-star-fill');
            } else {
                $('.rating-stars #s' + (index + 1) + ' i').removeClass('bi-star-fill').addClass('bi-star');
            }
        });
    });

    $('#select2modal').select2({
        placeholder: 'جستجوی شهر، استان و ...',
        dir: 'rtl',
        theme: 'bootstrap5', // یا تم مناسب دیگر
        dropdownParent: $('#search-filter'), // حل مشکل موقعیت‌دهی
        language: {
            noResults: function () {
                return 'نتیجه‌ای یافت نشد.';
            },
            searching: function () {
                return 'در حال جستجو...';
            }
        },
    });

    // نمایش مودال هنگام کلیک
    $('.search-button').click(function (e) {
        e.preventDefault();
        $('#search-filter').modal('show');
    });

    $('#search-filter').on('hidden.bs.modal', function () {

        $('#btn_modal').val('submit_artwork');
        $('#description').text('');
        $('#category').val(0);
        $('#title_art').val('');
        $('#art_id').val(0);


    });

    $(document).on("submit", "#search-modal", function (e) {
        e.preventDefault();

        let goTo = "";

        let city = $('#select2modal').val();
        let course = $('#course-modal').val();
        let age = $('#age-modal').val();
        let gender = $('#gender-modal').val();

        if (city != '') {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'c=' + city;
        }

        if (course != 'all') {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'course=' + course;
        }

        if (age != 'all') {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'age=' + age;
        }

        if (gender != 'all') {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'gender=' + gender;
        }


        const params = new URLSearchParams(window.location.search);

        const sValue = params.get('s');


        if (sValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 's=' + sValue;
        }


        if (goTo !== "") {
            goTo = '/search/' + goTo;
        } else {
            goTo = '/all';
        }
        window.location.href = atlas_js.page_base + goTo;

    });


    $('#search-header').submit(function (e) {
        e.preventDefault();

        let inputSearch = $('#search-header-input').val();


        const params = new URLSearchParams(window.location.search);

        const cValue = params.get('c');
        const courseValue = params.get('course');
        const ageValue = params.get('age');
        const genderValue = params.get('gender');

        let goTo = '';

        if (cValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'c=' + cValue;
        }

        if (courseValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'course=' + courseValue;
        }

        if (ageValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'age=' + ageValue;
        }

        if (genderValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'gender=' + genderValue;
        }

        if (inputSearch !== "") {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 's=' + inputSearch;
        }

        window.location.href = atlas_js.page_base + 'search/' + goTo;

    });


    $('.onlyNumbersInput').on('input paste', function () {
        // پاک کردن تمام کاراکترهای غیرعددی
        this.value = this.value.replace(/[^0-9]/g, '');
    });


    $('#select2searchcity').select2({
        placeholder: 'انتخاب شهر',
        dir: 'rtl',
        allowClear: true,
        theme: 'search',
        language: {
            noResults: function () {
                return 'نتیجه‌ای یافت نشد.';
            },
            searching: function () {
                return 'در حال جستجو...';
            }
        },
    });


    $('#select2searchcity').on('change', function () {

        let cValue = $(this).val();
        const params = new URLSearchParams(window.location.search);

        const courseValue = params.get('course');
        const ageValue = params.get('age');
        const genderValue = params.get('gender');
        const sValue = params.get('s');

        let goTo = '';

        if (cValue != 0) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'c=' + cValue;
        }



        if (courseValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'course=' + courseValue;
        }

        if (ageValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'age=' + ageValue;
        }

        if (genderValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'gender=' + genderValue;
        }

        if (sValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 's=' + sValue;
        }

        window.location.href = atlas_js.page_base + 'search/' + goTo;

    });


    $('#modal-reset').click(function (e) {
        e.preventDefault();


        $('#course-modal').val('all');
        $('#age-modal').val('all');
        $('#gender-modal').val('all');
        $('#select2modal').val(null).trigger('change');

    });

    $('.close-search').click(function (e) {
        e.preventDefault();
        let remoweId = $(this).attr('id');

        const params = new URLSearchParams(window.location.search);

        const cValue = params.get('c');
        const courseValue = (remoweId == 'course') ? null : params.get('course');
        const ageValue = (remoweId == 'age') ? null : params.get('age');
        const genderValue = (remoweId == 'gender') ? null : params.get('gender');
        const sValue = (remoweId == 's') ? null : params.get('s');

        let isGoToStart = true;
        let goTo = '';

        if (cValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'c=' + cValue;
        }

        if (courseValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'course=' + courseValue;
            isGoToStart = false;
        }

        if (ageValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'age=' + ageValue;
            isGoToStart = false;
        }

        if (genderValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 'gender=' + genderValue;
            isGoToStart = false;
        }

        if (sValue != null) {
            let sq = (goTo == "") ? '?' : '&';
            goTo = goTo + sq + 's=' + sValue;
            isGoToStart = false;
        }

        if (isGoToStart && cValue != null) {

            goTo = '/city=' + cValue

            window.location.href = atlas_js.page_base + goTo;

        } else {
            window.location.href = atlas_js.page_base + 'search/' + goTo;
        }
    });


    $('#select2home').select2({
        placeholder: 'جستجوی شهر، استان و ...',
        dir: 'rtl',
        theme: 'bootstrap-5',
        language: {
            noResults: function () {
                return 'نتیجه‌ای یافت نشد.';
            },
            searching: function () {
                return 'در حال جستجو...';
            }
        },
    });




    $('#select2home').on('change', function () {
        let cityId = $(this).val();
        if (cityId > 0) {

            window.location.href = atlas_js.page_base + '/city=' + cityId;
        }

    });




    $('#select2').select2({
        placeholder: 'جستجوی شهر، استان و ...',
        dir: 'rtl',
        width: '200px',
        theme: 'bootstrap-5',
        language: {
            noResults: function () {
                return 'نتیجه‌ای یافت نشد.';
            },
            searching: function () {
                return 'در حال جستجو...';
            }
        },
    });


    $('.select2map').select2({
        placeholder: 'جستجو کنید...',
        dir: 'rtl',
        theme: 'bootstrap-5',
        language: {
            noResults: function () {
                return 'نتیجه‌ای یافت نشد.';
            },
            searching: function () {
                return 'در حال جستجو...';
            }
        },
    });


    $('#select2').on('change', function () {
        let dataGoto = $(this).attr('data-goto');
        let cityId = $(this).val();
        if (cityId > 0) {
            window.location.href = atlas_js.page_base + '/' + dataGoto + '=' + cityId;
        }
    });


    if (isMap) {

        $('input#subject').tagsInput({
            'width': '300px',
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
                url: atlas_js.ajaxurl,
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

            $spans.each(function () {
                const $span = $(this);
                if ($span.text() === inputValue) {
                    $span.addClass('activ');
                }
            });
            checkInputMatches();

        });

        $spans.on('click', function () {
            const $this = $(this);
            const text = $this.text().trim();

            const exists = $tagsInput.find(`span.tag:contains('${text}')`).length > 0;
            if (!exists) {
                const newTag = `
                    <span class="tag">
                        <span>${text}&nbsp;&nbsp;</span>
                        <a href="#" title="حذف">x</a>
                    </span>
                `;
                $tagsInput.find('#subject_addTag').before(newTag);

                let newimput = $input.val();
                $input.val(newimput + ',' + text);

                checkInputMatches();

            }
        });

        $('.tag a').click(function (e) {
            e.preventDefault();

            const $tag = $(this).closest('.tag');
            const tagText = $tag.text().trim();

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
            const newRow = `<div class="atlas-teacher-row row mb-2" >
                                <div class="col-10"><input class=" form-control " name="atlas[teacher][]" value=""></div>
                                <button type="button" class="btn btn-danger atlas-teacher-remove col-2">حذف</button>
                              </div>`;

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
    }

    $('#user_item_send').submit(function (e) {


        let title = $('#title').val();
        let ostan = $('#ostan').val();
        let city = $('#city').val();

        let massege = "";


        if (title == "") {
            massege += `<div class="alert alert-danger" role="alert">
           عنوان  مرکز قرآنی را وارد کنید
        </div>`;
        }

        if (Number(ostan) == 0) {
            massege += `<div class="alert alert-danger" role="alert">
           استان مرکز قرآنی را وارد کنید
        </div>`;
        }


        if (Number(city) == 0) {
            massege += `<div class="alert alert-danger" role="alert">
           شهر مرکز قرآنی را وارد کنید
        </div>`;

        }

        if (marker == null) {

            massege += `<div class="alert alert-danger" role="alert">
             موقعیت مکانی مرکز قرآنی را وارد کنید
         </div>`;
        }

        if (massege != "") {
            $('#alert_item_danger').html(massege);
            e.preventDefault();

        }








    });















})

