<?php

/**
 * Reference:
 * https://phppot.com/php/php-image-upload-using-tinymce-editor/
 * /Web_Manage/comm/ckeditorupload.php
 */
include('_config.php');
include($inc_path . '_imgupload.php');

reset($_FILES);
$temp = current($_FILES);

$url = '';
$filepath = $admin_path_ckeditor;
$file = new imgUploder($temp);

if ($file->file_name != "") {
    $filename = explode('.', $file->file_name);
    $file->set("file_name", time() . '.' . end($filename));
    $file->set("file_max", 1024 * 1024 * 3);
    $file->set("file_dir", $filepath);
    $file->set("overwrite", "3");
    $file->set("fstyle", "image");
    if ($file->upload() && $file->file_name != "") {
        $url = $path_ckeditor . $file->file_name;
        $msg = '圖片上傳完成';
    } else {
        $msg = '圖片上傳失敗!' . $file->user_msg;
    }
} else {
    $msg = '請選擇上傳檔案。';
}

echo json_encode([
    'file_path' => $url,
]);
