<?php
include_once('_config.php');
include_once('filterconfig.php');
$errorhandle = new coderErrorHandle();
try {
    coderAdmin::vaild($auth, 'view');
    $db = Database::DB();
    $sHelp = new coderSelectHelp($db);
    $sHelp->select = "{$table}.*, (SELECT COUNT(1) FROM {$table_admin} WHERE {$table_admin}.r_id={$table}.r_id) AS num";
    $sHelp->table = $table;
    $sHelp->page_size = get("pagenum");
    $sHelp->page = get("page");
    $sHelp->orderby = get("orderkey", 1);
    $sHelp->orderdesc = get("orderdesc", 1);

    $sqlstr = $help->getSQLStr();
    $wheresql = $sqlstr->SQL;
    $sHelp->where = $wheresql;

    $rows = $sHelp->getList();
    for ($i = 0; $i < count($rows); $i++) {
        $rows[$i][$colname['admin']] = class_admin::getNameByUsername($rows[$i][$colname['admin']]);

        //操作權限
        $rows[$i]['auth'] = getAuthStr($rows[$i][$colname['id']], $rows[$i][$colname['superadmin']]);

        //成員數量
        $rows[$i]['num'] = '<a onclick="openBox(\'../admin/index.php?r_name=' . $rows[$i]['r_name'] . '\',null,null,\'fade\',function(){$(\'#refreshBtn\').click();}) " class="badge badge-large badge-info" style="cursor: pointer;">' . $rows[$i]['num'] . '</a>';

        //建立時間
        $rows[$i]['r_createtime'] = !empty($rows[$i]['r_createtime']) ? coderHelp::getDateTime($rows[$i]['r_createtime']) : '';

        //最後更新時間
        $rows[$i]['r_updatetime'] = !empty($rows[$i]['r_updatetime']) ? coderHelp::getDateTime($rows[$i]['r_updatetime']) : '';
    }

    $result['result'] = true;
    $result['data'] = $rows;
    $result['page'] = $sHelp->page_info;
    echo json_encode($result);
} catch (Exception $e) {
    $errorhandle->setException($e); // 收集例外
}

if ($errorhandle->isException()) {
    $result['result'] = false;
    $result['data'] = $errorhandle->getErrorMessage();
    echo json_encode($result);
}

?>
