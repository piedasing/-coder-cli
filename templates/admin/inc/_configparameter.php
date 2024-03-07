<?php
/* Upload path */
$path_temp = "upload/temp/";
$admin_path_temp = "../../upload/temp/";

// admin
$path_admin = "upload/admin/";
$admin_path_admin = "../../upload/admin/";

// SEO
$path_seo = 'upload/s/';
$admin_path_seo = '../../upload/s/';

// 網站頁面
$path_webmenu = 'upload/webmenu/';
$admin_path_webmenu = '../../upload/webmenu/';

/* Cache path */
$cache_path = 'upload/cache/';
$cache_path_web = $cache_path;
$cache_path_mob = '../' . $cache_path;
$cache_path_do = '../' . $cache_path;
$cache_path_api = '../' . $cache_path;
$cache_path_admin = '../../' . $cache_path;

// ckeditor
$path_ckeditor = $weburl . 'upload/editor/'; // ckeditor中路徑
$admin_path_ckeditor = "../../upload/editor/"; // 上傳放置(以後台位置來看)
$db_path_ckeditor = 'upload/editor/'; // 存入資料庫時改為

// 後台引入資源路徑
$manage_source_path = '_manage/';
$root_manage_path = $web_root . $manage_source_path;
$inc_manage_path = ROOT_DIR . $manage_source_path;

/* ==================== 資料用ARY ==================== */
$incary_captcha_types = [
    'login' => 'VaildImgCode'
];

$incary_YNE = [0 => 'No', 1 => 'Yes'];
$incary_isshow = [1 => '顯示', 0 => '隱藏'];
$incary_sex = [1 => '男', 2 => '女'];
$incary_sex_layout = [1 => '<span class="label label-primary">男</span>', 2 => '<span class="label label-pink">女</span>'];
$incary_yn = [0 => '否', 1 => '是'];
$incary_yn_layout = ['<span class="label">否</span>', '<span class="label label-success">是</span>'];
$incary_yn_layout2 = ['<span class="label">不顯示</span>', '<span class="label label-success">顯示</span>'];

$incary_labelstyle = [0 => 'default', 1 => 'success', 2 => 'warning', 3 => 'important', 4 => 'inverse', 5 => 'pink', 6 => 'yellow', 7 => 'lime', 8 => 'magenta', 9 => 'gray'];
$incary_labelstyle2 = [0 => 'default', 1 => 'yellow', 2 => 'important'];
$incary_labelstyle3 = [0 => 'primary', 1 => 'important', 2 => 'warning'];

$incary_labelstyle_web_source = [1 => 'success', 2 => 'warning' , 3=> 'important'];
$incary_labelstyle_web_media = [1 => 'warning', 2 => 'gray', 3 => 'success', 4 => 'yellow', 5 => 'lime', 6 => 'pink', 7 => 'info', 8 => 'magenta', 998 => 'gray', 999 => 'gray'];
$incary_labelstyle_client_status = [0 => 'gray', 1 => 'warning', 2 => 'magenta', 3 => 'success', 4 => 'yellow', 5 => 'lime', '-1' => 'pink'];
$incary_labelstyle_building_status = [0 => 'default', 1 => 'success', 2 => 'important'];

/* ==================== custom arys ==================== */
