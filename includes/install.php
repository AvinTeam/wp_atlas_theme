<?php

(defined('ABSPATH')) || exit;
function atlas_row_install()
{

    remove_role('responsible');
    remove_role('operator');

    if (get_role('responsible') == null) {
        add_role(
            'responsible',
            'مسئول مرکز قرآنی',
            [
                'read'        => true,
                'edit_atlas'  => true,
                'read_atlas'  => true,
                'edit_atlass' => true,
                'upload_files' => true,

             ]
        );
    }

    if (get_role('operator') == null) {
        add_role(
            'operator',
            'اپراتور',
            [
                'read'                  => true,
                'edit_atlas'            => true,
                'read_atlas'            => true,
                'edit_atlass'           => true,
                'edit_others_atlass'    => true,
                'publish_atlass'        => true,
                'edit_published_atlass' => true,
                'edit_private_atlass'   => true,
                'read_private_atlass'   => true,
                'upload_files' => true,

             ]
        );

    }

    $admin_role = get_role('administrator');

    if (! array_key_exists('operator', $admin_role->capabilities)) {
        $admin_role->add_cap('operator');
        $admin_role->add_cap('edit_atlas');
        $admin_role->add_cap('read_atlas');
        $admin_role->add_cap('delete_atlas');
        $admin_role->add_cap('edit_atlass');
        $admin_role->add_cap('edit_others_atlass');
        $admin_role->add_cap('delete_atlass');
        $admin_role->add_cap('publish_atlass');
        $admin_role->add_cap('edit_published_atlass');
        $admin_role->add_cap('edit_private_atlass');
        $admin_role->add_cap('delete_others_atlass');
        $admin_role->add_cap('read_private_atlass');
        $admin_role->add_cap('delete_published_atlass');
        $admin_role->add_cap('delete_private_atlass');
    }

    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $tabel_iran_area_row    = $wpdb->prefix . 'atlas_iran_area';
    $wpdb_collate_atlas_row = $wpdb->collate;
    $sql_iran_area          = "CREATE TABLE IF NOT EXISTS `$tabel_iran_area_row` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `city2` varchar(100) NOT NULL,
        `province_id` int NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate_atlas_row";

    dbDelta($sql_iran_area);

}

add_action('after_switch_theme', 'atlas_row_install');
