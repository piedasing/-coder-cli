<?php
include('_config.php');
include($inc_path.'_imgupload.php');
$file =new imgUploder($_FILES['file']);
$success=false;

if ($file->file_name != "")
{
    $filename = explode('.',$file->file_name);
    $file->set("file_name",time().'.'.end($filename));
	$file->set("file_max",1024*1024*30);
	$file->set("file_dir",$filepath);
	$file->set("overwrite","3");
	$file->set("fstyle","video");
	if ($file->upload() && $file->file_name!=""){
		$success=true;
	}
$result['result']=$success;
$result['msg']=$file->user_msg;
$result['filename']=$file->file_name;
$result['filepath']=$filepath;
$result['size']=$file->file_size;

echo json_encode($result);
}
?>