<?php
//以email重設密碼
$inc_path="../inc/";
include($inc_path.'_config.php');
$coder = get('coder',1);
$uid = get('uid',1);

$db = Database::DB();
$row = $db->query_prepare_first("select email from ".coderDBConf::$admin." where id='$uid' AND forgetcode='$coder' AND DATE_ADD(`forgetcode_time`,INTERVAL 24 HOUR )>=NOW()");
$db->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $webname?>後台管理系統-Neptunus</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php echo WEB_FAVICON_URL; ?>">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!--base css styles-->
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">


        <!--page specific css styles-->

        <!--flaty css styles-->
        <link rel="stylesheet" href="css/flaty.css">
        <link rel="stylesheet" href="css/flaty-responsive.css">
    </head>
<?php
if($row){
?>
    <body class="login-page">

        <!-- BEGIN Main Content -->

        <div id="confirm_pwemailform" class="login-wrapper">
            <!-- BEGIN Register Form -->
            <form id="confirm_pwemail" action="#" method="get">
                <input type="hidden" value="<?php echo $coder?>" id="coder" name="coder">
                <input type="hidden" value="<?php echo $uid?>" id="uid" name="uid">
                <h3>重設密碼</h3>
                <hr/>
                <div id="alertdiv" class="alert alert-info" style="display:none">
                    <strong>驗證中...</strong>請稍候
                </div>
                <div id="formcontent">
                    <div class="form-group">
                        <div class="controls">
                            <input type="text" placeholder="Email" value="<?php echo $row["email"]?>" class="form-control" disabled/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls">
                            <input type="password" id="password" name="password" placeholder="請輸入密碼" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls">
                            <input type="password" id="password_repeat" name="password_repeat" placeholder="密碼確認" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls">
                            <button type="button" id="pwchange_btn" class="btn btn-primary form-control">確認</button>
                        </div>
                    </div>
                    <hr/>
                </div>
            </form>
            <!-- END Register Form -->
        </div>
        <!-- END Main Content -->

        <!--basic scripts-->
        <script type="text/javascript" src="assets/jquery/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/nicescroll/jquery.nicescroll.min.js"></script>
        <script type="text/javascript" src="assets/jquery-cookie/jquery.cookie.js"></script>
        <script type="text/javascript" src="assets/jquery-validation/dist/jquery.validate.js"></script>
        <script type="text/javascript" src="js/animatehelp.js"></script>
        <!--page specific plugin scripts-->
        <!--flaty scripts-->
        <script src="js/flaty.js"></script>
        <script src="js/public.js"></script>
		<script language="javascript" type="text/javascript" src="js/confirm_pwemail.js"></script>
    </body>
<?php
}else{
?>
    <body class="error-page">

        <!-- BEGIN Main Content -->
        <div class="error-wrapper">
            <h5>錯誤<span>Error</span></h5>
            <p>抱歉，此連結無效或已過期，無法取回密碼<br/>請重新取得有效連結</p>
            <hr/>
            <p class="clearfix">
                <a href="login.php" class="pull-left">← 前往登入頁</a>
            </p>
        </div>
        <!-- END Main Content -->

        <!--basic scripts-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="assets/jquery/jquery-2.0.3.min.js"><\/script>')</script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    </body>
<?php
}
?>
</html>
