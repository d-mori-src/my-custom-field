<?php
/*
Plugin Name: custom-field
Plugin URI: http://www.example.com/plugin
Description: This is Plugin Test.
Author: Name
Version: 1.0
Author URI: http://www.example.com
*/

if (! defined('ABSPATH')) {
    exit;
}

// 外部ファイル
require_once(plugin_dir_path(__FILE__) . 'lib/jquery.php');

(function () {
    $dir = plugin_dir_url(__FILE__);
    wp_enqueue_style('form_style', $dir . 'css/form.css');
    wp_enqueue_script('form_script', $dir . 'js/form.js');
})();

add_action('admin_menu', function () {
    add_meta_box('id', 'カスタムフィールド', 'insert_custom_fields', 'post', 'normal'); // postにすると通常投稿に追加
});

add_action('post_edit_form_tag', function () {
    echo ' enctype="multipart/form-data"';
});

// post投稿の入力エリア
function insert_custom_fields()
{
    global $post;

    echo '<ul class="formWrap">';

    if (current_user_can('administrator')) { // 管理者: administrator 編集者: editor
        // text
        echo '
        <li class="form">
            <div class="title">タイトル<span>必須</span></div>
            <input type="text" class="input" name="title" placeholder="タイトルを入力" value="'.get_post_meta($post->ID, 'title', true).'" />
        </li>';
    }

    // textarea
    echo '
    <li class="form">
        <div class="title">本文 <span>必須</span></div>
        <textarea name="lead" class="input">'.get_post_meta($post->ID, 'lead', true).'</textarea>
    </li>';

    // checkbox
    $is_check_a = in_array('check_a', (array)get_post_meta($post->ID, 'custom_checkbox_2', true)) ? 'checked' : '';
    $is_check_b = in_array('check_b', (array)get_post_meta($post->ID, 'custom_checkbox_2', true)) ? 'checked' : '';
    $is_check_c = in_array('check_c', (array)get_post_meta($post->ID, 'custom_checkbox_2', true)) ? 'checked' : '';
    echo '
    <li class="form">
        <div class="title">チェックボックス<span>必須</span></div>
        <ul class="box">
            <li>
                <input type="checkbox" name="custom_checkbox_2[]" id="check_a" value="check_a" '.$is_check_a.' />
                <label for="check_a" class="customStyle">チェックボックスA</label>
            </li>
            <li>
                <input type="checkbox" name="custom_checkbox_2[]" id="check_b" value="check_b" '.$is_check_b.' />
                <label for="check_b" class="customStyle">チェックボックスB</label>
            </li>
            <li>
                <input type="checkbox" name="custom_checkbox_2[]" id="check_c" value="check_c" '.$is_check_c.' />
                <label for="check_c" class="customStyle">チェックボックスC</label>
            </li>
        </ul>
    </li>';

    // radio
    $is_radio_a = get_post_meta($post->ID, 'custom_radio', true) == 'radio_a' ? 'checked' : '';
    $is_radio_b = get_post_meta($post->ID, 'custom_radio', true) == 'radio_b' ? 'checked' : '';
    $is_radio_c = get_post_meta($post->ID, 'custom_radio', true) == 'radio_c' ? 'checked' : '';
    echo '
    <li class="form">
        <div class="title">ラジオボタン<span>必須</span></div>
        <ul class="box">
            <li>
                <input type="radio" name="custom_radio" id="radio_a" value="radio_a" '.$is_radio_a.' />
                <label for="radio_a" class="customStyle">ラジオボタンA</label>
            </li>
            <li>
                <input type="radio" name="custom_radio" id="radio_b" value="radio_b" '.$is_radio_b.' />
                <label for="radio_b" class="customStyle">ラジオボタンB</label>
            </li>
            <li>
                <input type="radio" name="custom_radio" id="radio_c" value="radio_c" '.$is_radio_c.' />
                <label for="radio_c" class="customStyle">ラジオボタンC</label>
            </li>
        </ul>
    </li>';

    // selectbox
    $is_select_a = get_post_meta($post->ID, 'custom_select', true) == 'select_a' ? 'selected' : '';
    $is_select_b = get_post_meta($post->ID, 'custom_select', true) == 'select_b' ? 'selected' : '';
    $is_select_c = get_post_meta($post->ID, 'custom_select', true) == 'select_c' ? 'selected' : '';
    echo '
    <li class="form">
        <div class="title">セレクトボックス<span>必須</span></div>
        <div class="selectbox">
            <select name="custom_select" id="select">
                <option value="select_a" '.$is_select_a.'>セレクトA</option>
                <option value="select_b" '.$is_select_b.'>セレクトB</option>
                <option value="select_c" '.$is_select_c.'>セレクトC</option>
            </select>
        </div>
    </li>';

    // image
    $hoge_name = get_post_meta(
        $post->ID, //投稿ID
    'hoge_name', //キー名
    true //戻り値を文字列にする場合true(falseの場合は配列)
    );
    $hoge_thumbnail = get_post_meta($post->ID, 'hoge_thumbnail', true);
    echo '
    <li class="form">
        <div class="title">画像<span class="any">任意</span></div>
        <label for="sample" class="imageBox">
            ファイルアップロード
            <input type="hidden" name="hoge_name" value="'.$hoge_name.'" />
            <input type="file" name="hoge_thumbnail" accept="image/*" id="sample" />
        </label>
    </li>';
    if (isset($hoge_thumbnail) && strlen($hoge_thumbnail) > 0) {
        echo '
        <li class="form">
            <div class="title">アップロードされた画像</div>
            <div class="imageBox imageBox2">
                <img class="thumbnailImage" src="'.wp_get_attachment_url($hoge_thumbnail).'">
            </div>
        </li>';
    }

    echo '
    <li class="form">
    <div class="title">Datepicker<span>必須</span></div>
    <input type="text" class="input" id="datepicker" name="date" value="'.get_post_meta($post->ID, 'date', true).'">
    <script>(function($){ $("#datepicker").datepicker({ dateFormat: "yy年m月d日" }); })(jQuery);</script>
    </li>';

    echo '</ul>';
}

// カスタムフィールドの値を保存
add_action('save_post', function ($post_id) {
    if (!empty($_POST['title'])) {
        update_post_meta($post_id, 'title', $_POST['title']);
    }

    if (!empty($_POST['lead'])) {
        update_post_meta($post_id, 'lead', $_POST['lead']);
    }

    if (!empty($_POST['custom_checkbox'])) {
        update_post_meta($post_id, 'custom_checkbox', $_POST['custom_checkbox']);
    } else {
        delete_post_meta($post_id, 'custom_checkbox');
    }

    if (!empty($_POST['custom_checkbox_2'])) {
        update_post_meta($post_id, 'custom_checkbox_2', $_POST['custom_checkbox_2']);
    } else {
        delete_post_meta($post_id, 'custom_checkbox_2');
    }

    if (!empty($_POST['custom_radio'])) {
        update_post_meta($post_id, 'custom_radio', $_POST['custom_radio']);
    } else {
        delete_post_meta($post_id, 'custom_radio');
    }

    if (!empty($_POST['custom_select'])) {
        update_post_meta($post_id, 'custom_select', $_POST['custom_select']);
    } else {
        delete_post_meta($post_id, 'custom_select');
    }

    if (isset($_POST['hoge_name'])) {
        //hoge_nameキーで、$_POST['hoge_name']を保存
        update_post_meta($post_id, 'hoge_name', $_POST['hoge_name']);
    } else {
        //hoge_nameキーの情報を削除
        delete_post_meta($post_id, 'hoge_name');
    }
    if (isset($_FILES['hoge_thumbnail']) && $_FILES["hoge_thumbnail"]["size"] !== 0) {
        $file_name = basename($_FILES['hoge_thumbnail']['name']);
        $file_name = trim($file_name);
        $file_name = str_replace(" ", "-", $file_name);

        $wp_upload_dir = wp_upload_dir();
        $upload_file = $_FILES['hoge_thumbnail']['tmp_name'];
        $upload_path = $wp_upload_dir['path'].'/'.$file_name;
        move_uploaded_file($upload_file, $upload_path);

        $file_type = $_FILES['hoge_thumbnail']['type'];
        $slug_name = preg_replace('/\.[^.]+$/', '', basename($upload_path));

        if (file_exists($upload_path)) {
            $attachment = array(
                'guid'           => $wp_upload_dir['url'].'/'.basename($file_name),
                'post_mime_type' => $file_type,
                'post_title' => $slug_name,
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment($attachment, $upload_path, $post_id);
            if (!function_exists('wp_generate_attachment_metadata')) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            }
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload_path);
            wp_update_attachment_metadata($attach_id, $attach_data);
            update_post_meta($post_id, 'hoge_thumbnail', $attach_id);
        } else {
            echo '画像保存に失敗しました';
            exit;
        }
    }

    if (!empty($_POST['date'])) {
        update_post_meta($post_id, 'date', $_POST['date']);
    }
});
