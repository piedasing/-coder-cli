<?php
include('_config.php');
include_once('formconfig.php');
$errorhandle=new coderErrorHandle();
try{
	$id = post($colname['id']);
	if($id>0){
		$method='edit';
		$active='編輯';
	}else{
		$method='add';
		$active='新增';
	}

	$data=$fhelp->getSendData();
	$error=$fhelp->vaild($data);

	if(count($error)>0){
		$msg=implode('\r\n',$error);
		throw new Exception($msg);
	}


	$data[$colname['admin']]=$adminuser['username'];
	$data[$colname['updatetime']]=datetime();

    $db = Database::DB();
	if($method=='edit'){
		coderAdmin::vaild($auth,'edit');
		$db->query_update($table,$data," {$colname['id']}=:id ",array(':id'=>$id));
	}else{
		coderAdmin::vaild($auth,'add');
		$data[$colname['createtime']]=datetime();
		$id=$db->query_insert($table,$data);
	}

	//更新權限
	$ary_fun_auth=request_ary("fun_auth");
	
	coderAdmin::updateAuth($id,$ary_fun_auth);
	

	echo showParentSaveNote($auth['name'],$active,$data[$colname['name']],"manage.php?id=".$id);
	coderAdminLog::insert($adminuser['username'],$main_auth_key,$fun_auth_key,$method,"{$data[$colname['name']]} id:{$id}");

	$db->close();
}
catch(Exception $e){
	$errorhandle->setException($e); // 收集例外
}

if ($errorhandle->isException()) {
		$errorhandle->showError();
}
?>