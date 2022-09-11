<?php

use Illuminate\Contracts\Support\Arrayable;

function array_column_default(array $array, string | int $key = '', $defaultValue = null)
{

    if (!is_array($array)) {
        throw (new ErrorException('First parameters requires an array but passing ' . typeof($array)));
    }
    if (empty($array[$key])) {
        return $defaultValue;
    } else {
        return $array[$key];
    }
}
