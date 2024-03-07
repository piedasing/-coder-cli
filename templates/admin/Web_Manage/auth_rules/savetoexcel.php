<?php
include("_config.php");
include('filterconfig.php');
$list_id = get('list_id',1);


coderAdmin::vaild($auth,'export');
$db = Database::DB();
	$sHelp=new coderSelectHelp($db);
	$sHelp->select="*,(SELECT count(*) FROM $table_admin admin WHERE $table.{$colname['id']} = admin.`r_id`) as num";
	$sHelp->table=$table;
	$sHelp->page_size=-1;
	/*$sHelp->page=get("page");*/
	$sHelp->orderby=get("orderkey",1);
	$sHelp->orderdesc=get("orderdesc",1);
	//$sHelp->orderby=get("orderkey",1);
	//$sHelp->orderdesc=get("orderdesc",1);

	$sqlstr=$help->getSQLStr();
	$wheresql = $sqlstr->SQL;
	$sHelp->where=$wheresql;

	$rows=$sHelp->getList();
	 for($i=0;$i<count($rows);$i++){
	 	$rows[$i]['auth']=getAuthStr($rows[$i][$colname['id']],$rows[$i][$colname['superadmin']]);
	 }
    ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>excel</title>
    </head>
    <body>
    <table width="1266" border="1" cellspacing="0" cellpadding="0">
      <tr>
    	<th width="60" align="center" >ID</th>
        <th width="200" align="center" >名稱</th>
        <th width="300" align="center" >操作權限</th>
        <th width="100" align="center" >成員數量</th>
        <th width="150" align="center" >建立日期</th>
        <th width="150" align="center" >最後更新日期</th>
        
      </tr>
      
    <?php
    foreach ($rows as $key => $row) {
    ?>
      <tr>
    	 <td align="center"><?php echo $row[$colname['id']]?></td>
         <td align="center"><?php echo $row[$colname['name']]?></td>
         <td align="center"><?php echo $row['auth']?></td>
         <td align="center"><?php echo $row['num']?></td>
         <td align="center"><?php echo $row[$colname['createtime']]?></td>
         <td align="center"><?php echo $row[$colname['updatetime']]?></td>
         
         
      </tr>
    <?php
    }
    $db->close();
    ?>
    </table>

    </body>
</html>
<?php 
    $outStr=ob_get_contents(); 
    ob_end_clean(); 
    header("Content-type:application/vnd.ms-excel");
    Header("Accept-Ranges: bytes"); 
    Header("Accept-Length: ".strlen($outStr)); 
		
    Header("Content-Disposition: attachment; filename=auth_rulesList_".date("Ymdhis").".xls"); 
    // 輸出文件內容          
    echo $outStr; 
?> 