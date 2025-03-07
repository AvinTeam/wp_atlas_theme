<?php

(defined('ABSPATH')) || exit;

add_theme_support('post-thumbnails');
add_theme_support('menus');



function custom_theme_setup()
{

    
    register_nav_menus([
        'footer-menu' => 'فهرست فوتر',
     ]);
}
add_action('after_setup_theme', 'custom_theme_setup');


class Footer_Menu_Walker extends Walker_Nav_Menu
{
    private $count = 0; // شمارشگر آیتم‌ها
    private $total = 0; // تعداد کل آیتم‌ها

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        return; // حذف <ul>
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        return; // حذف </ul>
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $this->count++; // افزایش شمارشگر آیتم‌های پردازش‌شده
        $output .= '<a href="' . esc_url($item->url) . '" class="p-2 text-white">' . esc_html($item->title) . '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if ($this->count < $this->total) { // فقط اگر این آیتم، آخرین نباشه "| " اضافه کن
             $output .= '<span class="p-2 text-white">|</span>';
        }
    }

    public function walk($elements, $max_depth, ...$args)
    {
        $this->total = count($elements); // ذخیره تعداد کل آیتم‌های منو
        return parent::walk($elements, $max_depth, ...$args);
    }
}
