<?php

    function atlas_title_filter_dashboard($title)
    {

        $title = get_bloginfo('name') . " | پنل کاربری";
        return $title;
    }
    add_filter('wp_title', 'atlas_title_filter_dashboard');

    $this_user = wp_get_current_user();

    $args = [
        'post_type'      => 'institute',
        'post_status'    => [ 'pending', 'publish', 'draft' ],
        'author__not_in' => [ get_current_user_id() ],
        'meta_query'     => [
            [
                'key'     => '_atlas_responsible-mobile',
                'value'   => $this_user->mobile,
                'compare' => '=',
             ],
         ],
        'fields'         => 'ids',
        'posts_per_page' => -1,
     ];

    $query      = new WP_Query($args);
    $post_count = $query->found_posts;
    if ($post_count >= 1 && $query->have_posts()) {

        while ($query->have_posts()) {
            $query->the_post();

            $post_data = [
                'ID'          => get_the_ID(),
                'post_author' => get_current_user_id(),
             ];

            update_post_meta(get_the_ID(), '_atlas_responsible', $this_user->nickname);

            wp_update_post($post_data, true);
        }
    }
    wp_reset_postdata();

get_header(); ?>
<div class="container-fluid bg-white px-5 py-2">
    <div class="d-flex flex-row justify-content-between align-items-start mx-auto atlas-row my-2">
        <div class="d-flex flex-row justify-content-start align-items-center gap-2">
            <a class="btn btn-light" href="<?php echo atlas_base_url('panel') ?>">داشبورد</a>
            <span><?php echo $this_user->nickname ?> خوش آمدید!  ( <a class="btn btn-link p-0 m-0" href="<?php echo atlas_base_url('panel/?profile') ?>">ویرایش</a> )</span>
        </div>

        <div class="d-flex flex-row justify-content-end align-items-center gap-2">
            <a class="btn btn-light" href="<?php echo site_url()?>">بازگشت به صفحه نخست</a>
            <a class="btn btn-danger" href="<?php echo atlas_base_url('logout') ?>">خروج</a>
        </div>
    </div>
    <hr>

    <?php

        if (isset($_GET[ 'post' ]) && ! empty($_GET[ 'post' ])) {
            require_once ATLAS_VIEWS . '/page/panel_post.php';

        } elseif (isset($_GET[ 'profile' ])) {
            require_once ATLAS_VIEWS . '/page/panel_profile.php';

        } else {
            require_once ATLAS_VIEWS . '/page/panel_dashboard.php';

        }

    ?>


</div>




<?php get_footer(); ?>