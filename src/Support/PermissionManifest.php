<?php

namespace Saccharine\BpmnEngine\Support;

class PermissionManifest
{
    protected static array $permissions = [];

    public static function register(array $permissions): void
    {
        static::$permissions = array_merge(static::$permissions, $permissions);
    }

    public static function all(): array
    {
        return static::$permissions;
    }
}