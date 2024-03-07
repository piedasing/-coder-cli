<?php
include_once('_config.php');
include_once('filterconfig.php');
coderAdmin::vaild($auth, 'view');

$listHelp = new coderListHelp('table1', '角色管理');
// $listHelp->mutileSelect = true;
$listHelp->editLink = "manage.php";
$listHelp->addLink = "manage.php";
$listHelp->ajaxSrc = "service.php";
$listHelp->delSrc = "delservice.php";
//$listHelp->excelLink="savetoexcel.php"; //匯出 excel

$col = array();
$col[] = array('column' => $colname['id'], 'name' => 'ID', 'order' => true, 'width' => '60');
$col[] = array('column' => $colname['name'], 'name' => '名稱', 'order' => true, 'width' => '200');
$col[] = array('column' => 'auth', 'name' => '權限');
$col[] = array('column' => 'num', 'name' => '成員數量', 'order' => true, 'width' => '100');
$col[] = array('column' => $colname['admin'], 'name' => '最後更新者', 'order' => true, 'width' => '90');
$col[] = array('column' => $colname['createtime'], 'name' => '建立時間', 'order' => true, 'width' => '150');
$col[] = array('column' => $colname['updatetime'], 'name' => '最後更新時間', 'order' => true, 'width' => '150');
$listHelp->Bind($col);

$listHelp->bindFilter($help);
$db = Database::DB();

$db->close();
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


        <?php include('../footer.php'); ?>

        <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="icon-chevron-up"></i></a>
    </div>
    <!-- END Content -->
</div>
<!-- END Container -->

<?php include('../js.php');
//$userhref = help::canViewUser() ? "../admin/index.php?rules='+row[\"{$colname['name']}\"]+'" : "javascript:none()"; ?>

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
                    $tr.attr("editlink", "id=" + row["<?php echo $colname['id']?>"]);
                    $tr.attr("delkey", row["<?php echo $colname['id']?>"]);
                    $tr.attr("title", row["<?php echo $colname['name']?>"]);

                    $tr.append('<td>' + row["<?php echo $colname['id']?>"] + '</td>');
                    $tr.append('<td>' + row["<?php echo $colname['name']?>"] + '</td>');
                    $tr.append('<td>' + row["auth"] + '</td>');
                    $tr.append('<td>' + row['num'] + '</td>');
                    $tr.append('<td>' + row["<?php echo $colname['admin']?>"] + '</td>');
                    $tr.append('<td>' + row["<?php echo $colname['createtime']?>"] + '</td>');
                    $tr.append('<td>' + row["<?php echo $colname['updatetime']?>"] + '</td>');
                    obj.append($tr);
                }
            }
        });
    });

</script>

</body>
</html>
