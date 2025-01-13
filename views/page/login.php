<?php

function atlas_title_filter_404($title)
{

    $title = get_bloginfo('name') . " | ورود ";
    return $title;
}
add_filter('wp_title', 'atlas_title_filter_404');

get_header(); ?>







<style>
body,
html {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f8f9fa;
}

.login-box {
    width: 450px;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.timer {
    font-size: 1.2em;
    margin-top: 10px;
}
</style>








<div class="login-box">

    <form id="loginForm">
        
        <div id="mobileForm">
            <h3 class="text-center">ورود</h3>

            <?php wp_nonce_field('atlas_login_page' . atlas_cookie()); ?>

            <div class="form-group text-start">
                <label for="mobile">شماره موبایل</label>
                <input type="text" inputmode="numeric" pattern="\d*" class="form-control mt-2 onlyNumbersInput"
                    id="mobile" maxlength="11" placeholder="شماره موبایل خود را وارد کنید">
            </div>
            <div class="form-group d-grid mt-2 ">
                <button type="submit" class="btn btn-primary btn-block">ورود</button>

            </div>
        </div>
        <div id="codeVerification" class="text-start" style="display: none;">
            <h4 class="text-center">کد تایید</h4>
            <div class="form-group d-grid mt-2">
                <label for="verificationCode">کد تایید</label>
                <input type="text" inputmode="numeric" pattern="\d*" class="form-control onlyNumbersInput"
                    id="verificationCode" maxlength="<?=$atlas_option[ 'set_code_count' ]?>"
                    placeholder="کد تایید را وارد کنید">
            </div>
            <div class="d-grid mt-2 gap-2">
                <div class="timer text-center" id="timer">00:00</div>

                <button type="submit" class="btn btn-primary btn-block" id="verifyCode">تایید کد</button>
                <button type="button" class="btn btn-secondary btn-block" id="resendCode" disabled>ارسال مجدد
                    کد</button>
                <button type="button" class="btn btn-link btn-block" id="editNumber">ویرایش شماره</button>
            </div>
        </div>
    </form>
    <div id="login-alert" class="alert alert-danger mt-2 d-none" role="alert"></div>

</div>

<?php get_footer(); ?>