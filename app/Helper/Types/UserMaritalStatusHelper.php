<?php


namespace App\Helper\Types;

use ReflectionClass;

class UserMaritalStatusHelper
{
    public const USER_MARITAL_STATUS_TYPE_NEVER_MARRIED       = 1; // HİÇ EVLENMEMİŞ
    public const USER_MARITAL_STATUS_TYPE_SINGLE              = 2; // BEKAR
    public const USER_MARITAL_STATUS_TYPE_DIVORCED            = 3; // BOŞANMIŞ
    public const USER_MARITAL_STATUS_TYPE_DECASE_WIFE         = 4; // EŞİ VEFAT ETMİŞ

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
