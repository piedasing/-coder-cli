<?php
include_once('_config.php');
include_once('formconfig.php');
$id = get('id');
$manageinfo = "";
if ($id != "") {
    coderAdmin::vaild($auth, 'edit');

    $db = Database::DB();
    $row = $db->query_prepare_first("select * from $table where {$colname['id']}=:id", array(':id' => $id));

    //$row['auth']=coderAdmin::getAuthListAryByInt($row['auth']);
    $fhelp->bindData($row);

    $method = 'edit';
    $active = '編輯';
    $manageinfo = '  管理者 : ' . $row[$colname['admin']] . ' | 建立時間 : ' . $row[$colname['createtime']] . ' | 上次修改時間 : ' . $row[$colname['updatetime']];
    $db->close();
} else {
    coderAdmin::vaild($auth, 'add');
    $method = 'add';
    $active = '新增';
}


?>
<!DOCTYPE html>
<html>
<head>
    <?php include('../_head.php'); ?>
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
                            <?php echo $fhelp->drawForm($colname['id']) ?>
                            <div class="row">
                                <!--right start-->
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-3 control-label">
                                            <?php echo $fhelp->drawLabel($colname['superadmin']) ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm($colname['superadmin']) ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-3 control-label">
                                            <?php echo $fhelp->drawLabel($colname['name']) ?>
                                        </label>
                                        <div class="col-sm-8  controls">
                                            <?php echo $fhelp->drawForm($colname['name']) ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-3 control-label">
                                            <?php echo $fhelp->drawLabel($colname['depiction']) ?>
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php echo $fhelp->drawForm($colname['depiction']) ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-9 col-sm-offset-3">
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
                                <!--left start-->
                                <div class="col-md-6 ">
                                    <div id="authgroup" class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">
                                            權限
                                        </label>
                                        <div class="col-sm-8 controls">
                                            <?php coderAdmin::drawAuthForm($id) ?>
                                        </div>
                                    </div>
                                </div>
                                <!--left end-->
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
<script type="text/javascript" src="../js/adminauth.js"></script>
<script type="text/javascript">
    <?php coderFormHelp::drawVaildScript();?>

    $("#<?php echo $colname['superadmin']?>").click(function () {
        disableAuth();
    });

    function disableAuth() {
        $('#authgroup').css('display', $("#<?php echo $colname['superadmin']?>").prop('checked') ? 'none' : 'block');
    }

    disableAuth();
</script>
</body>
</html>
