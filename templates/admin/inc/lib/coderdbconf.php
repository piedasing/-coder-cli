<?php

class coderDBConf
{
    public static $admin = 'admin'; // 後台管理者

    public static $admin_log = 'admin_log'; // 後台管理者操作紀錄

    public static $rules = 'rules'; // 角色
    public static $col_rules = ['id' => 'r_id', 'name' => 'r_name', 'depiction' => 'r_description', 'superadmin' => 'r_superadmin', 'system' => 'r_system', 'admin' => 'r_admin', 'updatetime' => 'r_updatetime', 'createtime' => 'r_createtime'];

    public static $rules_auth = 'rules_auth'; // 角色權限
    public static $col_rules_auth = ['id' => 'ra_id', 'r_id' => 'r_id', 'main_key' => 'ra_main_key', 'fun_key' => 'ra_fun_key', 'auth' => 'ra_auth', 'admin' => 'ra_admin', 'updatetime' => 'ra_updatetime', 'createtime' => 'ra_createtime'];

    /*********** 新增 ***********/
    public static $email_log = 'email_log';

    public static $form = 'form'; // 表單
    public static $form_comment = 'form_comment'; // 表單狀況說明
    public static $member = 'member'; // 會員
    public static $building_project = 'building_project'; // 建案專案管理
    public static $fb_log = 'fb_log'; // fblog管理

}
