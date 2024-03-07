<?php
$fhelp = new coderFormHelp();
$fobj = array();

$fobj[$colname['id']] = array('type' => 'hidden', 'name' => 'ID', 'column' => $colname['id'], 'sql' => false);

$fobj[$colname['name']] = array(
    'type' => 'text',
    'name' => '名稱',
    'column' => $colname['name'],
    'placeholder' => '請輸入權限名稱',
    'validate' => array('required' => 'yes')
);

$fobj[$colname['superadmin']] = array(
    'type' => 'checkbox',
    'name' => '超級管理員',
    'column' => $colname['superadmin'],
    'value' => '1',
    'default' => '0',
    'help' => '超級管理員具有最高權限，可以使用所有功能。'
);

$fobj[$colname['depiction']] = array(
    'type' => 'textarea',
    'name' => '敘述',
    'column' => $colname['depiction'],
    'placeholder' => '請輸入敘述'
);

$fhelp->Bind($fobj);
?>
