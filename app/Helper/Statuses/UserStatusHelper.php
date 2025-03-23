<?php


namespace App\Helper\Statuses;

use ReflectionClass;

class UserStatusHelper
{
    public const USER_STATUS_INACTIVE = 0;
    public const USER_STATUS_ACTIVE = 1;
    public const USER_STATUS_DELETED = 2;
    public const USER_STATUS_BLOCKED = 3;

    public static function types(): ?array
    {
        try {
            $reflection = new ReflectionClass(__CLASS__);
            $constants  = $reflection->getConstants();
            return collect($constants)->filter(function ($value, $key) {
                return str_contains($key, 'USER_STATUS_') === true;
            })->mapWithKeys(function ($value, $key) {
                return [$value => __($key)];
            })->toArray();
        } catch (\ReflectionException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @param $type
     * @return mixed|null
     */
    public static function getTypeName($type)
    {
        $types = self::types();
        return $types[ $type ];
    }

    /**
     * @return string
     */
    public static function stringValueOfTypes(): string
    {
        $types = self::types();
        return implode(',', array_keys($types));
    }
}
