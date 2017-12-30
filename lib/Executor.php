<?php

namespace Lib;

class Executor
{
    public static function execute(){
        $postData = json_decode(file_get_contents('php://input'), true);
        if(isset($postData['code'])){
            $code = $postData['code'];
            $code = self::sanitize($code);

            ob_start();
            eval($code);

            echo ob_get_clean();
            die;
        }
    }

    public static function sanitize($code){
        if (get_magic_quotes_gpc()) {
            $code = stripslashes($code);
        }
        $code = preg_replace('{^\s*<\?(php)?\s*}i', '', $code);
        return $code;
    }
}