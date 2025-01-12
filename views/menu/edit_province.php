<?php
$iran = new Iran_Area();

$province = $iran->get('id', absint($_GET[ 'province' ]));

$description = (isset($province->description)) ? $province->description : '';
?>

<div class="wrap nosubsub">
    <h1 class="wp-heading-inline">استان <?=$province->name?></h1>


    <hr class="wp-header-end">


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










    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">


                <div class="form-wrap">
                    <h2>افزودن شهر</h2>
                    <form id="addtag" method="post" action="" class="validate">
                        <div class="form-field form-required term-name-wrap">
                            <label for="tag-name">نام شهر</label>
                            <input name="tag-name" id="tag-name" type="text" value="" size="40" aria-required="true"
                                aria-describedby="name-description">
                        </div>
                        <div class="form-field term-description-wrap">
                            <label for="tag-description">توضیح</label>
                            <textarea name="description" id="tag-description" rows="5" cols="40"
                                aria-describedby="description-description"></textarea>
                            <p id="description-description">توضیح به طور پیش‌فرض پررنگ نیست؛ با این حال، برخی از
                                پوسته‌ها ممکن است آن را نمایش دهند.</p>
                        </div>

                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                value="افزودن دسته تازه"> <span class="spinner"></span>
                        </p>
                    </form>
                </div>
            </div>
        </div><!-- /col-left -->

        <div id="col-right">
            <div class="col-wrap">
                <h2>فهرست شهر ها</h2>

                <table class="wp-list-table widefat striped">
                    <thead>
                        <th>ردیف</th>
                        <th>نام شهر</th>
                        <th>تعداد موسسه ها</th>
                        <th>وضعیت</th>

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

    ?>


                        <tr>
                            <td><?=$m?></td>
                            <td> <a href="<?=atlas_pane_base_url('city=' . $city->id)?>"><?=$city->name?></a></td>
                            <td><a
                                    href="<?=admin_url('edit.php?post_type=institute&city=' . $city->id)?>"><?=$post_count?></a>
                            </td>
                            <td><button class="button button-primary"> ویرایش </button> <button
                                    class="button button-primary button-error"> حذف </button> </td>
                        </tr>
                        <?php $m++;endforeach; ?>
                    </tbody>


                </table>
            </div>
        </div><!-- /col-right -->

    </div><!-- /col-container -->

</div><!-- /wrap -->


<div class="clear"></div>