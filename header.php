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

</head>
<body class="<?php echo (isset($atlas_body) && !empty($atlas_body)) ? $atlas_body : ''?>">
<header class="container-fluid py-3 px-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between mx-auto atlas-row ">
        <!-- بخش جستجو -->
        <div class="col-12 col-md-4 text-center d-flex align-items-center justify-content-start mb-3 mb-md-0">
            <form id="search-header" class="atlas-search w-100 w-md-75 d-flex">
                <img class="search-img me-2" src="<?php echo atlas_panel_image('search.svg') ?>" alt="جستجو">
                <input type="text" name="search" id="search-header-input" class="form-control flex-grow-1" aria-label="Search" placeholder="مرکز قرآنی مد نظر خود را وارد کنید">
                <img id="fiter-btn" class="search-button ms-2" src="<?php echo atlas_panel_image('search_filter.svg') ?>">
            </form>
        </div>

        <!-- لوگو -->
        <!-- <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
            <a href="<?php echo bloginfo('url') ?>">
                <img src="<?php echo atlas_panel_image('logo.png') ?>" alt="لوگو" class="logo-img ">
            </a>
        </div> -->

        <!-- لینک دیگر -->
        <div class="col-12 col-md-4 text-center text-md-end d-none d-md-block">
            <a href="https://zendegibaayeha.ir/">
                <img src="<?php echo atlas_panel_image('zendegibaayeha.svg') ?>" alt="زندگی با آیه‌ها" class="additional-link-img">
            </a>
        </div>
    </div>
</header>