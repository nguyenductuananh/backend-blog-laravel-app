<?php

use Illuminate\Contracts\Support\Arrayable;

function array_column_default(array $array, string $key = '', $defaultValue = null)
{
    if (empty($array[$key])) {
        return $defaultValue;
    } else {
        return $array[$key];
    }
}

function array_except($array, ...$args): array
{
    $flatArray = [];
    foreach ($args as $value) {
        if (gettype($value) !== 'array') {
            $flatArray[] = $value;
        } else {
            $flatArray = array_merge($flatArray, $value);
        }

    }
    return array_diff_key($array, array_flip($flatArray));
}




