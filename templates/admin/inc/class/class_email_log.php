<?php
class class_email_log {
    private static $LINE_NOTIFY_URL = "https://notify-api.line.me/api/notify";
    private static $LINE_NOTIFY_TOKEN = "VYMasmLKCwfXspV5bAW3ZRgB6RSg7D843j4UA9vp8IS";

    private static $IS_TEST = false;

    public static function save($from_email, $to_email, $subject, $body, $mailStatus, $mailErrorMessage = '') {
        $db = Database::DB();
        $table = coderDBConf::$email_log;

        $datetime = datetime('Y-m-d H:i:s');

        $insertedId = $db->query_insert($table, [
            'from_email'=> $from_email,
            'to_email'=> $to_email,
            'subject'=> $subject,
            'body'=> $body,
            'status'=> $mailStatus ? 1 : 0,
            'error_message'=> $mailErrorMessage ?? '',
            'sendtime'=> $datetime
        ]);

        $msg = "";
        if (self::$IS_TEST) {
            $msg .= "\n[測試訊息]";
        }
        $msg .= "\n[Email發送失敗]\nlog_id: {$insertedId}\nfrom_email: {$from_email}\nto_email: {$to_email}\nsubject: {$subject}\nerror: {$mailErrorMessage}";
        if (!$mailStatus || self::$IS_TEST) {
            // self::sendLineNotify($msg);
        }
    }

    private static function sendLineNotify($msg) {
        $header = [
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Bearer " . self::$LINE_NOTIFY_TOKEN
        ];

        $data = [
            'message'=> $msg
        ];

        post_CURL(self::$LINE_NOTIFY_URL, $data, $header);
    }
}

?>
