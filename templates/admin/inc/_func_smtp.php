<?php

function send_smtp($fr_em, $fr_na, $to_ary, $subject, $msg, $attachment = [], $showerror = true) {
    global $web_domain;
    if ($web_domain == 'localhost' || $web_domain == '127.0.0.1' || $web_domain == '59.126.17.211') {
        return true;
    }

    require_once(ROOT_DIR . 'inc/Classes/PHPMailer-master/PHPMailerAutoload.php');
    mb_internal_encoding('UTF-8');

    $mail = new PHPMailer($showerror);
    //$mail->SMTPDebug = 3;
    $mail->IsSMTP();
    $mail->Host     = $GLOBALS["smtp_host"];  // SMTP servers
    $mail->Port     = $GLOBALS["smtp_port"];  //default is 25, gmail is 465 or 587
    $mail->SMTPAuth = $GLOBALS["smtp_auth"]; // turn on SMTP authentication
    if($GLOBALS["smtp_auth"]){
      $mail->Username = $GLOBALS["smtp_id"];    // SMTP username
      $mail->Password = $GLOBALS["smtp_pw"];    // SMTP password
    }
    if($GLOBALS["smtp_secure"]!=''){
        $mail->SMTPSecure = $GLOBALS["smtp_secure"];
    }
    $mail->Sender = $GLOBALS["sys_email"];

    foreach ($to_ary as $row) {
        if(!empty($row['name']) && !empty($row['email'])){
            $mail->AddAddress($row['email'],$row['name']);
        }
    }
    $mail->SetFrom($fr_em, $fr_na);
    //$mail->AddReplyTo("jyu@aemtechnology.com","AEM");
    //$mail->WordWrap = 50; // set word wrap

    // 電郵內容，以下為發送 HTML 格式的郵件
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->IsHTML(true); // send as HTML
    $mail->Subject = $subject;
    $mail->Body = $msg;

    foreach ($attachment as $row) {
        $filename = $row['path'] . $row['file'];
        if ($filename != '' && is_file($filename)) {
            $mail->AddAttachment($filename);
        }
    }
    //$mail->AltBody = "This is the text-only body";

    $result = $mail->Send();

    $to_emails = array_map(function($item) {
        return $item['email'];
    }, $to_ary);
    class_email_log::save($fr_em, implode(',', $to_emails), $subject, $msg, $result, $mail->ErrorInfo);

    if (!$result && $showerror) { // 失敗
        throw new Exception('寄件失敗!' . $mail->ErrorInfo);
    }
    $mail->ClearAddresses();
    $mail->ClearAttachments();
}

function sendmail($fr_em, $fr_na, $to_em, $to_na, $subject, $msg, $bcc) {
    global $web_domain;
    if ($web_domain == 'localhost' || $web_domain == '127.0.0.1' || $web_domain == '59.126.17.211') {
        return true;
    }

    //if($to_em != '' && IsEmail($to_em))
    if ($to_em != '') {
        // $to_em = 'jessica@coder.com.tw;pieda@coder.com.tw;khai@coder.com.tw';

        $recipient = $to_em;
        $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=\n";
        $mail_headers = "MIME-Version: 1.0\n";
        $mail_headers .= "Content-type: text/html; charset=utf-8\n";
        $from_name = "=?UTF-8?B?" . base64_encode($fr_na) . "?=";
        $mail_headers .= "From: " . $from_name . "<" . $fr_em . ">\n";
        if ($bcc != "") {
            $mail_headers .= "Bcc:" . $bcc . "\n";
        }


        if (mail($recipient, $subject, $msg, $mail_headers)) {
            return true; // Or do something here
        } else {
            return false;
            //die ("無法送出mail!");
        }
    }
}

function sendmail2($fr_em, $fr_na, $to_em, $to_na, $subject, $msg, $bcc, $path = '', $filename = '', $filename_new = '') {
    global $web_domain;
    if ($web_domain == 'localhost' || $web_domain == '127.0.0.1' || $web_domain == '59.126.17.211') {
        return true;
    }

    $file = $path . $filename;
    $uid = md5(uniqid(time()));

    if ($file != "") {
        $content = file_get_contents($file);
        $content = chunk_split(base64_encode($content));
        $name = basename($file);
    }

    $from_name = "=?UTF-8?B?" . base64_encode($fr_na) . "?=";
    // header
    $header = "From: " . $from_name . " <" . $fr_em . ">\r\n";
    //$header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
    if ($bcc != "") {
        $header .= "Bcc:" . $bcc . "\n";
    }

    // message & attachment
    $nmessage = "--" . $uid . "\r\n";
    $nmessage .= "Content-type:text/html; charset=UTF-8 \r\n";
    $nmessage .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $nmessage .= $msg . "\r\n\r\n";
    $nmessage .= "--" . $uid . "\r\n";

    if ($file != "") {
        $nmessage .= "Content-Type: application/octet-stream; name=\"" . $filename_new . "\"\r\n";
        $nmessage .= "Content-Transfer-Encoding: base64\r\n";
        $nmessage .= "Content-Disposition: attachment; filename=\"" . $filename_new . "\"\r\n\r\n";
        $nmessage .= $content . "\r\n\r\n";
        $nmessage .= "--" . $uid . "--";
    }

    $result = mail($to_em, $subject, $nmessage, $header);
    class_email_log::save($fr_em, $to_em, $subject, $nmessage, $result);

    return $result;
}

// check an email address is possibly valid
function isValidEmail($address) {
  return preg_match('/^[a-z0-9.+_-]+@([a-z0-9-]+.)+[a-z]+$/i', $address);
}

function IsEmail($email) {
  if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i", $email)) {
      return true;
  } else {
      return false;
  }
}
?>
