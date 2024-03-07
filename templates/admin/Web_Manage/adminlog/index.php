<?php
include('_config.php');
include_once "filterconfig.php";

try {
    coderAdmin::vaild($auth, 'view');

    $listHelp = new coderListHelp('table1', '使用記錄');
    $listHelp->ajaxSrc = "service.php";

    $col = array();
    $col[] = array('column' => 'id', 'name' => 'ID', 'order' => true, 'width' => '60', 'def_desc' => 'desc');
    $col[] = array('column' => 'username', 'name' => '帳號', 'order' => true, 'width' => '100');
    $col[] = array('column' => 'main_key', 'name' => '模組', 'order' => true, 'width' => '100');
    $col[] = array('column' => 'action', 'name' => '操作', 'order' => true, 'width' => '100');
    $col[] = array('column' => 'descript', 'name' => '資訊', 'order' => true);
    $col[] = array('column' => 'ip', 'name' => 'IP', 'width' => '100');
    $col[] = array('column' => 'createtime', 'name' => '操作時間', 'order' => true, 'width' => '150');
    $listHelp->Bind($col);

    $listHelp->bindFilter($help);
} catch (Exception $e) {
    $errorhandle = new coderErrorHandle();
    $errorhandle->setException($e); // 收集例外
    $result['result'] = false;
    $result['data'] = $errorhandle->getErrorMessage();
    echo json_encode($result);
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include('../_head.php'); ?>

</head>
<body>
<?php include('../_navbar.php'); ?>
<!-- BEGIN Container -->
<div class="container" id="main-container">
    <?php include('../_left.php'); ?>
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

        <!-- BEGIN Breadcrumb -->
        <div id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="icon-home"></i>
                    <a href="../home/index.php">Home</a>
                    <span class="divider"><i class="icon-angle-right"></i></span>
                </li>
                <?php echo $mtitle ?>
            </ul>
        </div>
        <!-- END Breadcrumb -->

        <!-- BEGIN Main Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-title">
                        <h3 style="float:left"><i class="icon-table"></i> <?php echo $page_title ?></h3>
                        <div class="box-tool">
                            <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                        </div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="box-content">

                        <?php $listHelp->drawTable() ?>

                    </div>
                </div>
            </div>
        </div>

        <?php include('../footer.php'); ?>

        <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="icon-chevron-up"></i></a>
    </div>
    <!-- END Content -->
</div>
<!-- END Container -->

<?php include('../_js.php'); ?>
<script type="text/javascript" src="../js/coderlisthelp.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table1').coderlisthelp({
            debug: true, callback: function (obj, rows) {
                obj.html('');
                var count = rows.length;
                for (var i = 0; i < count; i++) {
                    var row = rows[i];
                    var $tr = $('<tr></tr>');

                    $tr.append('<td>' + row["id"] + '</td>');
                    $tr.append('<td>' + row["username"] + '</td>');
                    $tr.append('<td>' + row["main_key"] + '</td>');
                    $tr.append('<td>' + row["action"] + '</td>');
                    $tr.append('<td>' + row["descript"] + '</td>');
                    $tr.append('<td>' + row["ip"] + '</td>');
                    $tr.append('<td>' + row["createtime"] + '</td>');
                    obj.append($tr);
                }
            }
        });
    });
</script>
</body>
</html>
