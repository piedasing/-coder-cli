<?php

function set_emailsample($body) { // 設置email的樣板
    global $webname;
    $html = '';
    $html .= '<!doctype html>
    <html class="no-js" lang="zh-TW">
    <head>
        <style>
            body { color: #454444; font-size: 18px; }
        </style>
    </head>
    <body style="background-color: #e2e3e2; padding: 20px;">
        <div style="width: 740px; margin: 0 auto;">
            <div style="background-color: white; margin-bottom: 20px; padding: 10px;">
                <img style="width: 250px; max-width: 100%; display: block; margin: 0 auto;" src="' . WEB_LOGO_URL . '" alt="' . $webname . '" />
            </div>
            <div style="font-size: 18px; color: #454444; background-color: white; padding: 20px 20px;">
                ' . $body . '
            </div>
        </div>
    </body>
    </html>
    ';

    return $html;
}

function deletefile($pic_old, $pic_new, $file) {
    if ($pic_old != $pic_new) {
        if(is_file($file . $pic_old)) {
            unlink($file.$pic_old);
        }
        if(is_file($file . 's' . $pic_old)) {
            unlink($file . 's' . $pic_old);
        }
    }
}

function get_chinese_weekday($datetime) {
    $weekday  = date('w', strtotime($datetime));
    $weeklist = array('日', '一', '二', '三', '四', '五', '六');
    return '星期' . $weeklist[$weekday];
}

function deldir($dir) {
    //先删除目錄下的文件：
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }

    closedir($dh);
    //删除當前前文件夹：
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}

function deldir_file($path) {
    //如果是目錄則繼續
    if (is_dir($path)) {
        //掃描一個資料夾內的所有資料夾和檔案並返回陣列
        $p = scandir($path);
        foreach ($p as $val) {
            //排除目錄中的.和..
            if ($val != "." && $val != "..") {
                //如果是目錄則遞迴子目錄，繼續操作
                if (is_dir($path . $val)) {
                    //子目錄中操作刪除資料夾和檔案
                    deldir_file($path . $val . '/');
                    //目錄清空後刪除空資料夾
                    //@rmdir($path.$val.'/');
                } else {
                    //如果是檔案直接刪除
                    unlink($path . $val);
                }
            }
        }
    }
}

?>
