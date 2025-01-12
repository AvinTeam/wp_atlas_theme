






jQuery(document).ready(function ($) {

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
    //             var remainingChars = args.minimum - args.input.length;
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

