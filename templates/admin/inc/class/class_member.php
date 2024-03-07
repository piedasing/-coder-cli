<?php
class class_member
{
    public static function getOptionAry()
    {
        $db = Database::DB();
        $table = coderDBConf::$member;

        $rows = $db->fetch_all_array("SELECT * FROM {$table} ORDER BY id DESC");

        return array_map(
            function ($row) {
                return [
                    'name' => "{$row['name']} ({$row['phone']})",
                    'value' => $row['id'],
                ];
            },
            $rows
        );
    }

    public static function insertOrUpdateMember($data)
    {
        $phone = $data['phone'];
        $phone = str_replace(['+', '-', ' '], ['', '', ''], $phone);
        $phone = preg_replace("/^886/", "0", $phone, 1);
        $phone = preg_replace("/^9[0-9]+/", "0{$phone}", $phone);

        // 會員基本資料
        $data_member = [
            'status' => 1,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $phone,
            // 'gender' => $data_form['gender'],
            // 'birthday' => $data_form['birthday'],
            'city' => $data['city'] ?? '',
            'area' => $data['area'] ?? '',
            // 'super8_id' => $data_form['super8_id'],        
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
        ];

        $db = Database::DB();
        $table_member = coderDBConf::$member;
        $m_id = NULL;
        if (!empty($data_member['phone'])) {
            $phone = $data_member['phone'];

            $row_member = $db->query_prepare_first("SELECT * FROM {$table_member} WHERE phone = :phone", [':phone' => $phone]);
            $m_id = $row_member['id'] ?? '';
            if (!empty($m_id)) {
                unset($data_member['phone']);
                unset($data_member['created_at']);
                $db->query_update($table_member, $data_member, " id = :id", [':id' => $m_id]);
            } else {
                $m_id = $db->query_insert($table_member, $data_member);
            }
        }

        return $m_id;
    }

    public static function chkLineIdExist($line_id)
    {
        if (empty($line_id)) {
            return false;
        }

        $db = Database::DB();
        $table = coderDBConf::$member;

        return $db->query_first("SELECT * FROM {$table} WHERE line_id = :line_id", [':line_id' => $line_id]);
    }

    public static function add($data)
    {
        $db = Database::DB();
        $table = coderDBConf::$member;

        if (!empty($data['phone'])) {
            $phone = $data['phone'];
            $phone = str_replace(['+', '-', ' '], ['', '', ''], $phone);
            $phone = preg_replace("/^886/", "0", $phone, 1);
            $phone = preg_replace("/^9[0-9]+/", "0{$phone}", $phone);
            $data['phone'] = $phone;

            $row_member = $db->query_prepare_first("SELECT * FROM {$table} WHERE phone = :phone", [':phone' => $data['phone']]);
            $m_id = $row_member['id'] ?? '';
            if (!empty($m_id)) {
                unset($data['phone']);
                unset($data['created_at']);
                $db->query_update($table, $data, " id = :id", [':id' => $m_id]);
            }
            return $m_id;
        }

        $m_id = $db->query_insert($table, $data);
        return $m_id;
    }
}
