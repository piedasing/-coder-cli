<?php
$inc_path = "../inc/";

include($inc_path . '_config.php');

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $webname . $webmanagename; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo WEB_FAVICON_URL; ?>">
    <!--base css styles-->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <!--flaty css styles-->
    <link rel="stylesheet" href="css/flaty.css">
    <link rel="stylesheet" href="css/flaty-responsive.css">
</head>

<body class="login-page">
    <!-- BEGIN Main Content -->
    <div id="loginform" class="login-wrapper">
        <!-- BEGIN Login Form -->
        <form id="myform">
            <img style="width: 100%; max-height: 200px; object-fit: contain; object-position: center;" src="<?php echo WEB_LOGO_URL; ?>">
            <hr />
            <div id="alertdiv" class="alert alert-info" style="display:none">
                <strong>登入中...</strong>請稍候
            </div>
            <div id="formcontent">
                <div class="form-group">
                    <div class="controls">
                        <input type="text" id="username" name="username" placeholder="請在此輸入您的帳號" class="form-control" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <input type="password" id="password" name="password" placeholder="請在此輸入您的密碼" class="form-control" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group">
                    <div style="float:left;width:180px;">
                        <input type="text" id="code" name="code" placeholder="右圖數字" class="form-control" autocomplete="off" />
                    </div>
                    <a href="javascript:void(0)">
                        <img id="codeimg" class="show-popover" style="float: left;" src="../api/captcha/login?time=<?php echo time() ?>" onClick="$(this).attr('src','../api/captcha/login?time='+getTimeStamp())" data-trigger="hover" data-placement="top" data-content="點我就可以重新取得一組新的驗證圖片!" data-original-title="看不清楚嗎?" />
                    </a>
                    <div style="clear: both;"></div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <label class="checkbox"><input type="checkbox" value="1" name="remember_me" id="remember_me"> 保持登入</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <button type="button" id="formbtn" class="btn btn-primary form-control">登入</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END Login Form -->
    </div>
    <!-- END Main Content -->
    <script src="assets/jquery/jquery-2.0.3.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="assets/jquery-cookie/jquery.cookie.js"></script>
    <script src="assets/jquery-validation/dist/jquery.validate.js"></script>
    <script src="js/animatehelp.js"></script>
    <script src="js/flaty.js"></script>
    <script src="js/public.js"></script>
    <script src="js/login.js"></script>
</body>

</html>
