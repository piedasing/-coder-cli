<?php
class class_rules{
    public static function getList(){//取name,value及nt_id
        global $db;
        $table = coderDBConf::$rules; 
        $colname = coderDBConf::$col_rules; 
        $sql = "select {$colname['name']} as name,{$colname['id']} as value 
                from $table
                ORDER BY `{$colname['id']}` DESC";
                            
        return $db -> fetch_all_array($sql);
    }
    //刪除 快取
    public static function clearCache() {

        $redis = class_redis::getInstance();
        //刪除此class的所有redis
        $redis->delByPattern(self::class);

    }
}
?>