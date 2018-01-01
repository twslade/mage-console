<?php

namespace Lib;

class Executor
{
    public static function execute(){
        $postData = json_decode(file_get_contents('php://input'), true);
        if(isset($postData['code'])){
            if(self::isProjectSelected($postData)){
                $project = new MagentoProject($postData['project']);
                $project->injectMagento(self::getRunCode($postData), self::getRunType($postData));
            }
            $code = $postData['code'];
            $code = self::sanitize($code);

            ob_start();
            eval($code);

            echo ob_get_clean();
            die;
        }
    }

    public static function getRunCode($postData){
        return isset($postData['website']) ? $postData['website'] : '';
    }

    public static function getRunType($postData){
        return isset($postData['website']) ? 'website' : 'store';
    }

    public static function isProjectSelected($postData){
        return isset($postData['project']);
    }

    public static function sanitize($code){
        if (get_magic_quotes_gpc()) {
            $code = stripslashes($code);
        }
        $code = preg_replace('{^\s*<\?(php)?\s*}i', '', $code);
        return $code;
    }
}