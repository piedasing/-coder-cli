<?php
class coderAdmin
{
    public static $admin_code = 1099511627775;
    public static $Auth;
    static function init()
    {
        self::$Auth = array(
            'auth' => array('key' => 1, 'name' => '系統管理', 'icon' => 'icon-lock', 'auth' => 30, 'list' => array(
                'admin' => array('key' => 1, 'name' => '系統管理者', 'icon' => 'icon-lock', 'path' => 'admin/index.php', 'auth' => 30),
                'auth_rules' => array('key' => 2, 'name' => '權限管理', 'icon' => 'icon-lock', 'path' => 'auth_rules/index.php', 'auth' => 30),
                'adminlog' => array('key' => 3, 'name' => '歷程記錄', 'icon' => 'icon-lock', 'path' => 'adminlog/index.php', 'auth' => 2),
            )),
        );

        if (self::isGodAdmin([])) {
        }
    }

    public static function Auth($_key)
    {
        if (isset(self::$Auth[$_key])) {
            $auth = self::$Auth[$_key];
            $auth['main_key'] = $auth['key'];
            $auth['fun_key'] = 0;
            return $auth;
        } else {
            foreach (self::$Auth as $key => $item) {
                if (isset($item['list']) && array_key_exists($_key, $item['list'])) {
                    $auth = $item['list'][$_key];
                    $auth['main_key'] = $item['key'];
                    $auth['fun_key'] = $auth['key'];
                    return $auth;
                }
            }

            die('權限錯誤~');
        }
    }


    /* 判斷是否超過登出時間 */
    public static function loginout_time()
    {
        global $incary_loginouttime;
        if (isset($_SESSION['manage_loginuser'])) {
            $user = unserialize($_SESSION['manage_loginuser']);
            $minute = $incary_loginouttime[$user['loginout_time']]['minute'];
            //print_r($user);
            if (date('Y-m-d H:i:s') >= date('Y-m-d H:i:s', strtotime($user['chk_time'] . "+$minute minute"))) //超過時間 登出
            {
                self::loginOut();
                self::showLoginPage();
            } else {
                $user['chk_time'] = date('Y-m-d H:i:s');
                self::setUser($user);
            }
        }
    }

    public static function vaild($auth, $log_type = '', $throwException = false)
    {
        $log_key = $log_type != '' ? coderAdminLog::getActionKey($log_type) : 0;

        if (!self::isAuth($auth, $log_key)) {
            $msg = '您未擁有操作此項功能的權限,請聯絡系統管理員。';
            if ($throwException) {
                throw new Exception($msg);
            } else {
                self::drawBody('授權失敗!', $msg);
            }
        }
    }

    public static function isAuth($auth, $log_key = 0)
    {
        $user = self::getUser();
        if (self::isInAuth($user['auth'], $auth['main_key'], $auth['fun_key'], $log_key)) {
            return true;
        }
        return false;
    }

    /*
    判斷是否有權限
    $ary_auth user的權限列表
    $main_key 主功能
    $fun_key  副功能
    $log_key  操作類型,對應coderAdminLog中的$active
    */
    public static function isInAuth($ary_auth, $main_key, $fun_key = 0, $log_key = 0, $log_note = "")
    {
        if ($ary_auth === self::$admin_code) {
            return true;
        }
        foreach ($ary_auth as $key => $item) {
            if ($item['main_key'] == $main_key && ($fun_key == 0 || $fun_key == $item['fun_key']) && ($log_key == 0 || $item['auth'] & $log_key)) {
                return true;
            }
        }
        return false;
    }

    public static function getAuthAry()
    {
        $ary = array();
        foreach (self::$Auth as $item) {
            $ary[] = self::getReturnElement($item);
        }
        return $ary;
    }


    public static function getRowAuth_name($main_key, $fun_key)
    {
        $name = "";
        foreach (self::$Auth as $val) {
            if ($val['key'] != $main_key) {
                continue;
            }

            if (array_key_exists('list', $val)) {
                foreach ($val['list'] as $item) {
                    if ($item['key'] == $fun_key) {
                        $name = $item['name'];
                    }
                }
            }
        }
        return ($name) ? $name : '登入帳號';
    }


    public static function getAuthListAryByInt($r_id)
    {
        global $db;
        $ary = array();
        $colname = coderDBConf::$col_rules_auth;
        $sql = "SELECT `{$colname['main_key']}` as main_key,`{$colname['fun_key']}` as fun_key,`{$colname['auth']}` as auth FROM " . coderDBConf::$rules_auth . " WHERE `{$colname['r_id']}`=" . $r_id;
        $rows = $db->fetch_all_array($sql);
        $item = array();


        $auth = self::getAuth($r_id);

        foreach (self::$Auth as $key => $item) {
            $main_key = $item['key'];

            if (!self::isInAuth($auth, $main_key)) {
                continue;
            }

            if (array_key_exists('list', $item)) {
                foreach ($item['list'] as $subkey => $subitem) {
                    if (self::isInAuth($auth, $main_key, $subitem["key"])) {
                        $item['name'] = $subitem['name'];
                        $item['ck_auth'] = (self::getRowAuth($auth, $main_key, $subitem["key"]) == $subitem["auth"]) ? true : false;
                        $ary[] = self::getReturnElement($item);
                    }
                }
            }
        }

        return $ary;
    }

    private static function getRowAuth($ary_auth, $main_key, $fun_key)
    {
        foreach ($ary_auth as $key => $item) {
            if ($item['main_key'] == $main_key && $fun_key == $item['fun_key']) {
                return $item['auth'];
            }
        }
    }

    private static function getReturnElement($item)
    {
        return array('name' => $item['name'], 'ck_auth' => $item['ck_auth']);
    }

    public static function change_admin_data($username)
    {
        global $db, $admin_path_admin;
        $sql = "SELECT username,name,id,mid,ispublic,pic,r_id,loginout_time FROM " . coderDBConf::$admin . " WHERE username=:username";
        $row = $db->query_first($sql, array(':username' => $username));
        if ($row) {
            self::setUser(self::getUserAry($row));
        }
    }

    public static function loginOut()
    {
        unCookie('mid');
        unset($_SESSION['manage_loginuser']);
    }
    public static function pwHash($str)
    {
        return hash('sha512', $str);
    }

    public static function login($username, $password, $remember_me = '')
    {
        $db = Database::DB();
        $password = self::pwHash($password);
        $sql = "SELECT username,name,id,mid,ispublic,pic,r_id,loginout_time FROM " . coderDBConf::$admin . " WHERE username=:username and password=:password";
        $row = $db->query_first($sql, array(':username' => $username, ':password' => $password));
        if (!$row) {
            coderAdminLog::insert($username, 0, 0, 1, 'admin登入失敗-帳密不正確');
            throw new Exception('帳號或密碼不正確!');
        } else if ($row['ispublic'] != 1) {
            coderAdminLog::insert($username, 0, 0, 1, 'admin登入失敗-己被停權');
            throw new Exception('此帳號己被停權!');
        } else {
            $mid_sessionid = substr(substr($row['mid'], 0, 32) . time() . session_id(), 0, 65);
            $db->execute("update " . coderDBConf::$admin . " set logintime=:logintime,ip=:ip,mid=:mid where username=:username ", array(':logintime' => request_cd(), ':ip' => request_ip(), ':username' => $username, 'mid' => $mid_sessionid));

            if ($remember_me === 1) {
                saveCookieHour('mid', $mid_sessionid, 24 * 7);
            } else {
                unCookie('mid');
            }
            self::setUser(self::getUserAry($row));
            coderAdminLog::insert($username, 0, 0, 1, 'admin登入成功');
        }
    }

    private static function getUserAry($row)
    {
        global $admin_path_admin;
        $auth_ary = self::getAuth($row['r_id']);
        $user = array(
            'username' => $row['username'],
            'name' => $row['name'],
            'pic' => $admin_path_admin . 's' . $row['pic'],
            'time' => datetime('A h:i'),
            'system' => ($auth_ary === self::$admin_code ? 'supermanage' : ''),
            'auth' => $auth_ary,
            'loginout_time' => $row['loginout_time'],
            'chk_time' => datetime('Y-m-d H:i:s')
        );
        return $user;
    }
    //取得user的權限列表
    public static function getAuth($r_id)
    {
        $table = coderDBConf::$rules;
        $col = coderDBConf::$col_rules;
        $table_auth = coderDBConf::$rules_auth;
        $col_auth = coderDBConf::$col_rules_auth;
        $db = Database::DB();
        $authdata = $db->fetch_all_array(
            "
            select ra.{$col_auth['main_key']} as main_key, ra.{$col_auth['fun_key']} as fun_key, ra.{$col_auth['auth']} as auth , r.{$col['superadmin']}
            FROM $table r 
            LEFT JOIN $table_auth ra ON r.{$col['id']} = ra.{$col_auth['r_id']}
            where r.{$col['id']}=:id",
            array(':id' => $r_id)
        );

        return isset($authdata[0][$col['superadmin']]) && intval($authdata[0][$col['superadmin']]) === 1 ? self::$admin_code : $authdata;
    }

    // public static function isAdmin(){
    // 	$user=self::getUser();
    // 	return $user['autn']==self::$admin_code;
    // }

    public static function setUser($ary)
    {
        if (!is_array($ary)) {
            throw new Exception("USER格式不正確,儲存錯誤!");
        } else {
            $_SESSION['manage_loginuser'] = serialize($ary);
        }
    }

    public static function getUser_cookie()
    {
        global $db, $admin_path_admin;
        $mid = getCookie('mid');
        if ($mid != '') {
            $sql = "SELECT username,name,id,mid,ispublic,pic,r_id,loginout_time FROM " . coderDBConf::$admin . " WHERE mid=:mid";
            $row = $db->query_first($sql, array(':mid' => $mid));
            if ($row && $row['ispublic'] == 1) {
                self::setUser(self::getUserAry($row));
                return true;
            }
        }
        return false;
    }

    public static function getUser()
    {
        if (!isset($_SESSION['manage_loginuser']) || $_SESSION['manage_loginuser'] == null) {
            if (self::getUser_cookie()) {
                return self::getUser();
            } else {
                self::showLoginPage();
            }
        } else {
            $user = unserialize($_SESSION['manage_loginuser']);
            if (!is_array($user)) {
                self::showLoginPage();
            } else if (isset($user['guest'])) {
                self::showLoginPage();
            } else {
                return $user;
            }
        }
    }

    /**
     * 是否為至高無上權限
     * @param
     *
     */
    public static function isGodAdmin($usernames = [])
    {
        $user = self::getUser();
        return $user['username'] == 'admin' || in_array($user['username'], $usernames);
    }

    public static function sayHello()
    {
        $talktype = rand(0, 1);
        //一般問候
        if ($talktype == 0) {
            $ary_talk = array('歡迎登入。', '感謝您使用本系統', 'Hello :)', ' 阿囉哈', '記得要微笑 : )', '每隔30分鐘記得喝水,出去活動一下。', '來杯咖啡嗎?', ' hihi!!');
        } else {
            //依時間問候
            $hour = datetime('H');
            if ($hour > 5 && $hour < 9) { //早上5點到9點登入
                $ary_talk = array('早安!', '早起的鳥兒有蟲吃!', '您知道嗎?清晨的空氣特別新鮮', '您今天真早', '您早', '來杯咖啡嗎?', '記得吃早餐!', '今天真是個美好的一天,不是嗎?', '一日之計在於晨');
            } else if ($hour > 9 && $hour < 11) {
                $ary_talk = array('您今天加油了嗎?', ' 每天告訴自己一次,我真的很不錯', '抱最大的希望，為最大的努力，做最壞的打算', '喝口水吧', '每天都是一年中最美好的日子');
            } else if ($hour > 10 && $hour < 14) {
                $ary_talk = array('吃過飯了嗎?', ' 記得多吃點蔬菜水果喔~ ', '來根香蕉吧!', '多吃香蕉有益健康');
            } else if ($hour > 13 && $hour < 17) {
                $ary_talk = array('來杯下午茶吧。', '每一件事都要用多方面的角度來看它', '美好的生命應該充滿期待、驚喜和感激。', '天才是百分之一的靈感加上百分之九十九的努力', '您累了嗎? 喝杯水吧休息一下吧。', '肚子餓的話,吃些點心吧。');
            } else if ($hour > 16 && $hour < 20) {
                $ary_talk = array('今天沒什麼事就早點下班吧', '記得吃晚餐', '晚餐不要吃太多,身體才健康', '想像力比知識更重要', '晚餐請不要吃太多');
            } else if ($hour > 19 && $hour < 23) {
                $ary_talk = array('您辛苦了', '別忙到太晚', '加油加油!', '如果你曾歌頌黎明，那麼也請你擁抱黑夜', '吃晚餐了嗎?', '沒什麼事就早點休息吧', '研究指出,加班會降低工作效率', '千萬別吃宵夜', '睡前別喝太多水,會水腄');
            } else if ($hour > 22 && $hour < 02) {
                $ary_talk = array('請去休息吧!', '研究指出,加班會降低工作效率', '您睡不著嗎?', '感謝每盞亮著的燈,沒留下你一個人', '經驗是由痛苦中粹取出來的', '天才是百分之一的靈感加上百分之九十九的努力');
            } else {
                $ary_talk = array('................', '唔....', '嗯.....', '現在是下班時間吧?', '天才是百分之一的靈感加上百分之九十九的努力', 'XD', '囧');
            }
            echo $hour;
        }

        return '您好 ' . $ary_talk[rand(0, count($ary_talk) - 1)];
    }

    public static function showLoginPage()
    {
        self::drawBody('登入逾時', '您尚未登入或超過登入期限<br>為了確保安全性<br>請按下方連結重新登入。');
    }

    public static function drawMenu()
    {
        global $manage_path, $fun_auth_key;
        $user = self::getUser();

        $auth = $user['auth'];
        //$query_str=$_SERVER['QUERY_STRING'];
        //$query_str=$query_str!='' ? '?'.$query_str : '';

        //$pagename=realpath(request_basename()).$query_str;
        foreach (self::$Auth as $key => $item) {
            if (isset($item['show']) && !$item['show']) {
                continue;
            }

            $main_key = $item['key'];
            if (!self::isInAuth($auth, $main_key)) {
                continue;
            }

            $classname = '';
            $str = '<a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="' . $item['icon'] . '"></i>
                        <span>' . $item['name'] . '</span>
                        <b class="arrow icon-angle-right"></b>
                   </a>';


            if (array_key_exists('list', $item)) {
                $_str = "";
                foreach ($item['list'] as $subkey => $subitem) {
                    if (isset($subitem['show']) && !$subitem['show']) {
                        continue;
                    }
                    if (self::isInAuth($auth, $main_key, $subitem["key"])) {
                        $path = $subitem['path'];
                        $index = strpos($path, '?');
                        if ($index > 0) {
                            $_subitem = realpath(substr($path, 0, $index)) . substr($path, $index);
                        } else {
                            $_subitem = realpath($path);
                        }

                        if ($fun_auth_key === $subkey) { //realpath(request_basename()) == $_subitem || $pagename==$subkey
                            $classname = ' class="active" ';
                            $_str .= '<li ' . $classname . '><a href="' . $manage_path . $path . '" >' . $subitem['name'] . '</a></li>';
                        } else {
                            $_str .= '<li ><a href="' . $manage_path . $path . '" >' . $subitem['name'] . '</a></li>';
                        }
                    }
                }
                if ($_str != "") {
                    $str .= '<ul class="submenu">' . $_str . '</ul>';
                }
            }

            echo '<li ' . $classname . '>' . $str . '</li>';
        }
    }

    public static function array_diff_assoc_auth($array1, $array2)
    {
        $ary2 = array();
        foreach ($array2 as $val) {
            $ary2[$val['key']][] = $val['name'];
        }
        foreach ($array1 as $key => $value) {
            if (!isset($ary2[$value['key']]) || !in_array($value['name'], $ary2[$value['key']])) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? 0 : $difference;
    }


    public static function updateAuth($r_id, $ary_fun_auth)
    {
        global $adminuser;
        $db = Database::DB();
        $table = coderDBConf::$rules_auth;
        $col = coderDBConf::$col_rules_auth;
        $db->execute('delete from ' . $table . ' where ' . $col['r_id'] . '=:r_id', array(':r_id' => $r_id));


        $auth_item = array();
        $updata = array();
        $dataupdate = array();
        foreach ($ary_fun_auth as $key => $value) {
            $item = explode('_', $value);
            $main_key = $item[0];
            $fun_key = $item[1];
            $auth = $item[2];
            if (!isset($auth_item[$main_key . '_' . $fun_key])) {
                $auth_item[$main_key . '_' . $fun_key] = 0;
            }

            $auth_item[$main_key . '_' . $fun_key] += $auth;
        }

        foreach ($auth_item as $key => $value) {
            $item = explode('_', $key);

            $updata[] = array(
                $col['main_key'] => $item[0],
                $col['fun_key'] => $item[1],
                $col['auth'] => $value,
                $col['r_id'] => $r_id,
                $col['admin'] => $adminuser['username'],
                $col['updatetime'] => datetime(),
                $col['createtime'] => datetime(),
            );
            //$db->execute('replace into '.$table.'('.$col['main_key'].', '.$col['fun_key'].','.$col['auth'].','.$col['r_id'].','.$col['updatetime'].') values(:main_key,:fun_key,:auth,:r_id,NOW());',array(':r_id'=>$r_id,':main_key'=>$item[0],':fun_key'=>$item[1],':auth'=>$value));
        }
        //$dataupdate[$col['updatetime']]=datetime();
        //$dataupdate[$col['admin']]=$adminuser['username'];

        foreach ($updata as $_key => $_val) {
            $db->query_insert($table, $_val);
        }
        /*if(count($updata) > 0)
        {
            $db->query_insert_update($table, $updata , $dataupdate,true);
        }*/
        return;
    }

    //取得角色的權限列表
    public static function getRulesAuth($r_id)
    {
        $table = coderDBConf::$rules;
        $col = coderDBConf::$col_rules;
        $table_auth = coderDBConf::$rules_auth;
        $col_auth = coderDBConf::$col_rules_auth;
        $db = Database::DB();
        $authdata = $db->fetch_all_array(
            "select ra.{$col_auth['main_key']} as main_key,
                    ra.{$col_auth['fun_key']} as fun_key,
                    ra.{$col_auth['auth']} as auth
            FROM $table r 
            LEFT JOIN $table_auth ra ON r.{$col['id']} = ra.{$col_auth['r_id']}
            where r.{$col['id']}=:id",
            array(':id' => $r_id)
        );

        return isset($authdata[0][$col['superadmin']]) && $authdata[0][$col['superadmin']] === '1' ? self::$admin_code : $authdata;
    }

    public static function drawAuthForm($r_id = '')
    {
        $user_auth = array();
        if ($r_id != '') {
            $user_auth = self::getRulesAuth($r_id);
        }
        $str = '<table collspan="10" class="table">';
        $str .= "<tr><th width='55'>全選</th><th width='150'>項目</th>";
        $ary_action = coderAdminLog::$action;
        // login不需要授權
        unset($ary_action['login']);
        //開始HEADER
        foreach ($ary_action as $key => $act_item) {
            $str .= "<th>{$act_item["name"]}</th>";
        }
        $str .= "</tr>";
        $ary_auth = self::$Auth;
        //每個項目
        foreach ($ary_auth as $key => $auth_item) {
            $main_tab = "";
            $sub_str = "";
            if (isset($auth_item["list"])) {
                foreach ($auth_item["list"] as $skey => $auth_sitem) {
                    $sub_str .= self::getAuthFormTrStr($auth_sitem, $user_auth, $ary_action, $auth_item['key'], $auth_sitem['key']);
                }
            }
            $str .= self::getAuthFormTrStr($auth_item, $user_auth, $ary_action, $auth_item['key'], 0);
            $str .= $sub_str;
        }
        $str .= "</table>";
        echo $str;
    }
    /*
    回傳畫TR的HTML
    $auth_item 該權限的物件
    $ary_action  該操作的物件
    $main_key 主功能key
    $fun_key  副功能key
    */
    private static function getAuthFormTrStr($auth_item, $user_auth, $ary_action, $main_key, $fun_key)
    {
        $style_class = "main_class_" . $main_key;
        $checkbox_name = '';
        $tr_style = '';
        $tr_class = '';

        $tab = "";
        $tab_end = "";
        //主功能
        if ($fun_key == 0) {
            $tab = (isset($auth_item['list'])) ? '<a class="tab tabclose icon-expand-alt" href="javascript:void(0)"></a>' : '';
            $checkbox_name = 'main_auth';
            $tr_class = 'maintr';
        } //子功能
        else {
            $tab = '<div class="col-sm-3">└─&gt;</div><div class="col-sm-9">';
            $tab_end = '</div>';
            $style_class .= " fun_class_" . $fun_key;
            $checkbox_name = 'fun_auth';
            $tr_style = "display:none;";
            $tr_class = 'funtr';
        }

        $str = "<tr class='{$tr_class}' style='{$tr_style}'><td align='center'><input type='checkbox' class='checkall'></td><td>{$tab}{$auth_item['name']}{$tab_end}</td>";
        foreach ($ary_action as $key => $act_item) {
            $checked = '';
            //有子功能的check判斷
            if ($fun_key == 0 && isset($auth_item['list'])) {
                $i = 0;
                $first_chk = true;
                foreach ($auth_item['list'] as $key => $value) {
                    //如果該項目擁有這個權限才繼續
                    if ($value['auth'] & $act_item['key']) {
                        if (self::isInAuth($user_auth, $main_key, $value['key'], $act_item["key"])) {
                            $has_chk = true;
                        } else {
                            $has_chk = false;
                        }

                        if ($i == 0) {
                            $first_chk = $has_chk;
                        } else {
                            if ($first_chk != $has_chk) {
                                break;
                            }
                        }
                        $i++;
                    }
                }
                if ($first_chk == $has_chk && $has_chk == true) {
                    $checked = 'checked';
                }
            } //沒有子功能的的check判斷
            else {
                if (self::isInAuth($user_auth, $main_key, $fun_key, $act_item["key"])) {
                    $checked = 'checked';
                }
            }
            $a_str = ($auth_item["auth"] & $act_item["key"]) ? "<input type='checkbox' class='" . $style_class . "' name='{$checkbox_name}[]' data_action='{$act_item["key"]}' data_main='{$main_key}' data_fun='{$fun_key}' value='{$main_key}_{$fun_key}_{$act_item["key"]}' {$checked}>" : "";
            $str .= "<td align='center'>{$a_str}</td>";
        }
        $str .= "</tr>";
        return $str;
    }
    private static function drawBody($title, $content)
    {
        die('<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
                <meta name="description" content="">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="shortcut icon" href="' . WEB_FAVICON_URL . '">
                <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">
                <!--flaty css styles-->
                <link rel="stylesheet" href="../css/flaty.css">
                <link rel="stylesheet" href="../css/flaty-responsive.css">
            </head>
            <body class="error-page">
            <div class="error-wrapper">
                <img style="width: 100%; max-height: 200px; object-fit: contain; object-position: center;" src="' . WEB_LOGO_URL . '">
                <hr>
                <h5>' . $title . '</h5>
                <div style="font-size: 16px;">
                    錯誤訊息如下：<br>
                    <p style="padding-left: 2em;">' . $content . '</p>
                </div>
                <hr>
                <p class="clearfix">
                    <a href="javascript:void(0)" onclick="window.location.href = document.referrer" class="pull-left">← 回到前一頁</a>
                    <a href="../login.php" class="pull-right"> 回到登入頁</a>
                </p>
                <!--basic scripts-->
                <script src="../assets/jquery/jquery-2.0.3.min.js"></script>
                <script src="assets/bootstrap/js/bootstrap.min.js"></script>
            </body>
        </html>');
    }
}
