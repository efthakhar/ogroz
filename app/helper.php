<?php


if (! function_exists('getOption')) {

    function getOption($key, $default = '')
    {
        $value = \App\Models\Setting\Option::where('key', $key)->value('value');
        return json_decode($value ?? $default, true);
    }
}

if (! function_exists('setOption')) {

    function setOption($key, $value, $default = '')
    {
        $option =  \App\Models\Setting\Option::updateOrCreate(
            ['key' =>  $key],
            ['value' => json_encode($value ?? $default)]
        );

        return json_decode($option->value);
    }
}
