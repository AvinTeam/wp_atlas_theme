<div class="d-flex flex-column justify-content-center align-items-start mx-auto atlas-row my-2">
    <h3 class="my-2">فهرست</h3>

    <?php
        $args = [
            'author'         => get_current_user_id(),
            'post_type'      => 'institute',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'fields'         => 'ids',
         ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $posts = [  ];

            while ($query->have_posts()) {
                $query->the_post();
                $status = (get_post_status() == 'publish') ? 'light' : 'warning';
                $img    = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : atlas_panel_image('default.png');
                echo '
                    <a href=" ' . atlas_base_url('panel/?post=' . get_the_ID()) . '" class="alert alert-light w-100 d-flex flex-row gap-3 justify-content-start align-items-center" role="alert">
                        <img src="' . $img . '" class="img-fluid rounded-circle mb-2" style=" height: 50px; border: 1px solid #3899a0;">
                        <span class="btn">' . get_the_title() . '</span>
                    </a>';
            }
            wp_reset_postdata();
        }
    ?>


</div>