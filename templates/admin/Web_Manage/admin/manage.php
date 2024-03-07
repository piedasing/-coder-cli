<?php
include('_config.php');
include_once('formconfig.php');
$username = get('username', 1);
$manageinfo = "";
$pic = "";
$showAuth = false;

if ($username != "") {
    if ($username != $adminuser['username']) {
        coderAdmin::vaild($auth, 'edit');
        $showAuth = true;
    }
    $db = Database::DB();

    $row = $db->query_prepare_first(" SELECT $table.* FROM $table WHERE username=:username", array(':username' => $username));

    // 編輯時, password預設為空白
    unset($row['password']);

    $fhelp->bindData($row);

    $pic = $row['pic'];

    $fhelp->setAttr('repassword', 'validate', array('maxlength' => '20', 'minlength' => 6, 'equalto' => '#password', 'data-msg-equalto' => '密碼與確認密碼不一致'));
    $fhelp->setAttr('username', 'readonly', true);

    $method = 'edit';
    $active = '編輯';
    $manageinfo = '  管理者 : ' . $row['admin'] . ' | 建立時間 : ' . $row['createtime'] . ' | 上次修改時間 : ' . $row['updatetime'] . ' | 最後登入時間 : ' . $row['logintime'] . ' | 最後登入IP : ' . $row['ip'];

    $row_history = coderAdminLog::getLogByUser($row['username'], 5);
    $db->close();
} else {
    coderAdmin::vaild($auth, 'add');
    $method = 'add';
    $active = '新增';
    $fhelp->setAttr('password', 'validate', array('required' => 'yes', 'maxlength' => '20', 'minlength' => 6));
    $fhelp->setAttr('repassword', 'validate', array('required' => 'yes', 'maxlength' => '20', 'minlength' => 6, 'equalto' => '#password', 'data-msg-equalto' => '請重新輸入管理員密碼'));
    $showAuth = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php include('../_head.php'); ?>
    <script language="javascript" type="text/javascript">
        <?php
        coderFormHelp::drawPicScript($method, $file_path, $pic, 'org_pic');
        ?>
    </script>
</head>
<body>
<!-- BEGIN Container -->
<div class="container" id="main-container">
    <!-- BEGIN Content -->
    <div id="main-content">
        <!-- BEGIN Page Title -->
        <div class="page-title">
            <div>
                <h1><i class="<?php echo $mainicon ?>"></i> <?php echo $page_title ?>管理</h1>
                <h4><?php echo $page_desc ?></h4>
            </div>
        </div>
        <!-- END Page Title -->
        <?php if ($manageinfo != '') { ?>
            <div class="alert alert-info">
                <button class="close" data-dismiss="alert">&times;</button>
                <strong>系統資訊 : </strong> <?php echo $manageinfo ?>
            </div>
        <?php } ?>
        <!-- BEGIN Main Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-title">
                        <h3><i class="<?php echo getIconClass($method) ?>"></i> <?php echo $page_title . $active ?></h3>
                        <div class="box-tool">
                            <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" action="save.php" id="myform" name="myform" method="post">
                            <?php echo $fhelp->drawForm('id') ?>
                            <div class="row">
                                <!--right start-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('ispublic') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('ispublic') ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('username') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('username') ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('password') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('password') ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('repassword') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('repassword') ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('name') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('name') ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('email') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('email') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('email_backup') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('email_backup') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('r_id') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('r_id') ?>
                                        </div>
                                    </div>
                                </div>
                                <!--left end-->
                                <!--right start-->
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('info') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm('info') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            <?php echo $fhelp->drawLabel('pic') ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <div id="picupload"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="icon-ok"></i>完成<?php echo $active ?>
                                            </button>
                                            <button type="button" class="btn" onclick="if(confirm('確定要取消<?php echo $active ?>?')){parent.closeBox();}">
                                                <i class="icon-remove"></i>取消<?php echo $active ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!--right end-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Main Content -->

        <?php include('../footer.php'); ?>
        <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="icon-chevron-up"></i></a>
    </div>
    <!-- END Content -->
</div>
<!-- END Container -->


<?php include('../_js.php'); ?>
<script type="text/javascript" src="../assets/jquery-validation/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../assets/jquery-validation/dist/additional-methods.js"></script>
<script type="text/javascript" src="../js/coderpicupload.js"></script>
<script type="text/javascript">
    let method = "<?php echo $method; ?>";
    let isAuth = <?php echo coderAdmin::isAuth($auth, coderAdminLog::getActionKey('edit')); ?>;

    $('#picupload').coderpicupload({
        pics: [{name: '縮圖', type: 5, tag: 's', width: 60, height: 60}],
        width: '100',
        height: '100',
        s_width: '60px',
        s_height: '60px',
        org_pic: org_pic
    });

    <?php coderFormHelp::drawVaildScript();?>

    if (method === "add") {
        $("#username").rules("add", {
            messages: {
                remote: "此帳號己被使用,請重新輸入!",
            },
            remote: {
                url: "checkusername.php",
                type: "post",
                data: {
                    username: function () {
                        return $('#username').val()
                    }
                }
            }
        });

        // $("#pic").rules("add", {
        //     required: true,
        //     messages: {
        //         required: "請上傳圖片!",
        //     }
        // });
    }

    if (!isAuth) {
        $("#ispublic,#r_id").attr("disabled", "disabled");
    }
</script>
</body>
</html>
