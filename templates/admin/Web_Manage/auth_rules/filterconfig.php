<?php
//搜尋欄位
$help = new coderFilterHelp();
$obj = array();

$obj[] = array('type' => 'keyword', 'name' => '關鍵字', 'sql' => true, 'ary' => array(
    array('column' => $colname['name'], 'name' => '名稱'),
    array('column' => $colname['admin'], 'name' => '管理員'),
));

$obj[] = array('type' => 'select', 'name' => '超級管理員', 'column' => $colname['superadmin'], 'sql' => true, 'ary' => array(
    array('name' => '是', 'value' => '1'),
    array('name' => '否', 'value' => '0')
));

$obj[] = array('type' => 'dategroup', 'sql' => true, 'column' => 'dategroup', 'ary' => array(
    array('name' => '建立時間', 'column' => $colname['createtime']),
    array('name' => '最後更新時間', 'column' => $colname['updatetime'])
));

$help->Bind($obj);
?>