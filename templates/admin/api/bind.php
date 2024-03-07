<?php
include '_config.php';

$result = true;
$resp = ['data'=>[]];
$code = 200;
$retMsg = "儲存成功";

try {
    $REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
    if ($REQUEST_METHOD !== 'POST') {
        throw new Exception('操作失敗', 400);
    }

    $token = post('token', 1);
    $formId = post('formId', 1);

    class_form::bind($token, $formId);

} catch (Exception $e) {
    $result = false;
    $resp["msg"] = $e->getMessage();
    $code = $e->getCode();
}

$resp = array_merge(array("result"=>$result), $resp);
$resp['code'] = $code;

http_response_code($code);
echo json_encode($resp);

?>
