<?php

    use atlasclass\Iran_Area;

    $atlas = get_query_var('atlas');

    $atlas = ($_SERVER[ 'REQUEST_METHOD' ] === 'POST' && isset($_POST[ 'act_user' ]) && $_POST[ 'act_user' ] == 'form_submit') ? 'panel' : $atlas;

    $iran = new Iran_Area;

    $search = $iran->all_city();

    $this_page = explode('=', $atlas);

    if ($this_page[ 0 ] == 'city') {
        $city = $this_page[ 1 ];

    } else {
        $city = (isset($_GET[ 'c' ]) && absint($_GET[ 'c' ])) ? absint($_GET[ 'c' ]) : '0';
    }
    $center_type = (isset($_GET[ 'type' ]) && ! empty($_GET[ 'type' ])) ? sanitize_text_field($_GET[ 'type' ]) : 'all';
    $course      = (isset($_GET[ 'course' ]) && ! empty($_GET[ 'course' ])) ? sanitize_text_field($_GET[ 'course' ]) : 'all';
    $age         = (isset($_GET[ 'age' ]) && ! empty($_GET[ 'age' ])) ? sanitize_text_field($_GET[ 'age' ]) : 'all';
    $gender      = (isset($_GET[ 'gender' ]) && ! empty($_GET[ 'gender' ])) ? sanitize_text_field($_GET[ 'gender' ]) : 'all';

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
                        <label for="type-modal" class="form-label">نوع مرکز</label>
                        <select id="type-modal" class="form-select form-select w-100" name="type">
                            <option                                                                       <?php selected($center_type, 'all')?> value="all">همه موارد</option>
                            <option                                                                       <?php selected($center_type, 'mohfel')?> value="mohfel">محفل زندگی با آیه ها
                            </option>
                            <option                                                                       <?php selected($center_type, 'Institute')?> value="Institute">موسسه</option>
                            <option                                                                       <?php selected($center_type, 'house_of_quran')?> value="house_of_quran">خانه قرآن
                            </option>
                            <option                                                                       <?php selected($center_type, 'education')?> value="education">آموزش پرورش</option>
                            <option                                                                       <?php selected($center_type, 'besij')?> value="besij">پایگاه قرآنی مساجد</option>
                            <option                                                                       <?php selected($center_type, 'home')?> value="home">جلسات خانگی</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="course-modal" class="form-label">نحوه برگزاری کلاس</label>
                        <select id="course-modal" class="form-select form-select w-100" name="course">
                            <option                                                                       <?php selected($course, 'all')?> value="all">همه موارد</option>
                            <option                                                                       <?php selected($course, 'online')?> value="online">حضوری</option>
                            <option                                                                       <?php selected($course, 'offline')?> value="offline">مجازی</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="age-modal" class="form-label">گروه سنی مخاطبین</label>
                        <select id="age-modal" class="form-select form-select w-100" name="age">
                            <option                                                                       <?php selected($age, 'all')?> value="all">همه گروه سنی</option>
                            <option                                                                       <?php selected($age, 7)?> value="7">زیر 7 سال</option>
                            <option                                                                       <?php selected($age, 12)?> value="12">7 تا 12 سال</option>
                            <option                                                                       <?php selected($age, 18)?> value="18">12 تا 18 سال</option>
                            <option                                                                       <?php selected($age, 'old')?> value="old">18 سال به بالا</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="gender-modal" class="form-label">جنسیت</label>
                        <select id="gender-modal" class="form-select form-select w-100" name="gender">
                            <option value="all">همه ی جنسیت ها</option>
                            <option                                                                       <?php selected($gender, 'woman')?> value="woman">خواهران</option>
                            <option                                                                       <?php selected($gender, 'man')?> value="man">برادران</option>
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

<footer class="container-fluid mt-2 my-md-5">
    <div class=" mx-auto atlas-row">

        <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center p-md-5 bg-linear-gradient rounded-2 gap-4"
            style="height: 136px;">
            <span class="fw-heavy text-white"></span>
            <div class="footer-link">

                <?php wp_nav_menu([
                        'theme_location' => 'footer-menu',
                        'container'      => false,
                        'menu_class'     => '',
                        'items_wrap'     => '%3$s', // فقط آیتم‌های منو
                        'walker'         => new Footer_Menu_Walker(),
                 ]); ?>

            </div>

        </div>
        <div class=" mx-auto atlas-row footercopy rounded-bottom-4 text-center d-flex flex-column justify-content-center align-items-center text-white py-2">
            <span>کلیه حقوق این سامانه متعلق به سامانه جامع زندگی با آیه ها می باشد.</span>
            <div class="w-100 text-center py-2"><a class=" text-white" href="https://avinmedia.ir/" target="_blank">طراحی و پشتیبانی: گروه هنری رسانه ای آوین</a></div>
        </div>
    </div>

</footer>
<?php wp_footer()?>

</body>

</html>