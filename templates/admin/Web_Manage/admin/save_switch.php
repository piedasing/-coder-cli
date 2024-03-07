<?php
include_once('_config.php');
$result = array();

$errorhandle=new coderErrorHandle();
try{
    $db = Database::DB();
    $id = post('id',1);
    $type = post('type',1);
    $val = post('val',1);
    $checked = post('checked');


    if($id!=""){
        $method='edit';
        $active='編輯';
    }

    $nowtime = datetime();
    switch ($type) {
        case 'ispublic':
            $data['ispublic']=($checked==1?'1':'0');
            break;
    }
    $data['admin']=$adminuser['username'];
    $data['updatetime']= $nowtime;

    if($method=='edit'){
        $db->query_update($table,$data," id='{$id}'");
    }


    coderAdminLog::insert($adminuser['username'], $main_auth_key, $fun_auth_key, $method, $page_title . " id:{$id}");
    $db->close();
}
catch(Exception $e){
    $errorhandle->setException($e); // 收集例外
}

if ($errorhandle->isException()) {
    $result['result']=false;
    $result['data']=$errorhandle->getErrorMessage();
}else{
    $result['result']=true;
    $result['data']='';
}
echo json_encode($result);
?>