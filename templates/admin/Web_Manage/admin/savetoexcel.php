<?php
include("_config.php");
include('filterconfig.php');
$list_id = get('list_id',1);


coderAdmin::vaild($auth,'export');
$db = Database::DB();
	$sHelp=new coderSelectHelp($db);
	$sHelp->select="a.* , r.{$colname_rules['name']} ";
	$sHelp->table = $table." a 
					LEFT JOIN $table_rules r ON a.`r_id` = r.{$colname_rules['id']} 
					";
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
		$rows[$i]['ispublic']=$incary_yn_layout[$rows[$i]['ispublic']];
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
        <th width="60" align="center" >啟用</th>
        <th width="100" align="center" >名子</th>
        <th width="150" align="center" >登入帳號</th>
        <th width="200" align="center" >E-mail</th>
        <th width="180" align="center" >權限</th>
        <th width="150" align="center" >ip</th>
        <th width="150" align="center" >建立日期</th>
        <th width="150" align="center" >最後更新日期</th>
        
      </tr>
      
    <?php
    foreach ($rows as $key => $row) {
    ?>
      <tr>
    	 <td align="center"><?php echo $row['id']?></td>
         <td align="center"><?php echo $row['ispublic']?></td>
         <td align="center"><?php echo $row['name']?></td>
         <td align="center"><?php echo $row['username']?></td>
         <td align="center"><?php echo $row['email']?></td>
         <td align="center"><?php echo $row[$colname_rules['name']]?></td>
         <td align="center"><?php echo $row['ip']?></td>        
         <td align="center"><?php echo $row['createtime']?></td>
         <td align="center"><?php echo $row['updatetime']?></td>
         
         
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