<?php


namespace App\Helper\Types;

use ReflectionClass;

class UserTypeHelper
{
    public const USER_TYPE_MALE_CLIENT          = 1;
    public const USER_TYPE_FEMALE_CLIENT        = 2;
    public const USER_TYPE_ADMIN                = 3;

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
