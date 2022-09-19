<?php

function convertStringToDateTime(String $timeString, $format = 'Y-m-d')
{
    if (!$timeString) {
        return null;
    }

    return date($format, strtotime($timeString));
}
