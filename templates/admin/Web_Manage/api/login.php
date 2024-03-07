<?php
include('_config.php');

$errorhandle = new coderErrorHandle();
$username = trim(post('username', 1));
$password = trim(post('password', 1));
$code = trim(post('code', 1));
$remember_me = post('remember_me');
try {
    // 把log清掉
    coderAdminLog::clearSession();

    $_SESSION['loginfo'] = '';
    $sessionName = $incary_captcha_types['login'];
    if ($code == '' || $code != $_SESSION[$sessionName]) {
        throw new Exception('圖形驗證碼不正確!');
    }
    if ($username == "" || $password == "") {
        throw new Exception('請輸入帳號與密碼!');
    }

    coderAdmin::login($username,$password,$remember_me);

    $code != $_SESSION["VaildImgCode"] = "";
}
catch(Exception $e) {
	$errorhandle->setException($e);
}

if ($errorhandle->isException()) {
	$result['result'] = false;
    $result['msg'] = $errorhandle->getErrorMessage(false);
} else {
	$result['result'] = true;
}

echo json_encode($result);
?>
