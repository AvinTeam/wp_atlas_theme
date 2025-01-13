

<?php

function atlas_title_filter_dashboard($title)
{

    $title = get_bloginfo('name') . " | پنل کاربری";
    return $title;
}
add_filter('wp_title', 'atlas_title_filter_dashboard');

get_header(); ?>

<div class="container-fluid w-100 text-center">
  <div class="row align-items-start">
    <div class="col">
    داشبورد
    </div>

  </div>
</div>
<?php get_footer(); ?>