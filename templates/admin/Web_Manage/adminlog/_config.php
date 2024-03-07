<?php
/*既定的參數配置 (S)*/
$inc_path = "../../inc/";
$manage_path = "../";
$main_auth_key = 'auth';
$fun_auth_key = 'adminlog';
include('../_config.php');

//上傳檔案的路徑
$file_path = $admin_path_admin;

//檢查權限
$auth = coderAdmin::Auth($fun_auth_key);

//其他的頁面資訊
$table = coderDBConf::$admin_log;
$page = request_pag("page");
$page_title = '歷程記錄';
$page_desc = "後台使用者操作記錄列表，此區不能進行新增/修改/刪除。";
$mtitle = '<li class="active">' . $auth['name'] . '</li>';
$mainicon = $auth['icon'];
/*既定的參數配置 (E)*/
?>