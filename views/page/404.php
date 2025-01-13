<?php

function atlas_title_filter_404($title)
{

    $title = get_bloginfo('name') . " | 404 ";
    return $title;
}
add_filter('wp_title', 'atlas_title_filter_404');

get_header(); ?>

<div class="container-fluid w-100 text-center">
  <div class="row align-items-start">
    <div class="col">
      404
    </div>

  </div>
</div>
<?php get_footer(); ?>