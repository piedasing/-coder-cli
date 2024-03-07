<?php
$help = new coderFilterHelp();
$obj = array();

$obj[] = array('type' => 'keyword', 'name' => '關鍵字', 'sql' => true, 'ary' => array(
    array('column' => 'name', 'name' => '名字'),
    array('column' => 'username', 'name' => '帳號'),
    array('column' => 'email', 'name' => '信箱'),
    array('column' => 'admin', 'name' => '管理員'),
));

$obj[] = array('type' => 'select', 'name' => '啟用', 'column' => 'ispublic', 'sql' => true,
    'ary' => array(
        array('name' => '是', 'value' => '1'),
        array('name' => '否', 'value' => '0')
    )
);

$obj[] = array(
    'type' => 'select',
    'name' => '權限',
    'column' => 'r_id',
    'table' => 'a',
    'sql' => true,
    'ary' => $rules_array,
    'default' => $r_name
);

$obj[] = array('type' => 'dategroup', 'sql' => true, 'column' => 'dategroup', 'ary' => array(
    array('name' => '建立時間', 'column' => 'createtime'),
    array('name' => '最後更新時間', 'column' => 'updatetime')
));

$help->Bind($obj);
?>
