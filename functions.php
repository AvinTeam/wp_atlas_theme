<?php

(defined('ABSPATH')) || exit;
define('ATLAS_VERSION', '1.1.3');

define('ATLAS_PATH', get_template_directory() . "/");
define('ATLAS_INCLUDES', ATLAS_PATH . 'includes/');
define('ATLAS_CLASS', ATLAS_PATH . 'classes/');
define('ATLAS_CORE', ATLAS_PATH . 'core/');
define('ATLAS_FUNCTION', ATLAS_PATH . 'functions/');
define('ATLAS_VIEWS', ATLAS_PATH . 'views/');

define('ATLAS_URL', get_template_directory_uri() . "/");
define('ATLAS_PHP', ATLAS_URL . 'json/');
define('ATLAS_ASSETS', ATLAS_URL . 'assets/');
define('ATLAS_CSS', ATLAS_ASSETS . 'css/');
define('ATLAS_JS', ATLAS_ASSETS . 'js/');
define('ATLAS_IMAGE', ATLAS_ASSETS . 'image/');

require_once ATLAS_CORE . '/accesses.php';

require_once ATLAS_INCLUDES . '/func.php';

require_once ATLAS_INCLUDES . '/postype.php';
require_once ATLAS_INCLUDES . '/meta_boxs.php';
require_once ATLAS_INCLUDES . '/styles.php';

require_once ATLAS_CLASS . '/Iran_Area.php';

require_once ATLAS_INCLUDES . '/init.php';

require_once ATLAS_INCLUDES . '/ajax.php';

// require_once ATLAS_INCLUDES . '/theme-function.php';
// require_once ATLAS_INCLUDES . '/jdf.php';

// require_once ATLAS_CLASS . '/Nasr.php';

//$atlas_option = atlas_start_working();

if (is_admin()) {
    // require_once ATLAS_CLASS . '/List_Table.php';

    // require_once ATLAS_INCLUDES . '/menu.php';
    require_once ATLAS_INCLUDES . '/install.php';
    require_once ATLAS_INCLUDES . '/edit_column_institute.php';
    require_once ATLAS_INCLUDES . '/edit_user_table.php';

}

if (isset($_GET[ 'mytest' ])) {

    $query_args = [
        'post_type' => 'institute',
        'posts_per_page' => -1,
     ];
    $query = new WP_Query($query_args);

    while ($query->have_posts()) {

        $query->the_post();

        $atlas_institute_get = get_post_meta(get_the_ID(), '_atlas_institute', true);
        if (is_array($atlas_institute_get)) {

            $atlas_institute = [

                'responsible' => (isset($atlas_institute_get[ 'responsible' ]) && $atlas_institute_get[ 'responsible' ]) ? $atlas_institute_get[ 'responsible' ] : '',
                'responsible-mobile' => (isset($atlas_institute_get[ 'responsible-mobile' ]) && $atlas_institute_get[ 'responsible-mobile' ]) ? $atlas_institute_get[ 'responsible-mobile' ] : '',
                'center-mode' => (isset($atlas_institute_get[ 'center-mode' ]) && $atlas_institute_get[ 'center-mode' ]) ? $atlas_institute_get[ 'center-mode' ] : 'public',
                'center-type' => (isset($atlas_institute_get[ 'center-type' ]) && $atlas_institute_get[ 'center-type' ]) ? $atlas_institute_get[ 'center-type' ] : 'Institute',
                'phone' => (isset($atlas_institute_get[ 'phone' ]) && $atlas_institute_get[ 'phone' ]) ? $atlas_institute_get[ 'phone' ] : '',
                'ostan' => (isset($atlas_institute_get[ 'ostan' ]) && $atlas_institute_get[ 'ostan' ]) ? $atlas_institute_get[ 'ostan' ] : 0,
                'city' => (isset($atlas_institute_get[ 'city' ]) && $atlas_institute_get[ 'city' ]) ? $atlas_institute_get[ 'city' ] : 0,
                'map' => (isset($atlas_institute_get[ 'map' ]) && is_array($atlas_institute_get[ 'map' ])) ? $atlas_institute_get[ 'map' ] : [ 'lat' => '', 'lng' => '' ],
                'address' => (isset($atlas_institute_get[ 'address' ]) && $atlas_institute_get[ 'address' ]) ? $atlas_institute_get[ 'address' ] : '',
                'link' => (isset($atlas_institute_get[ 'link' ]) && is_array($atlas_institute_get[ 'link' ])) ? $atlas_institute_get[ 'link' ] : [ 'site' => '', 'eitaa' => '', 'bale' => '', 'rubika' => '', 'telegram' => '', 'instagram' => '' ],
                'gender' => (isset($atlas_institute_get[ 'gender' ]) && is_array($atlas_institute_get[ 'gender' ])) ? $atlas_institute_get[ 'gender' ] : [  ],
                'age' => (isset($atlas_institute_get[ 'age' ]) && is_array($atlas_institute_get[ 'age' ])) ? $atlas_institute_get[ 'age' ] : [  ],
                'contacts' => (isset($atlas_institute_get[ 'contacts' ]) && $atlas_institute_get[ 'contacts' ]) ? $atlas_institute_get[ 'contacts' ] : '',
                'course-type' => (isset($atlas_institute_get[ 'course-type' ]) && is_array($atlas_institute_get[ 'course-type' ])) ? $atlas_institute_get[ 'course-type' ] : [  ],
                'subject' => (isset($atlas_institute_get[ 'subject' ]) && $atlas_institute_get[ 'subject' ]) ? $atlas_institute_get[ 'subject' ] : '',
                'coaches' => (isset($atlas_institute_get[ 'coaches' ]) && $atlas_institute_get[ 'coaches' ]) ? $atlas_institute_get[ 'coaches' ] : '',
                'teacher' => (isset($atlas_institute_get[ 'teacher' ]) && is_array($atlas_institute_get[ 'teacher' ])) ? $atlas_institute_get[ 'teacher' ] : [  ],
                'ayeha' => (isset($atlas_institute_get[ 'ayeha' ]) && $atlas_institute_get[ 'ayeha' ]) ? $atlas_institute_get[ 'ayeha' ] : 'no',
             ];

            update_post_meta(get_the_ID(), '_atlas_responsible', $atlas_institute[ 'responsible' ]);
            update_post_meta(get_the_ID(), '_atlas_responsible-mobile', $atlas_institute[ 'responsible-mobile' ]);
            update_post_meta(get_the_ID(), '_atlas_center-mode', $atlas_institute[ 'center-mode' ]);
            update_post_meta(get_the_ID(), '_atlas_center-type', $atlas_institute[ 'center-type' ]);
            update_post_meta(get_the_ID(), '_atlas_phone', $atlas_institute[ 'phone' ]);
            update_post_meta(get_the_ID(), '_atlas_ostan', $atlas_institute[ 'ostan' ]);
            update_post_meta(get_the_ID(), '_atlas_city', $atlas_institute[ 'city' ]);
            update_post_meta(get_the_ID(), '_atlas_map', $atlas_institute[ 'map' ]);
            update_post_meta(get_the_ID(), '_atlas_address', $atlas_institute[ 'address' ]);
            update_post_meta(get_the_ID(), '_atlas_link', $atlas_institute[ 'link' ]);
            update_post_meta(get_the_ID(), '_atlas_gender', $atlas_institute[ 'gender' ]);
            update_post_meta(get_the_ID(), '_atlas_age', $atlas_institute[ 'age' ]);
            update_post_meta(get_the_ID(), '_atlas_contacts', $atlas_institute[ 'contacts' ]);
            update_post_meta(get_the_ID(), '_atlas_course-type', $atlas_institute[ 'course-type' ]);
            update_post_meta(get_the_ID(), '_atlas_subject', $atlas_institute[ 'subject' ]);
            update_post_meta(get_the_ID(), '_atlas_coaches', $atlas_institute[ 'coaches' ]);
            update_post_meta(get_the_ID(), '_atlas_teacher', $atlas_institute[ 'teacher' ]);
            update_post_meta(get_the_ID(), '_atlas_ayeha', $atlas_institute[ 'ayeha' ]);

        }

    }
    exit;
}

//  exit;
