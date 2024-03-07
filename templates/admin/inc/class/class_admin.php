<?php
class class_admin{
    public static $cache_key = 'admin';

    public static function clearCache()
    {
        clearCache(self::$cache_key);
    }

    public static function getAllList_NoGroup($gid,$type='',$val=''){//取name,id 及 o_id (沒參加群組的名單)
        global $db;
        $table = coderDBConf::$admin;
        $table_map=coderDBConf::$groupadmin_map;
        $colname_map=coderDBConf::$col_groupadmin_map;

        $where = '';
        $where2 = '';
        $where_ary = array();
        switch ($type) {
            case 'keyword':
                $where = " AND name LIKE :namekeyword";
                $where_ary[':namekeyword'] = "%$val%";
                break;
        }
        if($gid!=''){
            $where2 = "OR $table_map.`{$colname_map['g_id']}`=$gid";
        }
        $sql = "SELECT id as value, name , o_id as pid , $table_map.`{$colname_map['g_id']}`
                FROM $table
                LEFT JOIN $table_map ON $table.`id` = $table_map.`{$colname_map['a_id']}`
                WHERE 1 AND ($table_map.`{$colname_map['g_id']}` IS NULL $where2) $where
                ORDER BY `id` DESC";            
        return $db -> fetch_all_array($sql,$where_ary);
    }

    public static function getList($type,$_val = '') { // 取 name, value
        global $db;
        $colname = "";
        $table = "";
        switch ($type) {
            case "rules":
                $colname = coderDBConf::$col_rules;
                $table = coderDBConf::$rules;
                break;
            case "admin":
                $table = coderDBConf::$admin;
                break;
        }
        if ($type =="admin") {
            $sql = "select name, id as value
                    from `".$table."`";
        } else {
            $sql = "select {$colname['name']} as name, {$colname['id']} as value
                    from `".$table."`";
        }
        $rows = $db -> fetch_all_array($sql);
        return coderHelp::getArrayPropertyVal($rows, 'value', $_val, 'name');
    }

    public static function getAllAdmin() {
        $cache_key = self::$cache_key;
        $result = getCache($cache_key);
        if (!empty($result)) {
            return json_decode($result, 1);
        }

        $db = Database::DB();
        $table = coderDBConf::$admin;
        $result = $db->fetch_all_array("SELECT id, r_id, username, name FROM {$table}");
        // saveCache($cache_key, json_encode($result, 1));
        return $result;
    }

    public static function getNameByUsername($username = '') {
        if (empty($username)) {
            return '';
        }

        $row = self::getByUsername($username);

        return $row['name'] ?? '';
    }

    public static function getByUsername($username) {
        if (empty($username)) {
            return null;
        }

        $row_admins = self::getAllAdmin();
        $index = array_search($username, array_column($row_admins, 'username'));
        if ($index === false) {
            return null;
        }
        return $row_admins[$index] ?? null;
    }

    public static function getByName($name) {
        if (empty($name)) {
            return null;
        }

        $row_admins = self::getAllAdmin();
        $index = array_search($name, array_column($row_admins, 'name'));
        if ($index === false) {
            return null;
        }
        return $row_admins[$index] ?? null;
    }
}
?>
