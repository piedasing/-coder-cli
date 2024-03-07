<?php

class Database
{

    private static $instance;

    var $server = ""; //database server
    var $user = ""; //database login name
    var $pass = ""; //database login password
    var $database = ""; //database name
    var $pre = ""; //table prefix

    /* 讀取 ↓ */
    var $server_read = ""; //database server
    var $user_read = ""; //database login name
    var $pass_read = ""; //database login password
    var $database_read = ""; //database name
    var $pre_read = ""; //table prefix
    /* 讀取 ↑ */

    var $timezone = ""; //時區
    var $character = ""; //資料庫編碼


#######################
//internal info
    var $error = "";
    var $errno = 0;

//number of rows affected by SQL query
    var $affected_rows = 0;

    var $link_id;
    var $link_id_read; //讀取
    var $query_id = 0;

    var $debug = true;
    var $debugsql = true;
    var $commandtext = '';
#-#############################################
# desc: constructor
    function __construct($server, $user, $pass, $database, $server_read, $user_read, $pass_read, $database_read, $pre = '', $pre_read = '', $timezone = '+8:00', $character = 'utf8')
    {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
        $this->pre = $pre;

        $this->server_read = $server_read;
        $this->user_read = $user_read;
        $this->pass_read = $pass_read;
        $this->database_read = $database_read;
        $this->pre_read = $pre_read;

        $this->timezone = $timezone;
        $this->character = $character;

        $this->commandtext = "";
    }#-#constructor()

    public static function DB()
    {
        if (!isset(self::$instance)) {
            self::$instance = self::initDB();
        }
        return self::$instance;
    }

    public static function initDB()
    {
        global $MYSQL_HS, $MYSQL_ID, $MYSQL_PW, $MYSQL_DB;
        global $MYSQL_HS_read, $MYSQL_ID_read, $MYSQL_PW_read, $MYSQL_DB_read;
        global $MYSQL_TIMEZONE, $MYSQL_CHARACTER;
        $db = new Database($MYSQL_HS, $MYSQL_ID, $MYSQL_PW, $MYSQL_DB, $MYSQL_HS_read, $MYSQL_ID_read, $MYSQL_PW_read, $MYSQL_DB_read, '', '', $MYSQL_TIMEZONE, $MYSQL_CHARACTER);
        $db->connect();
        return $db;
    }

#-#############################################
# desc: connect and select database using vars above
# Param: $new_link can force connect() to open a new link, even if mysql_connect() was called before with the same parameters
    function connect($new_link = false)
    {
        $dsn = 'mysql:host=' . $this->server . ';dbname=' . $this->database;

        $this->link_id = new PDO($dsn, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="NO_ENGINE_SUBSTITUTION"'));
        $this->link_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->link_id) {//open failed
            $this->oops("Could not connect to server: <b>$this->server</b>.");
        }

        //$this->link_id->query("SET NAMES 'UTF8'");
        $this->link_id->query("set character set {$this->character}"); // 編碼設定
        $this->link_id->query("SET time_zone = \"{$this->timezone}\""); // 時區設定

        // unset the data so it can't be dumped
        $this->server = '';
        $this->user = '';
        $this->pass = '';
        $this->database = '';

        /* 讀取 ↓ */
        $dsn_read = 'mysql:host=' . $this->server_read . ';dbname=' . $this->database_read;

        $this->link_id_read = new PDO($dsn_read, $this->user_read, $this->pass_read, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="NO_ENGINE_SUBSTITUTION"'));
        $this->link_id_read->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->link_id_read) {//open failed
            $this->oops("Could not connect to server: <b>$this->server_read</b>.");
        }

        //$this->link_id_read->query("SET NAMES 'UTF8'");
        $this->link_id_read->query("set character set {$this->character}"); // 編碼設定
        $this->link_id_read->query("SET time_zone = \"{$this->timezone}\""); // 時區設定

        // unset the data so it can't be dumped
        $this->server_read = '';
        $this->user_read = '';
        $this->pass_read = '';
        $this->database_read = '';
        /* 讀取 ↑ */
    }#-#connect()


#-#############################################
# desc: close the connection
    function close()
    {
        self::$instance = null;
        $this->link_id = null;
        $this->link_id_read = null;

        //if (!@sqlsrv_close($this->link_id)) {
        //$this->oops("Connection close failed.");
        //}
    }#-#close()


#-#############################################
# Desc: escapes characters to be mysql ready
# 轉義 SQL 語句中使用的字元串中的特殊字元
# Param: string
# returns: string
    function escape($string)
    {
        if (get_magic_quotes_runtime()) $string = stripslashes($string);
        return @mysql_real_escape_string($string, $this->link_id);
    }#-#escape()


#-#############################################
# Desc: executes SQL query to an open connection
# Param: (MySQL query) to execute
# returns: (query_id) for fetching results etc
    function query($sql)
    {
        $sql = trim($sql);
        // do query
        try {
            $this->commandtext = $sql;
            if (preg_match("/^select\s/i", $sql)) {
                $this->query_id = $this->link_id_read->query($sql);
            } else {
                $this->query_id = $this->link_id->query($sql);
            }

            if (!$this->query_id) {
                $this->oops("Query fail:");
                return 0;
            }
        } catch (Exception $e) {
            $this->oops("Query fail");
        }
        //$this->affected_rows = @sqlsrv_rows_affected($this->link_id);
        return $this->query_id;
    }#-#query()

    function isExisit($table, $field, $val, $where = '')
    {
        try {
            $this->commandtext = "select $field  from `{$table}` where $field='$val' $where";
            $row = $this->query_first("select $field  from `{$table}` where $field='$val' $where");

            return ($row) ? true : false;
        } catch (Exception $e) {
            $this->oops("Query fail");
        }
    }

//pdo的exec
    function exec($sql)
    {
        $count = 0;
        try {
            $this->commandtext = $sql;

            if (preg_match("/^select\s/i", $sql)) {
                $count = $this->link_id_read->exec($sql);
            } else {
                $count = $this->link_id->exec($sql);
            }



        } catch (Exception $e) {
            $this->oops("Query fail");
        }

        return $count;
    }


    function queryCount($sql, $ary = array())
    {
        try {
            $this->commandtext = 'select count(*) from (' . $sql . ') as count';
            $this->query_id = $this->link_id_read->prepare('select count(*) from (' . $sql . ') as count');
            $this->query_id->execute($ary);

            if ($this->query_id) {
                $count = $this->query_id->fetchColumn();
                $this->free_result($this->query_id);
                return $count;
            } else {
                $this->oops("Query fail:");
                return 0;
            }
        } catch (Exception $e) {
            $this->oops("Query fail");
        }

    }#-#query()
#-#############################################
# desc: fetches and returns results one line at a time
# param: query_id for mysql run. if none specified, last used
# return: (array) fetched record(s)
    function fetch_array()
    {
        if (isset($this->query_id)) {
            $record = $this->query_id->fetch(PDO::FETCH_ASSOC);
        } else {
            $this->oops("Invalid query_id: <b>$this->query_id</b>. Records could not be fetched.");
        }
        return $record;
    }#-#fetch_array()


    function fetch_all_array($sql, $ary = array())
    {
        if (count($ary) > 0) {
            return $this->preparefetch_all_array($sql, $ary);
        } else {
            $query_id = $this->query($sql);
            $out = @$this->query_id->fetchAll(PDO::FETCH_ASSOC);
            $this->free_result($query_id);
            return $out;
        }
    }


#-#############################################
# desc: frees the resultset
# param: query_id for mysql run. if none specified, last used
    function free_result($query_id = -1)
    {

        if ($this->query_id && !@$this->query_id->closeCursor()) {
            $this->oops("Result ID: <b>$this->query_id</b> could not be freed.");
        }
    }#-#free_result()


#-#############################################
# desc: does a query, fetches the first row only, frees resultset
# param: (MySQL query) the query to run on server
# returns: array of fetched results
    function query_first($query_string, $ary = array())
    {

        if (count($ary) > 0) {
            return $this->query_prepare_first($query_string, $ary);
        } else {

            $query_id = $this->query($query_string);
            $out = $this->fetch_array($query_id);
            $this->free_result($query_id);
            return $out;
        }

    }#-#query_first()

    function preparefetch_all_array($query_string, $ary = array())
    {
        try {
            $query_string = trim($query_string);
            $this->commandtext = $query_string;
            if (preg_match("/^select\s/i", $query_string)) {
                $stmt = $this->link_id_read->prepare($query_string);
            } else {
                $stmt = $this->link_id->prepare($query_string);
            }

            $stmt->execute($ary);
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->free_result($stmt);
            return $out;
        } catch (Exception $e) {
            $this->oops('preparefetch_all_array Failed:');
        }
    }

    function query_prepare_first($query_string, $ary = array())
    {
        $query_string = trim($query_string);
        $this->commandtext = $query_string;
        if (preg_match("/^select\s/i", $query_string)) {
            $stmt = $this->link_id_read->prepare($query_string);
        } else {
            $stmt = $this->link_id->prepare($query_string);
        }

        $stmt->execute($ary);
        $out = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->free_result($stmt);

        if ($stmt->errorCode() && $stmt->errorCode() != 00000) {
            $this->oops("execute error : ", $stmt);
        } else {
            return $out;
        }
    }

    function execute($sql, $ary = array())
    {
        $sql = trim($sql);
        $this->commandtext = $sql;
        if (preg_match("/^select\s/i", $sql)) {
            $stmt = $this->link_id_read->prepare($sql);
        } else {
            $stmt = $this->link_id->prepare($sql);
        }


        $stmt->execute($ary);
        if ($stmt->errorCode() && $stmt->errorCode() != 00000) {
            $this->oops("execute error : ", $stmt);
        } else {
            return $stmt->rowCount();
        }

    }

#-#############################################
# desc: 更新Query
# param: table (no prefix), assoc array with data (doesn't need escaped), where condition
# returns: (query_id) for fetching results etc
    function query_update($table, $data, $where = '1', $wheredata = array())
    {
        $q = "UPDATE `" . $this->pre . $table . "` SET ";
        $ary = array();
        foreach ($data as $key => $val) {
            if (strtolower($val) == 'null') $q .= "`$key` = NULL, ";
            elseif (strtolower($val) == 'now()') $q .= "`$key` = NOW(), ";
            else {
                $q .= "`$key`=:" . $key . ", ";
                $ary[':' . $key] = $val;
            }
        }


        $q = rtrim($q, ', ') . ' WHERE ' . $where . ';';
        if (count($wheredata) > 0) {
            $ary = array_merge($ary, $wheredata);
        }

        return $this->execute($q, $ary);

    }#-#query_update()


#-#############################################
# desc: 將陣列內容新增到table
# param: 陣列KEY值是欄位名稱,陣列元素是內容
# returns: 回傳最後新增的自動編號的id
    function query_insert($table, $data)
    {
        $q = "INSERT INTO `" . $this->pre . $table . "` ";
        $v = '';
        $n = '';
        $ary = array();
        foreach ($data as $key => $val) {
            $n .= "`$key`, ";
            if (strtolower($val) == 'null') $v .= "NULL, ";
            elseif (strtolower($val) == 'now()') $v .= "NOW(), ";
            elseif (strtolower($val) == 'uuid()') $v .= "uuid(), ";
            else {
                $v .= ":" . $key . ", ";
                $ary[':' . $key] = $val;
            };
        }

        $q .= "(" . rtrim($n, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";

        $stmt = $this->link_id->prepare($q);
        $stmt->execute($ary);
        if ($stmt->errorCode() && $stmt->errorCode() != 00000) {
            $this->oops("insert error : ", $stmt);
        } else {
            return $this->link_id->lastInsertId();
        }


    }#-#query_insert()

    /**
     * 一次新增多筆
     * @param $table : table名稱
     * @param $data : 欄位值 e.g. [0=>['id'=>1,'name'=>'name1'], 1=>['id'=>2,'name'=>'name2']]
     * @return mixed
     * @throws Exception
     */
    function query_insert_multi($table, $data)
    {
        if(count($data)>0) {
            $q = "INSERT INTO `" . $this->pre . $table . "` ";
            $n = '';
            $ary = array();


            $v_ary = array();
            foreach ($data as $datakey => $row) {
                $v = '';
                foreach ($row as $key => $val) {
                    if ($datakey === 0) $n .= "`$key`, ";
                    if (strtolower($val) == 'null') $v .= "NULL, ";
                    elseif (strtolower($val) == 'now()') $v .= "NOW(), ";
                    else {
                        $v .= ":" . $key . $datakey . ", ";
                        $ary[':' . $key . $datakey] = $val;
                    };
                }
                $v_ary[] = "(" . rtrim($v, ', ') . ")";
            }
            $q .= "(" . rtrim($n, ', ') . ") VALUES " . implode(',', $v_ary);


            $stmt = $this->link_id->prepare($q);
            $stmt->execute($ary);
            if ($stmt->errorCode() && $stmt->errorCode() != 00000) {
                $this->oops("insert error : ", $stmt);
            } else {
                return $this->link_id->lastInsertId();
            }
        }

    }#-#query_insert_multi()

    function query_insert_update($table, $data, $dataupdate, $muli = false)
    {//增加ON DUPLICATE KEY UPDATE(表格名,資料ary(需含pk或uniqune),若重複則更新的資料ary)
        $q = "INSERT INTO `" . $this->pre . $table . "` ";
        $v = '';
        $n = '';
        $ary = array();
        if (!$muli) {
            foreach ($data as $key => $val) {
                $n .= "`$key`, ";
                if (strtolower($val) == 'null') $v .= "NULL, ";
                elseif (strtolower($val) == 'now()') $v .= "NOW(), ";
                else {
                    $v .= ":" . $key . ", ";
                    $ary[':' . $key] = $val;
                };
            }
            $q .= "(" . rtrim($n, ', ') . ") VALUES (" . rtrim($v, ', ') . ")";
        } else {
            $v_ary = array();
            foreach ($data as $datakey => $row) {
                $v = '';
                foreach ($row as $key => $val) {
                    if ($datakey === 0) $n .= "`$key`, ";
                    if (strtolower($val) == 'null') $v .= "NULL, ";
                    elseif (strtolower($val) == 'now()') $v .= "NOW(), ";
                    else {
                        $v .= ":" . $key . $datakey . ", ";
                        $ary[':' . $key . $datakey] = $val;
                    };
                }
                $v_ary[] = "(" . rtrim($v, ', ') . ")";
            }
            $q .= "(" . rtrim($n, ', ') . ") VALUES " . implode(',', $v_ary);
        }


        $q .= " ON DUPLICATE KEY UPDATE ";
        foreach ($dataupdate as $key => $val) {
            $v = '';
            $n = '';
            $n = " `$key`, ";
            if (strtolower($val) == 'null') $v = "NULL, ";
            elseif (strtolower($val) == 'now()') $v = "NOW(), ";
            else {
                $v = ":" . $key . "_update" . ", ";
                $ary[':' . $key . "_update"] = $val;
            };

            $q .= rtrim($n, ', ') . "=" . rtrim($v, ', ') . ",";
        }
        $q = substr($q, 0, -1);
        $q .= ";";
        $stmt = $this->link_id->prepare($q);
        $stmt->execute($ary);
        if ($stmt->errorCode() && $stmt->errorCode() != 00000) {
            $this->oops("insert error : ", $stmt);
        } else {
            return $this->link_id->lastInsertId();
        }
    }#-#query_insert()


#-#############################################
# desc: throw an error message
# param: [optional] any custom error to display
    function oops($msg = '', $_error = null)
    {
        if ($this->debug == true) {
            if ($_error == null) {
                $_error = $this->link_id;
            }
            $error = $_error->errorInfo();
            $this->error = $error[0] . $error[2];
            $this->errno = $error[1];

            throw new Exception('DB:' . $msg . '<br>ERROR' . $this->error . ($this->debugsql == true ? 'Query:' . $this->commandtext : ''));
        }

    }#-#oops()

    function closeAutoCommit()
    {
        $this->query("SET AUTOCOMMIT=0");
    }

    function begin()
    {
        $this->query("SET AUTOCOMMIT=0");
        $this->query("BEGIN");
    }

    function commit()
    {
        $this->query("COMMIT");
        $this->query("SET AUTOCOMMIT=1");
    }

    function rollback()
    {
        $this->query("ROLLBACK");
        $this->query("SET AUTOCOMMIT=1");
    }

}//CLASS Database
###################################################################################################

?>
