<?php

    $atlas_body = 'atlas-home';

    get_header();

    $iran = new Iran_Area;

    $search = $iran->all_city();
?>

<div class="container-fluid home-search-box px-5 py-2">
    <div class="row justify-content-center align-items-center mx-auto atlas-row">
        <div class="col-12 col-md-6 mb-4 mb-md-0 d-none d-md-block">
            <img class="w-100" alt="iran" src="<?php echo atlas_panel_image('iran_ghoran.png') ?>">
        </div>
        <div class="col-12 col-md-6">
            <img class="w-100" alt="بسم الله الرحمن الرحيم" src="<?php echo atlas_panel_image('besme-allah.svg') ?>">

            <div class="home-search-box-input my-4">
                <select id="select2home" class="form-select form-select-lg py-3 my-4 w-100" name="state">
                    <option></option>
                    <?php foreach ($search as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="home-btn mt-2 d-flex justify-content-center">


                    <img class="search-button me-1  d-none d-lg-block"
                        src="<?php echo atlas_panel_image('btn-advanced-search.svg') ?>">
                    <a href=" <?php echo atlas_base_url('all')?>"><img class="search-button-all"
                            src="<?php echo atlas_panel_image('btn-show-all.svg') ?>"></a>
                </div>
            </div>
            <!-- <img class="w-75 float-lg-end mx-auto d-block" src="<?php echo atlas_panel_image('aye.png') ?>"> -->
        </div>
    </div>
</div>

<div class="container-fluid ostan-list">
    <div class="text-center">
        <img class="chose_ostan" alt="انتخاب استان" src="<?php echo atlas_panel_image('ostan-title.png') ?>">
    </div>
    <div class="container-fluid px-md-5 px-0 mt-3">
        <div class="row justify-content-center align-items-center mx-auto atlas-row" id="ostan-items-row">
            <?php foreach ($iran->select() as $ostan): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center mb-3 ostan-item">
                <a href="<?php echo atlas_base_url('province=' . $ostan->id)?>"
                    class="d-flex justify-content-center align-items-center ostan px-4 py-3 text-white fw-bold">
                    <?php echo $ostan->name?>
                </a>
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