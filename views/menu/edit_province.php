<?php
$iran = new Iran_Area();

$province = $iran->get('id', absint($_GET[ 'province' ]));

$description = (isset($province->description)) ? $province->description : '';

$city_name = $city_description = '';
$city_btn = "افزودن شهر";
$city_id = 0;

if (isset($_GET[ 'city' ]) && !empty($_GET[ 'city' ])) {

    $city = $iran->get('id', absint($_GET[ 'city' ]));

    $city_id = absint($_GET[ 'city' ]);

    $city_name = $city->name;
    $city_description = $city->description;
    $city_btn = "بروزرسانی شهر";

    if (absint($_GET[ 'province' ]) != $city->province_id) {
        echo '<a href="' . admin_url('admin.php?page=province&province=' . absint($_GET[ 'province' ])) . '" style="width: 50%;text-align: center;margin: 50px auto;padding: 50px;"  class="button" >برگشت</a>';
        exit;
    }

}

?>

<div class="wrap nosubsub">
    <h1 class="wp-heading-inline">استان <?=$province->name?></h1>


    <hr class="wp-header-end">

    <?php if (empty($city_name)): ?>

    <form method="post" action="" novalidate="novalidate" class="ag_form">
        <?php wp_nonce_field('atlas_nonce' . get_current_user_id()); ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="sms_text_otp">درباره استان</label></th>
                    <td>
                        <?php $editor_array = [
    'media_buttons' => false,
    'textarea_name' => 'description',
    'tinymce' => [
        'wpautop' => true,
        'force_p_newlines' => true,
        'br_in_pre' => true,
        'valid_elements' => '*[*]',
        'extended_valid_elements' => 'p[*],br[*],span[*]',
        'remove_linebreaks' => false,

     ],
 ];

wp_editor($description, 'description', $editor_array)?>
                    </td>
                </tr>




            </tbody>
        </table>


        <p class="submit">
            <button type="submit" name="atlas_act" value="atlas__submit" id="submit"
                class="button button-primary">ذخیرهٔ
                تغییرات</button>
        </p>
    </form>

    <div class="clear"></div>

    <?php endif; ?>








    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h2>افزودن شهر</h2>
                    <form id="addtag" method="post" action="" class="validate">
                        <?php wp_nonce_field('atlas_nonce' . get_current_user_id()); ?>

                        <input name="city_id" type="hidden" value="<?=$city_id?>">

                        <div class="form-field form-required term-name-wrap">
                            <label for="city_name">نام شهر</label>
                            <input name="city_name" id="city_name" type="text" value="<?=$city_name?>" size="40"
                                aria-required="true">
                            <div class="form-field term-description-wrap">
                                <label for="city_description">توضیح</label>
                                <textarea name="description" id="city_description"
                                    rows="5"><?=$city_description?></textarea>
                            </div>

                            <p class="submit">
                                <button type="submit" name="atlas_act" id="submit" class="button button-primary"
                                    value="atlas_city_submit"><?=$city_btn?></button>
                                <?php if (!empty($city_name)): ?>

                                <a href="<?=admin_url('admin.php?page=province&province=' . absint($_GET[ 'province' ]))?>"
                                    class="button">برگشت</a>

                                <?php endif; ?>
                            </p>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /col-left -->

    <div id="col-right">
        <div class="col-wrap">
            <h2>فهرست شهر ها</h2>

            <table id="city_list" class="wp-list-table widefat striped">
                <thead>
                    <th>ردیف</th>
                    <th>نام شهر</th>
                    <th>تعداد موسسه ها</th>
                    <th></th>

                </thead>


                <tbody><?php $m = 1;foreach ($iran->select(absint($_GET[ 'province' ])) as $city):

    $args = [
        'post_type' => 'institute',
        'post_status' => [ 'publish', 'draft' ],
        'meta_query' => [
            [
                'key' => '_atlas_city',
                'value' => $city->id,
                'compare' => '=',
             ],
         ],
        'fields' => 'ids',
        'posts_per_page' => -1,
     ];

    $query = new WP_Query($args);
    $post_count = $query->found_posts;
    $active_class = '';
    if (isset($_GET[ 'city' ]) && !empty($_GET[ 'city' ]) && absint($_GET[ 'city' ]) == $city->id) {
        $active_class = ' style="background-color: #fffbc8 !important"';
    }?>
                    <tr <?=$active_class?>>
                        <td><?=$m?></td>
                        <td><a target="_blank" href="<?=atlas_pane_base_url('city=' . $city->id)?>"><?=$city->name?></a>
                        </td>
                        <td><a target="_blank"
                                href="<?=admin_url('edit.php?post_type=institute&city=' . $city->id)?>"><?=$post_count?></a>
                        </td>
                        <td>
                            <a href="<?=admin_url('admin.php?page=province&province=' . absint($_GET[ 'province' ]) . '&city=' . $city->id)?>"
                                class="button button-primary"> ویرایش </a>
                            <button class="button button-primary button-error delete-city-btn"
                                data-city-id="<?=$city->id?>"> حذف </button>
                        </td>
                    </tr>
                    <?php $m++;endforeach; ?>
                </tbody>


            </table>
        </div>
    </div><!-- /col-right -->

</div><!-- /col-container -->

</div><!-- /wrap -->


<div class="clear"></div>