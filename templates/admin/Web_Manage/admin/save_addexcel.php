<?php
include('_config.php');
include('formconfig.php');

coderAdmin::vaild($auth,'import');
//引入函式庫
include '../../Classes/PHPExcel.php';

$errorhandle=new coderErrorHandle();
try{

	$db = Database::DB();
	$method='add';
	$active='編輯';

	$data=$fhelp_excel->getSendData();
	$error=$fhelp_excel->vaild($data);

	if(count($error)>0){
		$msg=implode('<br/>',$error);
		throw new Exception($msg);
	}

	//設定要被讀取的檔案
	$file = $file_path_temp.post("file",1);
	try {
		$objPHPExcel = PHPExcel_IOFactory::load($file);
	} catch(Exception $e) {
		die('Error loading file "'.pathinfo($file,PATHINFO_BASENAME).'": '.$e->getMessage());
	}

	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$sheetData = uniqueAssocArray($sheetData, "B");
	$rowindex=0;

	foreach($sheetData as $key => $col){ //列
		$rowindex++;
		if($rowindex<=1)continue;
		$data = array();
		$data_groupmap = array();
		if($col['A'] != "" && $col['B'] != "" && $col['C'] != "" && $col['D'] != "" && $col['E'] != "" && $col['H'] != ""){
			
			if(isUsernameNotExisit($col['B']))
			{			
				$data['ispublic'] = $col['A'];
				$data['username'] = $col['B'];
				$data['password'] = coderAdmin::pwHash($col['C']);
				$data['name'] = $col['D'];
				$data['email'] = $col['E'];	
				$data['email_backup'] = $col['F'];						
				$data['o_id'] = ($col['G'] != "")?$col['G']:'null';
				$data['r_id'] = $col['H'];
				$data['info'] = ($col['I'])?$col['I']:"";
				$data['updatetime'] = datetime();
				$data['createtime'] = datetime();
				$data['forgetcode'] = 'null';
				$data['mid'] = getmid();
				
				$id = $db->query_insert($table,$data);
				
			}
			
		}
	}

	echo showParentSaveNote($auth['name'],$active,'','');
	coderAdminLog::insert($adminuser['username'],$main_auth_key,$fun_auth_key,$method,$page_title);
	$db->close();
}
catch(Exception $e){
	$errorhandle->setException($e); // 收集例外
}

if ($errorhandle->isException()) {
		$errorhandle->showError();
}


?>