<?php
include('_config.php');
include($inc_path . '_imgupload.php');

$_path = request_str('path');
$filepath = $_path == '' ? $filepath : $_path;

$arys = request_ary('arys');
$file = new imgUploder($_FILES['pic']);
$success = false;
$width = 0;
$height = 0;
if ($file->file_name != "") {
    $filename = explode('.', $file->file_name);
    $file->set("file_name", time() . generatorPassword() . '.' . end($filename));
    $file->set("file_max", 1024 * 1024 * 3);
    $file->set("file_dir", $filepath);
    $file->set("overwrite", "3");
    $file->set("fstyle", "image");
    if ($file->upload() && $file->file_name != "") {
        for ($i = 0; $i < count($arys); $i++) {
            $obj = explode(',', $arys[$i]);
            
            if (count($obj) == 4 || count($obj) == 5) {
                $obj[4] = isset($obj[4]) && $obj[4] && $obj[4] !== 'false' ? $obj[4] : false;
                $file->file_sname = $obj[0];
                $file->createSmailImg($obj[2], $obj[3], $obj[1], $obj[4]);
            }
        }
        $success = true;
        $size = $file->getSize();
        $width = $size[0];
        $height = $size[1];
    }
    $result['result'] = $success;
    $result['msg'] = $file->user_msg;
    $result['filename'] = $file->file_name;
    $result['filepath'] = $filepath;
    $result['width'] = $width;
    $result['height'] = $height;
    echo json_encode($result);
}
?>
