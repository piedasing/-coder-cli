<?php
include('_config.php');
include_once('filterconfig.php');

coderAdmin::vaild($auth, 'view');

$listHelp = new coderListHelp('table1', '帳號');
// $listHelp->mutileSelect = true;
$listHelp->editLink = "manage.php";
$listHelp->addLink = "manage.php";
$listHelp->ajaxSrc = "service.php";
$listHelp->delSrc = "delservice.php";
//$listHelp->excelLink="savetoexcel.php"; //匯出 excel
//$listHelp->addexcelLink="manage_addexcel.php"; //匯入 excel


$col = array();
$col[] = array('column' => 'id', 'name' => 'ID', 'order' => true, 'width' => 60);
$col[] = array('column' => 'ispublic', 'name' => '啟用', 'order' => true, 'width' => 60);
$col[] = array('column' => 'pic', 'name' => '圖片', 'width' => 70);
$col[] = array('column' => 'name', 'name' => '名字', 'order' => true, 'width' => 120);
$col[] = array('column' => 'username', 'name' => '帳號', 'order' => true, 'width' => 120);
$col[] = array('column' => 'email', 'name' => '信箱', 'order' => true);
$col[] = array('column' => $colname_rules['name'], 'name' => '權限', 'order' => true);
$col[] = array('column' => 'ip', 'name' => '登入IP', 'width' => 120);
// $col[] = array('column' => 'admin', 'name' => '管理員', 'order' => true, 'width' => 90);
// $col[] = array('column' => 'createtime', 'name' => '建立時間', 'order' => true, 'width' => 150);
// $col[] = array('column' => 'updatetime', 'name' => '最後更新時間', 'order' => true, 'width' => 150);
$listHelp->Bind($col);

$listHelp->bindFilter($help);
$db = Database::DB();

$db->close();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include('../_head.php'); ?>

    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-switch/static/stylesheets/bootstrap-switch.css"/>
</head>
<body>
<?php if (empty($r_name)) include('../_navbar.php'); ?>
<!-- BEGIN Container -->
<div class="container" id="main-container">
    <?php if (empty($r_name)) include('../_left.php'); ?>
    <!-- BEGIN Content -->
    <div id="main-content">
        <!-- BEGIN Page Title -->
        <div class="page-title">
            <div>
                <h1><i class="<?php echo $mainicon ?>"></i> <?php echo $page_title ?></h1>
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
        <!-- END Main Content -->

        <?php include('../_footer.php'); ?>

        <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="icon-chevron-up"></i></a>
    </div>
    <!-- END Content -->
</div>
<!-- END Container -->


<?php include('../_js.php'); ?>
<script type="text/javascript" src="../js/coderlisthelp.js"></script>
<script type="text/javascript" src="../assets/bootstrap-switch/static/js/bootstrap-switch.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table1').coderlisthelp({
            debug: true, callback: function (obj, rows) {
                obj.html('');
                var count = rows.length;
                for (var i = 0; i < count; i++) {
                    var row = rows[i];
                    var $tr = $('<tr></tr>');
                    $tr.attr("editlink", "username=" + row["username"]);
                    $tr.attr("delkey", row["id"]);
                    $tr.attr("title", ' 帳號: ' + row["username"]);

                    $tr.append('<td>' + row["id"] + '</td>');
                    $tr.append('<td>' + row["ispublic"] + '</td>');
                    $tr.append('<td>' + row['pic'] + '</td>');
                    $tr.append('<td>' + row["name"] + '</td>');
                    $tr.append('<td>' + row["username"] + '</td>');
                    $tr.append('<td>' + row["email"] + '</td>');
                    $tr.append('<td>' + row["<?php echo $colname_rules['name']?>"] + '</td>');
                    $tr.append('<td>' + row["ip"] + '</td>');
                    obj.append($tr);
                }
            }, listComplete: function () {
                $('#table1').find('img').click(function () {
                    openPicBox({
                        href: $(this).attr('src'),
                        initialWidth: '50px',
                        initialHeight: '50px'
                    });
                });

                $('#table1 .bootstrap-switch-btn').bootstrapSwitch();

                $('#table1 .bootstrap-switch-btn').on('switch-change', function (e, data) {
                    var _this = $(this).find('.switch_btn');
                    $.ajax({
                        url: 'save_switch.php',
                        data: {
                            '<?php echo "id"?>': _this.attr('data-id'),
                            'type': _this.attr('data-type'),
                            'val': _this.attr('data-val'),
                            'checked': _this.prop('checked') ? 1 : 0
                        },
                        type: "POST",
                        dataType: 'json',
                        success: function (r) {
                            if (r.result) {
                                showNotice("ok", "成功", '己完成編輯。<br>');
                                $('#refreshBtn').click();
                            } else {
                                showNotice("alert", "失敗", '編輯失敗。<br>');
                            }
                        }
                    });
                });
            }
        });
    });

</script>

</body>
</html>
