<?php
include('_config.php');

$db = Database::DB();
coderAdmin::vaild($auth,'import');
//引入函式庫
include '../../Classes/PHPExcel.php';
//設定要被讀取的檔案

$file = $_POST["file_path"];
try {
	$objPHPExcel = PHPExcel_IOFactory::load($file);
} catch(Exception $e) {
	die('Error loading file "'.pathinfo($file,PATHINFO_BASENAME).'": '.$e->getMessage());
}

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$sheetData = uniqueAssocArray($sheetData, "B") ;
?>
<style type="text/css">
.test {
	font-size: 16px;
}
</style>

<div class="table-big">
	<table width="90%" border="0" align="center" class="test table table-striped table-hover fill-head" >
		<thead>
			<tr>
				<th align="center">#</th>
				<th align="center">啟用</th>
				<th align="center">帳號</th>
                <th align="center">密碼</th>
				<th align="center">名子</th>
                <th align="center">Email</th>
				<th align="center">所屬群組</th>
                <th align="center">所屬組織</th>
				<th align="center">權限角色ID</th>
                <th align="center">個人資料</th>
			</tr>
		</thead>
		<?php
		$i = 1;
		$rowindex=0;
		foreach($sheetData as $key => $col){
			$rowindex++;
			if($rowindex<=1)continue;
			
			if($col['A'] == "" && $col['B'] == "" && $col['C'] == "" && $col['D'] == "" && $col['E'] == "" && $col['H'] == "")continue;
			if(!isUsernameNotExisit($col['B'])) continue;
		?>
		<tr>
			<td><?php echo $i;?></td>
			<?php
			foreach ($col as $colkey => $colvalue){
				if(!in_array($colkey, array("A","B","C","D","E","F","G","H","I")))continue;
			?>
			<td>
				<?php
					switch ($colkey) {
						case "A":
							echo $incary_yn_layout[$colvalue];
							break;
						case "F":
							echo class_admin::getList("group",$colvalue);
							break;
						case "G":
							echo class_admin::getList("o",$colvalue);
							break;
						case "H":
							echo class_admin::getList("rules",$colvalue);
							break;
						default:
        					echo $colvalue;
					}
				?>
			</td>
			<?php }?>
		</tr>
		<?php
		$i++;}
		$db->close();
		?>
	</table>
</div>