<?php
include $inc_path."_config.php";
include $inc_path."_web_func.php";
$login_admin_ip = request_ip();
/* if(!in_array($login_admin_ip,  $incary_admin_ip)){
  echo "<script type='text/javascript'>window.open('{$base_link}','_top');</script>";
  exit;  
} */
$weburl = $weburl;
$cache_path=$cache_path_admin;

$isInIframe = false;
if (isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe') {
    $isInIframe = true;
}

include($inc_path.'_cache.php');
//$webmanagename="後台管理系統-Neptunus V1.0";

//取得登入USER順便檢查是否登入
$db = Database::DB();
$adminuser=coderAdmin::getUser();

$row_admin = class_admin::getByUsername($adminuser['username']);

$isAdmin = $row_admin['r_id'] == 1 ? true : false;
$isSalePM = in_array($row_admin['r_id'], [2, 6]) ? true : false; // 現場專案 / 專案助理
$isSale = $row_admin['r_id'] == 3 ? true : false; // 銷售人員
$isManager = $row_admin['r_id'] == 4 ? true : false; // 主委
$isEditer = $row_admin['r_id'] == 5 ? true : false; // 管理員
$canEditManager = $isAdmin || $isSalePM ? true : false; // 可以編輯名單的負責人

//coderAdmin::loginout_time();
coderAdmin::init(); //left
function showParentSaveNote($authname,$active,$title,$link="",$bodytxt=""){
	if($bodytxt==''){
		$bodytxt=$title.'己'.$active.'完成。';
	}
	$str= '<script>parent.closeBox();parent.showNotice("ok","'.$authname.$active.'完成。",\''.$bodytxt;
	if($link!=""){
		$str.='<br><a href="#" onclick="openBox(\\\''.$link.'\\\')"><i class="icon-check"></i>您可以按這裡檢視'.$active.'資料</a>';
	}
	$str.='<br>\');</script>';
	return $str;
}

function showCompleteIcon(){
	$numargs  = func_num_args();
	if($numargs<1){
		return '';
	}
	$arg_array  = func_get_args();
	$has_value=0;
	for ($i=0; $i<$numargs; $i++)
	{
		if(isset($arg_array[$i]) && trim($arg_array[$i])!=''){
			$has_value++;
		}
	}
	return $numargs==$has_value ? '' : ' <i class="red icon-exclamation-sign" title="該語系資料輸入不完全"></i> ';
}

function getIconClass($type){
	switch($type){
		case 'add':
			return 'icon-plus-sign-alt';
		break;
		case 'edit':
			return 'icon-edit-sign';
		break;
		case 'pic':
			return 'icon-picture';
		break;
		case 'q':
			return 'icon-question-sign';
		break;
		default :
			return 'icon-info-sign';
		break;
	}
}
?>
