<?php
class class_twzipcode {
    public static function getData() {
        $jsonData = file_get_contents(ROOT_DIR . '_taiwan_area.json');
        return json_decode($jsonData, 1);
    }

    public static function getOptionAry() {
        $data = self::getData();

        $result = [];
        foreach ($data as $key => $val) {
            $result[] = [
                'label' => $key,
                'value' => $key,
                'children' => array_map(function ($_key) {
                    return [
                        'label' => $_key,
                        'value' => $_key,
                    ];
                }, array_keys($val))
            ];
        }
        return $result;
    }

    public static function validate($cityName, $areaName = '') {
        $data = self::getData();
        $city = $data[$cityName] ?? null;
        if (!$city) {
            return false;
        }
        if (empty($areaName)) { // 不檢查鄉鎮市區
            return true;
        }
        $area = $city[$areaName] ?? null;
        return $area ? true : false;
    }
}

?>
