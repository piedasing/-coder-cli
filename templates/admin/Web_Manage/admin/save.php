<?php
include('_config.php');
include_once('formconfig.php');
$errorhandle=new coderErrorHandle();
try{
	$update_auth=false;
	if(post('id')>0){
		$method='edit';
		$active='編輯';
	}else{
		$method='add';
		$active='新增';
		$fhelp->setAttr('password','validate',array('required'=>'yes','maxlength'=>'20','minlength'=>6));
	}

    if(!coderAdmin::isAuth($auth,coderAdminLog::getActionKey('edit'))) {
        $fhelp->setAttr('r_id','validate',false);
    }

	$data=$fhelp->getSendData();
	$error=$fhelp->vaild($data);

	if(count($error)>0){
		$msg=implode('\r\n',$error);
		throw new Exception($msg);
	}


    if(!coderAdmin::isAuth($auth,coderAdminLog::getActionKey('edit'))){
        unset($data['ispublic']);
        unset($data['r_id']);
    }

	$data['admin']=$adminuser['username'];
	$data['updatetime']=datetime();
	coderFormHelp::moveCopyPic($data['pic'], $admin_path_temp, $file_path, 's');
	
	//$data['system_notice'] = implode(',',request_ary("system_notice"));
	$data['mail_notice'] = implode(',',request_ary("mail_notice"));


    $db = Database::DB();
	if($method=='edit'){
		$s_username = $adminuser['username'];
		//非修改自己的資料需要驗證權限
		if($s_username!=$data['username']){
			coderAdmin::vaild($auth,'edit');
		}

		unset($data['username']);
		if($data['password']==''){
			unset($data['password']);
		}else{
			$data['password']=coderAdmin::pwHash($data['password']);
		}

		$username=post('username',1);

		$id=$db->query_update($table,$data," username=:username ",array(':username'=>$username));
		if($s_username===$username){coderAdmin::change_admin_data($username);}
	}else{
		coderAdmin::vaild($auth,'add');
		$username=$data['username'];
		$data['mid'] = getmid($table);
		$data['password']=coderAdmin::pwHash($data['password']);
		if($db->isExisit($table,'username',$username)){
			throw new Exception('帳號'.$username.'重覆,請重新輸入一組帳號');
		}
		$id=$db->query_insert($table,$data);
	}

	echo showParentSaveNote($auth['name'],$active,$username,"manage.php?username=".$username);
	coderAdminLog::insert($adminuser['username'],$main_auth_key,$fun_auth_key,$method,$username);
	$db->close();

}catch(Exception $e){
	$errorhandle->setException($e); // 收集例外
}

if ($errorhandle->isException()) {
	$errorhandle->showError();
}
?>
