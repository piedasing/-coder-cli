<?php
/*圖*/
include('_config.php');
include($inc_path.'_imgupload.php');
$result = array();
$success=false;
$msg = '';

$sx = post('sx');
$sy = post('sy');
$sw = post('sw');
$sh = post('sh');
$tw = post('tw');
$th = post('th');
$name = post('name',1);
$tag = post('tag',1);
$croptag = post('croptag',1);
$orgfilepath = post('orgfilepath',1);

if (is_file($orgfilepath.$name)){
	$filename = explode('.',$name);	
    $file =new imgUploder(null);
	$file->set("fileex",end($filename));
    $file->set("file_dir",$orgfilepath);
    $file->set("file_name",$name);
    $file->set("file_x",$sx);
    $file->set("file_y",$sy);
    $file->set("file_sw",$sw);
    $file->set("file_sh",$sh);
    $file->set("file_sname",$croptag.$tag);
    $file->createSmailImg($tw,$th,10,$filepath,$name);

    $size=$file->getSize();
    $result['width']=$size[0];
    $result['height']=$size[1];
    $result['filepath_pics']=array($tag=>$filepath);
    $success = true;
    $msg = (string)$file->user_msg;
    
}else{
    $result['result']=false;
    $msg='切圖失敗，請重新上傳';
}

$result['filename']=$name;
$result['filepath']=$orgfilepath;
$result['result']=$success;
$result['msg']=$msg;

echo json_encode($result);
?>