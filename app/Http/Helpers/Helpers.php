<?php

//namespace App;


namespace App\Http\Helpers;
class Helpers
{
    /**
     * Fetch Cached settings from database
     *
     * @return string
     */


    function getSetting($key)
    {

        $setting = \App\Models\Setting::where('key', '=', $key)->first();

        dd($setting);

        return $settingValue;

    }

}

