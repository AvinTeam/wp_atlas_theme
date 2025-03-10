<?php

    global $atlas_body;

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
    <script async defer src="https://tianji.ayeh.net/tracker.js" data-website-id="cm81frvegw9frul654y1ywdgn"></script>
</head>

<body class="<?php echo(isset($atlas_body) && ! empty($atlas_body)) ? $atlas_body : '' ?>">
    <header class="container-fluid py-3 px-5">
        <div class="d-flex flex-wrap align-items-center justify-content-between mx-auto atlas-row ">
            <!-- بخش جستجو -->
            <div class="col-12 col-md-4 text-center d-flex align-items-center justify-content-start mb-3 mb-md-0">
                <form id="search-header" class="atlas-search w-75 w-md-75 d-flex">
                    <img class="search-img me-2" src="<?php echo atlas_panel_image('search.svg') ?>" alt="جستجو">
                    <input type="text" name="search" id="search-header-input" class="form-control flex-grow-1"
                        aria-label="Search" value="<?php echo(isset($_GET[ 's' ])) ? sanitize_text_field($_GET[ 's' ]) : '' ?>" placeholder="مرکز قرآنی مد نظر خود را وارد کنید">
                    <img id="fiter-btn" class="search-button ms-2"
                        src="<?php echo atlas_panel_image('search_filter.svg') ?>">
                </form>
            </div>

            <!-- لوگو -->
            <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                <a href="<?php echo home_url() ?>">
                    <img src="<?php echo atlas_panel_image('logo.png') ?>" alt="لوگو" class="w-100">
                </a>
            </div>

            <!-- لینک دیگر -->
            <div
                class="col-12 col-md-4 text-center text-md-end d-flex flex-row justify-content-end gap-3 align-items-center ">
                <a class="btn btn-primary d-flex flex-row justify-content-center align-items-center gap-2 text-nowrap" href="<?php echo atlas_base_url('panel') ?>">
                    <img style="width:24px ;" src="<?php echo atlas_panel_image('login-icon.png') ?>">
                    <span>|</span>
                    <span>محفل ساز شو</span>
                </a>
                <a class=" d-none d-lg-block" href="<?php echo home_url() ?>">
                    <img src="<?php echo atlas_panel_image('zendegibaayeha.png') ?>" alt="زندگی با آیه‌ها"
                        style="height: 100px;" class="additional-link-img">
                </a>
            </div>
        </div>
    </header>