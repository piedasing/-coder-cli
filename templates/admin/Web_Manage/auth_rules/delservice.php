<?php
include('_config.php');
$errorhandle=new coderErrorHandle();
try{
	coderAdmin::vaild($auth,'del');
	$success=false;
	$count=0;
	$msg="未知錯誤,請聯絡系統管理員";

	$id=request_ary('id',0);

	if (count($id) > 0) {
        for($i = 0; $i < count($id); $i++) {
            if (in_array($id[$i], [1, 2, 3, 4, 5])) {
                throw new Exception('無法刪除此權限設定');
            }
        }

        $db = Database::DB();
		$idlist="'".implode("','",$id)."'";
		$count=$db->exec("delete from $table where {$colname['id']} in($idlist)");

		if($count>0){
			coderAdminLog::insert($adminuser['username'],$main_auth_key,$fun_auth_key,'del',$count.'筆資料('.$idlist.')');
			$success=true;
		}
		else{
			throw new Exception('查無刪除資料');
		}
		$db->close();
	}
	else{
		$msg="未選取刪除資料";
	}

	$result['result']=$success;
	$result['count']=$count;
	$result['msg']=hc($errorhandle->getErrorMessage());
	echo json_encode($result);
}
catch(Exception $e){
	$errorhandle->setException($e); // 收集例外
}

if ($errorhandle->isException()) {
	$result['result']=false;
    $result['msg']=$errorhandle->getErrorMessage();
	echo json_encode($result);
}

?>
