<?php

// functions.phpでjQueryとjQueryUI読み込み
function my_admin_style()
{
    wp_enqueue_style('my_admin_style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css');
}
add_action('admin_enqueue_scripts', 'my_admin_style');
function custom_print_scripts()
{
    if (!is_admin()) {
        //デフォルトjquery削除
        wp_deregister_script('jquery');

        //GoogleCDNから読み込む
        wp_enqueue_script('jquery-js', '//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js');
    }
}
add_action('wp_print_scripts', 'custom_print_scripts');
