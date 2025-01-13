let pageLogin = document.getElementById('loginForm');
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






jQuery(document).ready(function ($) {


    $('.onlyNumbersInput').on('input paste', function () {
        // پاک کردن تمام کاراکترهای غیرعددی
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $('#select2').select2({
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























})

