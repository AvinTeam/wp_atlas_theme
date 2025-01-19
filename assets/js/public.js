const pageLogin = document.getElementById('loginForm');
if (pageLogin) {


    let isSendSms = true;

    function send_sms() {
        let mobile = document.getElementById('mobile').value;
        if (validateMobile(mobile)) {

            const xhr = new XMLHttpRequest();
            xhr.open('POST', atlas_js.ajaxurl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {

                const response = JSON.parse(xhr.responseText);

                if (xhr.status === 200) {
                    if (response.success) {
                        document.getElementById('mobileForm').style.display = 'none';
                        document.getElementById('codeVerification').style.display = 'block';
                        document.getElementById('resendCode').disabled = true;
                        startTimer();
                    }
                } else {

                    let loginAlert = document.getElementById('login-alert');

                    loginAlert.classList.remove('d-none');
                    loginAlert.textContent = response.data;
                }
            };
            xhr.send(`action=atlas_sent_sms&nonce=${atlas_js.nonce}&mobileNumber=${mobile}`);

        } else {

            let loginAlert = document.getElementById('login-alert');

            loginAlert.classList.remove('d-none');
            loginAlert.textContent = 'شماره موبایل نامعتبر است';

        }
    }


    pageLogin.addEventListener('submit', function (event) {
        event.preventDefault();

        if (isSendSms) {
            isSendSms = false;
            send_sms();
        }
    });


    document.getElementById('verifyCode').addEventListener('click', function () {
        let mobile = document.getElementById('mobile').value;

        let verificationCode = document.getElementById('verificationCode').value;



        const xhr = new XMLHttpRequest();
        xhr.open('POST', atlas_js.ajaxurl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {

            const response = JSON.parse(xhr.responseText);

            if (xhr.status === 200) {
                if (response.success) {
                    location.reload();
                }
            } else {

                let loginAlert = document.getElementById('login-alert');

                loginAlert.classList.remove('d-none');
                loginAlert.textContent = response.data;
            }
        };
        xhr.send(`action=atlas_sent_verify&nonce=${atlas_js.nonce}&otpNumber=${verificationCode}&mobileNumber=${mobile}`);


    });


    document.getElementById('editNumber').addEventListener('click', function () {
        document.getElementById('mobileForm').style.display = 'block';
        document.getElementById('codeVerification').style.display = 'none';
        isSendSms = true;
        startTimer(true);

    });

    document.getElementById('resendCode').addEventListener('click', function () {
        send_sms();
    });

    function validateMobile(mobile) {
        let regex = /^09\d{9}$/;
        return regex.test(mobile);
    }

    function startTimer(end = false) {

        if (end) { clearInterval(interval); } else {


            let timer = Number(atlas_js.option.set_timer) * 60,
                minutes, seconds;
            interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                document.getElementById('timer').textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    document.getElementById('resendCode').disabled = false;
                }
            }, 1000);
        }
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


    $('.onlyNumbersInput').on('input paste', function () {
        // پاک کردن تمام کاراکترهای غیرعددی
        this.value = this.value.replace(/[^0-9]/g, '');
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
        console.log('nexy');
        let cityId = $(this).val();
        if (cityId > 0) {

            window.location.href = atlas_js.page_base + '/city=' + cityId;
        }


        console.log($(this).val());


    });

    
    $('#select2home').on('change', function () {
        console.log('nexy');
        let cityId = $(this).val();
        if (cityId > 0) {

            window.location.href = atlas_js.page_base + '/city=' + cityId;
        }


        console.log($(this).val());


    });

    // $('#ajax-select').select2({
    //     placeholder: 'جستجو کنید...',
    //     minimumInputLength: 2,
    //     language: {
    //         inputTooShort: function (args) {
    //             let remainingChars = args.minimum - args.input.length;
    //             return `لطفاً حداقل ${remainingChars} حرف وارد کنید.`;
    //         },
    //         noResults: function () {
    //             return 'نتیجه‌ای یافت نشد.';
    //         },
    //         searching: function () {
    //             return 'در حال جستجو...';
    //         }
    //     },
    //     ajax: {
    //         url: atlas_js.ajaxurl,
    //         type: 'POST',
    //         delay: 250,
    //         data: function (params) {
    //             return {
    //                 action: 'ajax_search',
    //                 search: params.term,
    //                 nonce: atlas_js.nonce
    //             };
    //         },
    //         processResults: function (data) {

    //             console.log(data);
    //             // return {
    //             //     results: data.map(function (item) {
    //             //         return { id: item.id, text: item.title };
    //             //     })
    //             // };
    //         }
    //     }
    // });





    if (isMap) {
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
                url: atlas_js.ajaxurl,
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {

                    console.log(response);
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
            console.log('atlas-teacher-add');
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

















})

