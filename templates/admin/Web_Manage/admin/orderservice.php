<?php
include('_config.php');
$success=false;
$count=0;
$msg="未知錯誤,請聯絡系統管理員";
$id=post('id');
$method=post('method',1);
coderAdmin::vaild($auth,'view');
if($id>0){
    $db = Database::DB();
	try{
		if($orderDesc=="desc"){
			coderListOrderHelp::changeDescOrder($method,$table,$orderColumn,$id,'');
		}
		else{
			coderListOrderHelp::changeAscOrder($method,$table,$orderColumn,$id,'');
		}
		$success=true;
	}
	catch(Execption $e){
		$msg=$e->getMessage();
	}
}
else{
	$msg="未設定排序資料";
}

$result['result']=$success;
$result['count']=$count;
$result['msg']=$msg;
echo json_encode($result);

?>