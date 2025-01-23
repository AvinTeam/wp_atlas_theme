<?php
    $iran = new Iran_Area;

    $search = $iran->all_city();

    $atlas     = get_query_var('atlas');
    $this_page = explode('=', $atlas);
    if ($this_page[ 0 ] == 'city') {
        $city = $this_page[ 1 ];

    } else {
        $city = (isset($_GET[ 'c' ]) && absint($_GET[ 'c' ])) ? absint($_GET[ 'c' ]) : '0';
    }
    $course = (isset($_GET[ 'course' ]) && ! empty($_GET[ 'course' ])) ? sanitize_text_field($_GET[ 'course' ]) : 'all';
    $age    = (isset($_GET[ 'age' ]) && ! empty($_GET[ 'age' ])) ? sanitize_text_field($_GET[ 'age' ]) : 'all';
    $gender = (isset($_GET[ 'gender' ]) && ! empty($_GET[ 'gender' ])) ? sanitize_text_field($_GET[ 'gender' ]) : 'all';

?>


<div class="modal fade" id="search-filter" aria-hidden="true" aria-labelledby="search-filterLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="search-modal">

                <div class="modal-header d-flex flex-row justify-content-between">
                    <img class="modal-title fs-5" id="search-filterLabel"
                        src="<?php echo atlas_panel_image('title-modal.svg') ?>">
                    <img class="close-modal" data-bs-dismiss="modal" aria-label="Close"
                        src="<?php echo atlas_panel_image('close-modal.svg') ?>">

                </div>
                <div class="modal-body">
                    <?php wp_nonce_field('submit_profile_referee' . atlas_cookie()); ?>



                    <div class="mb-3">
                        <label for="select2modal" class="form-label">استان و شهر</label>
                        <select id="select2modal" class="form-select form-select w-100" name="select2modal">
                            <option></option>
                            <?php foreach ($search as $key => $value): ?>
                            <option<?php selected($city, $key)?> value="<?php echo $key ?>"><?php echo $value ?>
                                </option>
                                <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="course-modal" class="form-label">نحوه برگزاری کلاس</label>
                        <select id="course-modal" class="form-select form-select w-100" name="course">
                            <option                                    <?php selected($course, 'all')?> value="all">همه موارد</option>
                            <option                                    <?php selected($course, 'online')?> value="online">حضوری</option>
                            <option                                    <?php selected($course, 'offline')?> value="offline">مجازی</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="age-modal" class="form-label">مقاطع سنی</label>
                        <select id="age-modal" class="form-select form-select w-100" name="age">
                            <option                                    <?php selected($age, 'all')?> value="all">همه مقاطع سنی</option>
                            <option                                    <?php selected($age, 7)?> value="7">زیر 7 سال</option>
                            <option                                    <?php selected($age, 12)?> value="12">7 تا 12 سال</option>
                            <option                                    <?php selected($age, 18)?> value="18">12 تا 18 سال</option>
                            <option                                    <?php selected($age, 'old')?> value="old">18 سال به بالا</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="gender-modal" class="form-label">جنسیت</label>
                        <select id="gender-modal" class="form-select form-select w-100" name="gender">
                            <option value="all">همه ی جنسیت ها</option>
                            <option                                    <?php selected($gender, 'woman')?> value="woman">خواهران</option>
                            <option                                    <?php selected($gender, 'man')?> value="man">برادران</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer d-flex flex-row justify-content-between">

                    <button id="modal-reset" class="btn btn-light btn-atlas-reset" type="reset">
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