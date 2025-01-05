<?php (defined('ABSPATH')) || exit;?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
<style>




.tagify__input {
    direction: rtl;
    /* راست‌چین کردن متن داخل ورودی */
    text-align: right;
    /* تنظیم راست‌چین بودن متن */
}

.tagify__tag {
    direction: rtl;
    /* راست‌چین کردن تگ‌ها */
    text-align: right;
    /* متن داخل تگ‌ها راست‌چین شود */
}
</style>


<div class="atlas_parent">
    <div id="atlas-general-preview" class="menu-preview">
        <table class="form-table">

            <tr>
                <th>نام مسئول</th>
                <td><input class="regular-text" name="atlas[responsible]"
                        value="<?=$atlas_institute[ 'responsible' ]?>"></td>
            </tr>
            <tr>
                <th>شماره موبایل مسئول</th>
                <td><input class="regular-text" name="atlas[responsible-mobile]"
                        value="<?=$atlas_institute[ 'responsible-mobile' ]?>"></td>
            </tr>
            <tr>
                <th>حالت مرکز</th>
                <td class="center-type">
                    <fieldset>
                        <legend class="screen-reader-text"><span> حالت مرکز </span></legend>

                        <label><input type="radio" name="atlas[center-mode]" value="public"
                                <?=checked('public', $atlas_institute[ 'center-mode' ])?>><span
                                class="date-time-text">عمومی</span></label>

                        <label><input type="radio" name="atlas[center-mode]" value="private"
                                <?=checked('private', $atlas_institute[ 'center-mode' ])?>><span
                                class="date-time-text">خصوصی</span></label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">نوع مرکز</th>
                <td class="center-type">
                    <fieldset>
                        <legend class="screen-reader-text"><span> نوع مرکز </span></legend>

                        <label><input type="radio" name="atlas[center-type]" value="Institute"
                                <?=checked('Institute', $atlas_institute[ 'center-type' ])?>><span
                                class="date-time-text">موسسه</span></label>

                        <label><input type="radio" name="atlas[center-type]" value="house_of_quran"
                                <?=checked('house_of_quran', $atlas_institute[ 'center-type' ])?>><span
                                class="date-time-text">خانه قرآن</span></label>

                        <label><input type="radio" name="atlas[center-type]" value="mohfel"
                                <?=checked('mohfel', $atlas_institute[ 'center-type' ])?>><span
                                class="date-time-text">محفل</span></label>

                        <label><input type="radio" name="atlas[center-type]" value="education"
                                <?=checked('education', $atlas_institute[ 'center-type' ])?>><span
                                class="date-time-text">آموزش پرورش</span></label>

                        <label><input type="radio" name="atlas[center-type]" value="besij"
                                <?=checked('besij', $atlas_institute[ 'center-type' ])?>><span
                                class="date-time-text">دارالقرآن بسیج</span></label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th>شماره ارتباط با مرکز</th>
                <td><input class="regular-text" name="atlas[phone]" value="<?=$atlas_institute[ 'phone' ]?>"></td>
            </tr>
            <tr>
                <th>استان</th>
                <td>
                    <select name="atlas[ostan]" id="ostan">
                        <option value="0">انتخاب استان</option>

                        <?php foreach ($ostan as $key => $value): ?>
                        <option value="<?=$value->id?>" <?=selected($value->id, $atlas_institute[ 'ostan' ])?>>
                            <?=$value->name?></option>
                        <?php endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>شهر</th>
                <td>
                    <select name="atlas[city]" id="city">
                        <option value="0">انتخاب شهرستان</option>
                        <?php foreach ($city as $key => $value): ?>
                        <option value="<?=$value->id?>" <?=selected($value->id, $atlas_institute[ 'city' ])?>>
                            <?=$value->name?></option>
                        <?php endforeach;?>

                    </select>
                </td>
            </tr>

            <tr>
                <th>نقشه</th>
                <td>
                    <input type="hidden" name="atlas[map][lat]" id="map-lat"
                        value="<?=$atlas_institute[ 'map' ][ 'lat' ]?>">
                    <input type="hidden" name="atlas[map][lng]" id="map-lng"
                        value="<?=$atlas_institute[ 'map' ][ 'lat' ]?>">
                    <div style=" width: 100%;height: 500px;" id="map"></div>
                </td>
            </tr>

            <tr>
                <th>آدرس</th>
                <td>
                    <textarea name="atlas[address]" rows="5" cols="50" id="atlas-address"
                        class="large-text"><?=$atlas_institute[ 'address' ]?></textarea>
                </td>
            </tr>
            <tr>
                <th>لینک سایت / فضای مجازی</th>
                <td><input class="regular-text" name="atlas[link]" value="<?=$atlas_institute[ 'link' ]?>"></td>
            </tr>
            <tr class="center-type">
                <th>جنسیت هدف</th><?=$atlas_institute[ 'address' ]?>
                <td>
                    <fieldset>
                        <label for="gender-woman">
                            <input name="atlas[gender][]" type="checkbox" id="gender-woman" value="woman"
                                <?php if (in_array('woman', $atlas_institute[ 'gender' ])) {echo 'checked';}?>>خانم</label>

                        <label for="gender-man">
                            <input name="atlas[gender][]" type="checkbox" id="gender-man" value="man"
                                <?php if (in_array('man', $atlas_institute[ 'gender' ])) {echo 'checked';}?>>آقا</label>
                    </fieldset>
                </td>
            </tr>
            <tr class="center-type">
                <th>گروه سنی</th>
                <td>
                    <fieldset>
                        <label for="age-7">
                            <input name="atlas[age][]" type="checkbox" id="age-7" value="7"
                                <?php if (in_array('7', $atlas_institute[ 'age' ])) {echo 'checked';}?>>زیر 7
                            سال</label>
                        <label for="age-12">
                            <input name="atlas[age][]" type="checkbox" id="age-12" value="12"
                                <?php if (in_array('12', $atlas_institute[ 'age' ])) {echo 'checked';}?>>7 تا 12
                            سال</label>
                        <label for="age-18">
                            <input name="atlas[age][]" type="checkbox" id="age-18" value="18"
                                <?php if (in_array('18', $atlas_institute[ 'age' ])) {echo 'checked';}?>>12 تا 18
                            سال</label>
                        <label for="age-old">
                            <input name="atlas[age][]" type="checkbox" id="age-old" value="old"
                                <?php if (in_array('old', $atlas_institute[ 'age' ])) {echo 'checked';}?>>18 سال به
                            بالا</label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th>تعداد مخاطبین </th>
                <td><input class="regular-text" name="atlas[contacts]" value="<?=$atlas_institute[ 'contacts' ]?>"></td>
            </tr>
            <tr class="center-type">
                <th>قالب برگزیده دوره ها </th>
                <td>
                    <fieldset>
                        <label for="course-type-online">
                            <input name="atlas[course-type][]" type="checkbox" id="course-type-online" value="online"
                                <?php if (in_array('online', $atlas_institute[ 'course-type' ])) {echo 'checked';}?>>حضوری</label>
                        <label for="course-type-offline">
                            <input name="atlas[course-type][]" type="checkbox" id="age-offline" value="offline"
                                <?php if (in_array('offline', $atlas_institute[ 'course-type' ])) {echo 'checked';}?>>مجازی</label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th>محتوا و موضوع </th>
                <td>
                    <input class="regular-text" name="atlas[subject]"
                        value="<?=$atlas_institute[ 'subject' ]?>"><br><br>
                    <input class="regular-text" name="atlas[subject]" id="subject" value="ایران,کانادا,استرالیا,مکزیک" />

                </td>
            </tr>
            <tr>
                <th>تعداد مربیان </th>
                <td><input class="regular-text" name="atlas[coaches]" value="<?=$atlas_institute[ 'coaches' ]?>"></td>
            </tr>

            <tr>
                <th>مرکز در سایت زندگی با آیه ها نمایش </th>
                <td><input class="regular-text" name="atlas[ayeha]" value="<?=$atlas_institute[ 'ayeha' ]?>"></td>
            </tr>


            <tr>
                <th>توضیحات بیشتر</th>
                <td>
                    <?php
wp_editor($post->post_content, 'content', [
    'textarea_name' => 'post_content',
    'media_buttons' => true,
    'textarea_rows' => 10,
 ]);
?>
                </td>
            </tr>
        </table>
    </div>

</div>