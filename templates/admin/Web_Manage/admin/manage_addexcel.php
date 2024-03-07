<?php
include('_config.php');
$id=get('id');
$manageinfo="";
$db = Database::DB();
include('formconfig.php');

//$row=$db->query_prepare_first("select * from $table WHERE $idColumn=1");
$manageinfo="";

coderAdmin::vaild($auth,'import');
$method='add';
$active='編輯';
//$fhelp->bindData($row);
//$manageinfo = $row!=null?(' 管理者 : '.$row['admin'].' | 上次修改時間 : '.$row['updatetime']):"";


$db->close();

?>
<!DOCTYPE html>
<html>
	<head>
		 <?php  include('../head.php');?>

	 <script language="javascript" type="text/javascript">
	 </script>
	</head>
	<body>


		<?php //include('../navbar.php');?>
		<!-- BEGIN Container -->
		<div class="container" id="main-container">
			<?php //include('../left.php');?>
			<!-- BEGIN Content -->
			<div id="main-content">
				<!-- BEGIN Page Title -->
				<div class="page-title">
					<div>
						<h1><i class="<?php echo $mainicon?>"></i> <?php echo $page_title.' - Excel檔案上傳 & 匯入管理'?></h1>
						<h4><?php echo $page_desc?></h4>
					</div>
				</div>
				<!-- END Page Title -->
						<?php if ($manageinfo!='') {?>
				<div class="alert alert-info">
					   <button class="close" data-dismiss="alert">&times;</button>
					  <strong>系統資訊 : </strong> <?php echo $manageinfo?>
				 </div>
				 <?php }?>
				<!-- BEGIN Breadcrumb -->
				<div id="breadcrumbs">
					<ul class="breadcrumb">
						<li>
							<i class="icon-home"></i>
							<a href="../home/index.php">Home</a>
							<span class="divider"><i class="icon-angle-right"></i></span>
						</li>
						<?php echo $mtitle?>

					</ul>
				</div>
				<!-- END Breadcrumb -->
				<!-- BEGIN Main Content -->
				<div class="row">

					<div class="col-md-12">
						<div class="box">
							<div class="box-title">
								<h3><i class="<?php echo getIconClass($method)?>"></i> <?php echo $page_title?></h3>
								<div class="box-tool">
									<a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
								</div>
							</div>
							<div class="box-content">
								 <form  class="form-horizontal" action="save_addexcel.php" id="myform" name="myform" method="post">
								<div class="row">
				  <!--left start-->
						  <div class="col-md-12">
					<div class="form-group">
						<label class="col-sm-3 col-lg-2 control-label" >
						<?php echo $fhelp_excel->drawLabel('file')?> </label>
						<div class="col-sm-8 controls">
							  <div id="fileupload"></div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 col-lg-2 control-label" >
						</label>
						<div class="col-sm-8 controls">
							<hr/>
							<a href="../upload/excel/admin.xlsx">下載匯入範例檔</a><br/><br/>
							預覽Excel內容(請在下方確認匯入的資料內容是否正確)
                            <div class="red">帳號為唯一值，不可重複(重複將跳過此筆不匯入)</div>
							<div class="excel_data"></div>
						</div>

					</div>

					<div class="form-group">
					  <div class="col-sm-offset-3 col-lg-offset-2">
						  <button type="submit" class="btn btn-primary" ><i class="icon-ok"></i>確定送出</button>
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

				<?php include('../footer.php');?>

				<a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="icon-chevron-up"></i></a>
			</div>
			<!-- END Content -->
		</div>
		<!-- END Container -->

		  <?php include('../js.php');?>
	  <script type="text/javascript" src="../assets/jquery-validation/dist/jquery.validate.js"></script>
	  <script type="text/javascript" src="../assets/jquery-validation/dist/additional-methods.js"></script>
	  <script type="text/javascript" src="../js/coderfileupload_2.js"></script>
	  <script type="text/javascript" src="../assets/jquery-form/jquery.form.js"></script>
	  <script type="text/javascript">
			  <?php echo coderFormHelp::drawVaildScript('myform',$fobj_excel);?>
			  $('#fileupload').coderfileupload({id:'<?php echo "file";?>',size_id:'',org_filename:'<?php echo isset($row["file"])?$row["file"]:""?>',org_filepath:'<?php echo $file_path;?>',org_filesize:'',extname:'檔案只支援xls,xlsx',ajaxsrc:"../comm/uploadexcel.php",required:true});
		

		</script>
	</body>
</html>
