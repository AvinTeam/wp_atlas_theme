<?php
(defined('ABSPATH')) || exit;
global $title;

if (isset($_GET[ 'province' ]) && !empty($_GET[ 'province' ])) {

    require_once ATLAS_VIEWS . 'menu/edit_province.php';

} else { ?>


<div id="wpbody-content">
    <div class="wrap">
        <h1><?php echo esc_html($title) ?></h1>
        <hr class="wp-header-end">
        <div class="province-list">
            <?php foreach ($iran->select() as $province): ?>
            <a href="<?=admin_url('admin.php?page=atlas&province=' . $province->id)?>"
                class="button province-item"><?=$province->name?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="clear"></div>
</div>

<?php } ?>