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
        <div class="d-flex align-items-center justify-content-between  atlas-row ">
            <div class="col-4  text-center d-flex align-items-center justify-content-start ">
                <div class="atlas-search w-75 ">
                    <img class="search-img" src="<?php echo atlas_panel_image('search.svg') ?>" alt="جستجو">
                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                    <img class="search-button" src="<?php echo atlas_panel_image('search_filter.svg') ?>">
                </div>
            </div>
            <div class="col-4 text-center">
                <a href="<?php echo bloginfo('url') ?>"><img src="<?php echo atlas_panel_image('logo.svg') ?>"
                        alt="جستجو"></a>

            </div>
            <div class="col-4 text-end">
                <a href="https://zendegibaayeha.ir/"><img src="<?php echo atlas_panel_image('zendegibaayeha.svg') ?>"
                        alt="جستجو"></a>
            </div>
        </div>
    </header>