<?php

(defined('ABSPATH')) || exit;
define('ATLAS_VERSION', '1.0.5');

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
    require_once ATLAS_INCLUDES . '/edit_user_table.php';

}

//  exit;
