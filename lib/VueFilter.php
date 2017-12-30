<?php

namespace Lib;

class VueFilter
{
    public static function filter($data){
        return htmlentities(json_encode($data, JSON_HEX_QUOT), ENT_QUOTES);
    }
}