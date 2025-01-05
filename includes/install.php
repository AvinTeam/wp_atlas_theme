<?php

(defined('ABSPATH')) || exit;
function atlas_row_install()
{

    if (get_role('operator') == null) {
        add_role(
            'operator',
            'اپراتور',
            [
                'read' => true,
                'edit_atlas' => true,
                'read_atlas' => true,
                'delete_atlas' => true,
                'edit_atlass' => true,
                'edit_others_atlass' => true,
                'delete_atlass' => true,
                'publish_atlass' => true,
                'edit_published_atlass' => true,
                'edit_private_atlass' => true,
                'delete_others_atlass' => true,
                'read_private_atlass' => true,
                'delete_published_atlass' => true,
                'delete_private_atlass' => true,
             ]
        );

    }

    $admin_role = get_role('administrator');

    if (!array_key_exists('operator', $admin_role->capabilities)) {
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



    $tabel_iran_area_row = $wpdb->prefix . 'atlas_iran_area';
    $wpdb_collate_atlas_row = $wpdb->collate;
    $sql_iran_area = "CREATE TABLE IF NOT EXISTS `$tabel_iran_area_row` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` varchar(19) NOT NULL,
        `province_id` int NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate_atlas_row";
    
    dbDelta($sql_iran_area);

    
      



    // global $wpdb;
    // $tabel_atlas_row = $wpdb->prefix . 'atlas_row';
    // $wpdb_collate_atlas_row = $wpdb->collate;
    // $sql = "CREATE TABLE IF NOT EXISTS `$tabel_atlas_row` (
    //         `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
    //         `full_name` varchar(50) CHARACTER SET utf8mb4 COLLATE $wpdb_collate_atlas_row NOT NULL DEFAULT '',
    //         `mobile` varchar(11) COLLATE $wpdb_collate_atlas_row NOT NULL,
    //         `avatar` varchar(20) CHARACTER SET utf8mb4 COLLATE $wpdb_collate_atlas_row NOT NULL DEFAULT 'no',
    //         `ostan` int NOT NULL DEFAULT '0',
    //         `description` text COLLATE $wpdb_collate_atlas_row,
    //         `signature` longtext CHARACTER SET utf8mb4 COLLATE $wpdb_collate_atlas_row,
    //         `status` varchar(20) COLLATE $wpdb_collate_atlas_row NOT NULL,
    //         `created_at` timestamp NOT NULL,
    //         PRIMARY KEY (`ID`),
    //         UNIQUE KEY `mobile` (`mobile`)
    //         ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=$wpdb_collate_atlas_row";


    // dbDelta($sql);

}

add_action('after_switch_theme', 'atlas_row_install');
