<?php
include('_config.php');

$type = get('type', 1);
$sessionName = $incary_captcha_types[$type] ?? '';
if (empty($sessionName)) {
    die('發生錯誤');
}

if (!isset($_SESSION)) {
    session_start();
}
$img_height = 50; // 圖形高度
$img_width = 114; // 圖形寬度

$num = "";        // rand後所存的地方
$num_max = 5;     // 產生4個驗證碼
for ($i = 0; $i < $num_max; $i++) {
    $num .= rand(0, 9);
}

$_SESSION[$sessionName] = $num; // 將產生的驗證碼寫入到session

// 創造圖片，定義圖形和文字顏色
Header("Content-type: image/PNG");
srand((float) microtime() * 1000000);
$im = @imagecreatefrompng(ROOT_DIR . "images/validate.png");
$black = ImageColorAllocate($im, 255, 255, 255); // (0,0,0)文字為白色
$gray = ImageColorAllocate($im, 255, 0, 0);      // (200,200,200)背景是灰色

// 將數字隨機顯示在圖形上,文字的位置都按一定波動範圍隨機生成
$strx = rand(5, 10);
$font = realpath(ROOT_DIR . "fonts/comic.ttf");
for ($i = 0; $i < $num_max; $i++) {
    $strpos = rand(30, 40);
    $fontsize = rand(18, 25);
    imagettftext($im, $fontsize, 10, $strx, $strpos,  $black, $font, substr($num, $i, 1));
    $strx += rand(15, 23);
}
ImagePNG($im);
ImageDestroy($im);
