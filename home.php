<?php

$atlas_body = 'atlas-home';

get_header();

    $iran = new Iran_Area;

    $search = $iran->all_city();
?>

<div class="container-fluid home-search-box px-5 py-0">
    <div class=" row justify-content-center align-items-center atlas-row">
        <div class="col-6">
            <img class="w-100" alt="iran" src="<?php echo atlas_panel_image('iran_ghoran.svg') ?>">
        </div>
        <div class="col-6">

            <img class="w-100" alt="بسم الله الرحمن الرحيم" src="<?php echo atlas_panel_image('besme-allah.svg') ?>">

            <div class="home-search-box-input my-4">
                <select id="select2home" class="form-select form-select-lg py-3 my-4" name="state" style="width: 100%;">
                    <option></option>
                    <?php foreach ($search as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>

                </select>
                <div class="home-btn mt-2">
                    <!-- <img class="search-button" src="<?php echo atlas_panel_image('btn-advanced-search.svg') ?>"> -->
                    <a href=" <?=atlas_base_url('all') ?>"><img class="search-button" src="<?php echo atlas_panel_image('btn-show-all.svg') ?>"></a>
                    <!-- <img class="search-button" src="<?php echo atlas_panel_image('btn-search.svg') ?>"> -->

                </div>

            </div>

            <img style="float: left;" class="w-75" src="<?php echo atlas_panel_image('aye.svg') ?>">

        </div>
    </div>
</div>

<div class="container-fluid ostan-list">
    <div class="text-center">
        <img class="chose_ostan" alt="انتخاب استان" src="<?php echo atlas_panel_image('ostan-title.png') ?>">
    </div>
    <div class="container-fluid px-5 mt-3">
        <div class="row justify-content-center  align-items-center">
            <?php foreach ($iran->select() as $ostan): ?>
            <div class="m-2 col-2 text-center">
                <a href="<?= atlas_base_url('province='.$ostan->id) ?>"
                    class=" d-flex justify-content-center align-items-center  ostan px-4 py-3 text-white fw-bold"><?=$ostan->name?></a>
            </div>

            <?php endforeach; ?>
        </div>

    </div>
</div>





<!-- 

<div class="container mt-5">

    <a class="btn btn-dark m-2 text-white" href="/atlas/panel">ورود به پنل کاربری</a>


</div> -->





<?php get_footer(); ?>