<?php


namespace App\Helper\Types;

use ReflectionClass;

class UserReportTypeHelper
{
    public const USER_REPORT_TYPE_MARRIED                               = 1;
    public const USER_REPORT_TYPE_NOT_CORRECT_INFORMATION               = 2;
    public const USER_REPORT_TYPE_CONTAIN_ADVERTISING                   = 3;
    public const USER_REPORT_TYPE_WANT_TO_MONEY                         = 4;
    public const USER_REPORT_TYPE_NOT_CORRECT_PHOTO                     = 5;
    public const USER_REPORT_TYPE_TOO_MANY_PROFILE                      = 6;
    public const USER_REPORT_TYPE_MESSAGE                               = 7;
    public const USER_REPORT_TYPE_PROBLEM_WITH_FACE_TO_FACE             = 8;

    public static function types(): ?array
    {
        $reflection = new ReflectionClass(__CLASS__);
        $constants  = $reflection->getConstants();
        return collect($constants)->mapWithKeys(function ($value, $key) {
            return [$value => __($key)];
        })->toArray();
    }

    /**
     * @param $type
     * @return mixed
     */
    public static function getTypeName($type): mixed
    {
        $types = self::types();
        return $types[$type];
    }

    /**
     * @return string
     */
    public static function stringValueOfTypes(): string
    {
        $types = self::types();
        return implode(',', array_keys($types));
    }

    /**
     * @return array
     */
    public static function arrayOfTypes(): array
    {
        $types = self::types();
        return array_keys($types);
    }
}
