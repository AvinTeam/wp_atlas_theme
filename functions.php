<?php

(defined('ABSPATH')) || exit;
define('ATLAS_VERSION', '1.4.0');

define('ATLAS_PATH', get_template_directory() . "/");
define('ATLAS_INCLUDES', ATLAS_PATH . 'includes/');
define('ATLAS_CLASS', ATLAS_PATH . 'classes/');
define('ATLAS_CORE', ATLAS_PATH . 'core/');
define('ATLAS_FUNCTION', ATLAS_PATH . 'functions/');
define('ATLAS_VIEWS', ATLAS_PATH . 'views/');

define('ATLAS_URL', get_template_directory_uri() . "/");
define('ATLAS_ASSETS', ATLAS_URL . 'assets/');
define('ATLAS_CSS', ATLAS_ASSETS . 'css/');
define('ATLAS_JS', ATLAS_ASSETS . 'js/');
define('ATLAS_IMAGE', ATLAS_ASSETS . 'image/');
define('ATLAS_VENDOR', ATLAS_ASSETS . 'vendor/');

if (! defined('ATLAS_PAGE_BASE')) {
    define('ATLAS_PAGE_BASE', 'atlas');
}

require_once ATLAS_CORE . '/accesses.php';

require_once ATLAS_INCLUDES . '/func.php';
require_once ATLAS_INCLUDES . '/theme_filter.php';

require_once ATLAS_INCLUDES . '/postype.php';
require_once ATLAS_INCLUDES . '/meta_boxs.php';
require_once ATLAS_INCLUDES . '/styles.php';
require_once ATLAS_CLASS . '/Iran_Area.php';
require_once ATLAS_INCLUDES . '/init.php';
require_once ATLAS_INCLUDES . '/ajax.php';
require_once ATLAS_INCLUDES . '/theme-function.php';
require_once ATLAS_INCLUDES . '/init_user_submit.php';

// require_once ATLAS_INCLUDES . '/jdf.php';

// require_once ATLAS_CLASS . '/Nasr.php';

$atlas_option = atlas_start_working();

if (is_admin()) {
    //require_once ATLAS_CLASS . '/List_Table.php';
    require_once ATLAS_INCLUDES . '/menu.php';
    require_once ATLAS_INCLUDES . '/install.php';
    require_once ATLAS_INCLUDES . '/edit_column_institute.php';
    require_once ATLAS_INCLUDES . '/edit_user_table.php';
    require_once ATLAS_INCLUDES . '/handle_download.php';

}

if (isset($_GET[ 'test' ])) {

    $args = [
        'post_type'      => 'institute',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'p'              => 9,
     ];

    if (isset($_GET[ 's' ]) && ! empty($_GET[ 's' ])) {
        $args[ 's' ] = sanitize_text_field($_GET[ 's' ]);
    }

    if (isset($_GET[ 'format_art' ])) {
        $args[ 'tax_query' ][  ] = [
            'taxonomy' => 'format_art',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET[ 'format_art' ]),
         ];
    }

    $query = new WP_Query($args);

    $data = [  ];

    print_r($row);

    exit;

}

function maintenance_mode()
{
    // بررسی اگر کاربر وارد نشده یا مدیر نیست
    // تنظیم کد وضعیت HTTP (اختیاری)
    http_response_code(503);

    // نمایش پیام سفارشی
    echo '<!DOCTYPE html>
        <html lang="fa">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>در حال تعمیر</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    background-color: #f7f7f7;
                    color: #333;
                    padding: 50px;
                }
                h1 {
                    color: #d63031;
                }
                p {
                    font-size: 18px;
                }
            </style>
        </head>
        <body>
            <h1>سایت در حال تعمیر است</h1>
        </body>
        </html>';
    exit; // جلوگیری از اجرای بیشتر

}
add_action('template_redirect', 'maintenance_mode');