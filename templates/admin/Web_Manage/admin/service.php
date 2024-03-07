<?php
include('_config.php');
include_once('filterconfig.php');
$errorhandle = new coderErrorHandle();
try {
    coderAdmin::vaild($auth, 'view');
    $db = Database::DB();
    $sHelp = new coderSelectHelp($db);
    $sHelp->select = "a.* , r.{$colname_rules['name']} ";
    $sHelp->table = $table . " a 
					LEFT JOIN $table_rules r ON a.`r_id` = r.{$colname_rules['id']} 
					";
    $sHelp->page_size = get("pagenum");
    $sHelp->page = get("page");
    $sHelp->orderby = get("orderkey", 1);
    $sHelp->orderdesc = get("orderdesc", 1);

    $sqlstr = $help->getSQLStr();

    $wheresql = $sqlstr->SQL;
    $sHelp->where = $wheresql;

    $rows = $sHelp->getList();
    for ($i = 0; $i < count($rows); $i++) {
        //圖片
        if (!empty($rows[$i]['pic'])) {
            $rows[$i]['pic'] = '<img src="' . $file_path . 's' . $rows[$i]['pic'] . '" style="width: 40px; height: 40px; object-fit: cover; border-radius: 100%; background-color: #000;">';
        }

        //啟用
        $rows[$i]['ispublic'] = '
            <div class="bootstrap-switch-btn switch-mini" data-on="success" data-off="warning">
                <input class="switch_btn" type="checkbox" data-type="ispublic" data-id="' . $rows[$i]['id'] . '" data-val="1"' . (($rows[$i]['ispublic'] == 1) ? 'checked' : '') . ' />
            </div>';

        //建立時間
        $rows[$i]['createtime'] = coderHelp::getDateTime($rows[$i]['createtime']);

        //最後更新時間
        $rows[$i]['updatetime'] = coderHelp::getDateTime($rows[$i]['updatetime']);

        //權限
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
