<?php
    $iran = new Iran_Area;

    $search = $iran->all_city();

?>


<div class="modal fade" id="search-filter" aria-hidden="true" aria-labelledby="search-filterLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="search-modal">

                <div class="modal-header d-flex flex-row justify-content-between">
                    <img class="modal-title fs-5" id="search-filterLabel"
                        src="<?php echo atlas_panel_image('title-modal.svg') ?>">
                    <img class="" data-bs-dismiss="modal" aria-label="Close"
                        src="<?php echo atlas_panel_image('close-modal.svg') ?>">

                </div>
                <div class="modal-body">
                    <?php wp_nonce_field('submit_profile_referee' . atlas_cookie()); ?>



                    <div class="mb-3">
                        <label for="select2modal" class="form-label">استان و شهر</label>
                        <select id="select2modal" class="form-select form-select w-100" name="state">
                            <option></option>
                            <?php foreach ($search as $key => $value): ?>
                            <option value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="course" class="form-label">نحوه برگزاری کلاس</label>
                        <select id="course" class="form-select form-select w-100">
                            <option value="all">همه موارد</option>
                            <option value="online">حضوری</option>
                            <option value="offline">مجازی</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="age" class="form-label">مقاطع سنی</label>
                        <select id="age" class="form-select form-select w-100">
                        <option value="all">همه مقاطع سنی</option>
                        <option value="7">زیر 7 سال</option>
                        <option value="12">7 تا 12 سال</option>
                        <option value="18">12 تا 18 سال</option>
                        <option value="old">18 سال به بالا</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">جنسیت</label>
                        <select id="gender" class="form-select form-select w-100">
                        <option value="all">همه ی جنسیت ها</option>
                        <option value="woman">خواهران</option>
                        <option value="man">برادران</option>              
                        </select>
                    </div>

                </div>
                <div class="modal-footer d-flex flex-row justify-content-between">

                    <button class="btn btn-light btn-atlas-reset" type="reset">
                        <img src="<?php echo atlas_panel_image('reset-btn.svg') ?>">
                        <div class="vr"></div>
                        <span>پاک کردن فیلتر</span>
                    </button>

                    <button class="btn btn-primary btn-atlas-submit text-white" type="submit">
                        <img src="<?php echo atlas_panel_image('submit-btn.svg') ?>">
                        <div class="vr"></div>
                        <span>اعمال تغییرات</span>
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>






























<nav class="p-3 bg-light mt-5 text-center">
    <span>کلیه حقوق این سامانه متعلق به سامانه جامع زندگی با آیه ها می باشد.</span>
</nav>
<?php wp_footer()?>

</body>

</html>