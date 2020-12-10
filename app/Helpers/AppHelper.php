<?php

namespace App\Helpers;

use App\Config;

class AppHelper
{
    public static function instance()
    {
        return new AppHelper();
    }

    public static function getConfig($key, $defaultValue){
        $config = Config::where('key', $key);
        if ($config->exists())
            $value = $config->first()->value;
        else
            $value = $defaultValue;

        if (is_numeric($value))
            return intval($value);
        return $value;
    }
}