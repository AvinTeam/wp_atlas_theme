<?php get_header(); ?>


<div class="container-fluid">
    <div class="institute-head-box px-4 py-3 mx-auto atlas-row d-flex flex-column gap-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between">
            <div class="breadcrumbs text-white d-flex flex-wrap gap-2  align-content-center justify-content-center">
                <img src="<?php echo atlas_panel_image('home-icone.svg') ?>">
                <a class="text-white" href="<?php echo home_url()?>">صفحه نخست</a>
                <img src="<?php echo atlas_panel_image('arrow.svg') ?>">
                <span class="text-white-50"><?php the_title(); ?></span>
            </div>
        </div>

        <div class="d-flex justify-content-center my-2">
            <div class="p-2 text-center">
                <div class="px-3 py-2 text-center fw-bold text-white">
                    <span><?php the_title(); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid mt-2">

    <div class="atlas-row mx-auto d-flex flex-row gap-2">
        <div class="content"><?php the_content(); ?></div>
    </div>
</div>

<?php get_footer(); ?>