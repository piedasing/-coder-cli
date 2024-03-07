<?php
$help = new coderFilterHelp();
$obj = array();

$obj[] = array('type' => 'keyword', 'name' => '關鍵字', 'sql' => true, 'ary' => array(
    array('column' => 'username', 'name' => '帳號'),
    array('column' => 'ip', 'name' => 'IP'),
));

$obj[] = array(
    'type' => 'select',
    'name' => '操作',
    'column' => 'action',
    'sql' => true,
    'ary' => coderHelp::parseAryKeys(coderAdminLog::$action, array('key' => 'value'))
);

$obj[] = array('type' => 'datearea', 'sql' => true, 'column' => 'createtime', 'name' => '操作時間');

$obj[] = array('type' => 'hidden', 'sql' => true, 'column' => 'username', 'name' => '');

$help->Bind($obj);
?>