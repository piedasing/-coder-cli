<?php
/**
 * 產生uuid
 * 
 */
class class_uuid
{

    public static function getSno($table, $colname = 'sno', $charLength = 8, $charRange = 'default')
    {

        $db = Database::DB();

        $uuid = self::generateUUID($charLength, $charRange);

        $row = $db->query_first(
            "select `{$colname}` from `{$table}` where `{$colname}` = :uuid limit 1 ", array(':uuid' => $uuid)
        );
        if (!$row) {
            return $uuid;
        }
        return self::getSno($table, $colname, $charLength, $charRange);
    }

    public static function generateUUID($length = 8, $charRange = 'default') 
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            switch ($charRange) {
                case 'number': {
                    $random .= rand(0, 9);
                    break;
                }
                case 'default':
                default: {
                    $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('A'), ord('Z')));
                    break;
                }
            }
        }
        return $random;
    }
    
}
