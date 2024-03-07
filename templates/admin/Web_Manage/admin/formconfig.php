<?php
$fhelp = new coderFormHelp();
$fobj = array();

$fobj['id'] = array('type' => 'hidden', 'name' => 'ID', 'column' => 'id', 'sql' => false);

$fobj['ispublic'] = array('type' => 'checkbox', 'name' => '啟用', 'column' => 'ispublic', 'value' => '1', 'default' => '1');

$fobj['username'] = array(
    'type' => 'text',
    'name' => '帳號',
    'column' => 'username',
    'autocomplete' => 'off',
    'placeholder' => '請輸入帳號',
    'help' => '此為登入系統之帳號，不能重覆。',
    'validate' => array('required' => 'yes', 'maxlength' => '20', 'minlength' => '3'),
    'icon' => '<i class="icon-user"></i>'
);

$fobj['password'] = array(
    'type' => 'password',
    'name' => '密碼',
    'column' => 'password',
    'autocomplete' => 'off',
    'placeholder' => '請輸入密碼',
    'help' => '此為登入系統之密碼。',
    'validate' => array('maxlength' => '30', 'minlength' => '6'),
    'icon' => '<i class="icon-key"></i>'
);

$fobj['repassword'] = array(
    'type' => 'password',
    'name' => '密碼確認',
    'column' => 'password',
    'autocomplete' => 'off',
    'placeholder' => '請再次輸入密碼',
    'help' => '為了確認密碼是否正確，請再輸入一次。',
    'sql' => false,
    'icon' => '<i class="icon-check-sign"></i>'
);

$fobj['name'] = array(
    'type' => 'text',
    'name' => '名字',
    'column' => 'name',
    'placeholder' => '請輸入名字',
    'validate' => array('required' => 'yes')
);

$fobj['email'] = array(
    'type' => 'text',
    'name' => '信箱',
    'column' => 'email',
    'placeholder' => '請輸入信箱',
    'validate' => array('required' => 'yes', 'email' => 'yes')
);

$fobj['email_backup'] = array(
    'type' => 'text',
    'name' => '備用信箱',
    'column' => 'email_backup',
    'placeholder' => '請輸入備用信箱',
    'validate' => array('email' => 'yes')
);

$fobj['r_id'] = array(
    'type' => 'select',
    'name' => '權限',
    'column' => 'r_id',
    'ary' => $rules_array,
    'validate' => array(
        'required' => 'yes'
    )
);

$fobj['pic'] = array('type' => 'pic', 'name' => '圖片', 'column' => 'pic');

$fobj['info'] = array('type' => 'textarea', 'name' => '個人資料', 'column' => 'info', 'placeholder' => '請輸入個人資料');

$fhelp->Bind($fobj);

//匯入excel
$fhelp_excel = new coderFormHelp();
$fobj_excel = array();
$fobj_excel['file'] = array('type' => 'text', 'name' => 'Excel檔案', 'column' => 'file', 'sql' => false, 'validate' => array(
    'required' => 'yes'
));
$fhelp_excel->Bind($fobj_excel);
?>
