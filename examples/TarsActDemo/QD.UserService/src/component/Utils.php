<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/17
 * Time: 17:26
 */

namespace Server\component;


class Utils
{
    public static function objToArrayForTars($obj)
    {
        $data = is_array($obj) ? $obj : get_object_vars($obj);

        foreach ($data as $key => $value) {
            if ($key === '__fields' || $key === '__typeName') {
                unset($data[$key]);
                continue;
            }

            if ($value === null) {
                unset($data[$key]);
                continue;
            }

            if (is_array($value) || is_object($value)) {
                $data[$key] = self::objToArrayForTars($value);
            }
        }

        return $data;
    }
}
