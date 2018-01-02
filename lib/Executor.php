<?php

namespace Lib;

class Executor
{

    const EXECUTION_FLAG = 'code';

    const PROJECT_PATH = 'proj';

    public static function getProjectInfo($postData){
        $project = new MagentoProject($postData[ self::PROJECT_PATH ]);
        $project->injectMagento(self::getRunCode($postData), self::getRunType($postData));
        echo json_encode($project->toArray());
        die;
    }

    public static function execute(){
        $postData = json_decode(file_get_contents('php://input'), true);
        if(self::shouldExecuteMagentoCode($postData)){
            self::runMageCode($postData);
        } elseif (self::shouldGetProjectInfo($postData)){
            self::getProjectInfo($postData);
        }
    }

    public static function shouldExecuteMagentoCode($postData){
        return isset($postData[ self::EXECUTION_FLAG ]);
    }

    public static function runMageCode($postData){
        if(self::isProjectSelected($postData)){
            $project = new MagentoProject($postData['project']);
            $project->injectMagento(self::getRunCode($postData), self::getRunType($postData));
        }
        $code = $postData[ self::EXECUTION_FLAG ];
        $code = self::sanitize($code);

        ob_start();
        eval($code);

        echo ob_get_clean();
        die;
    }

    public static function getRunCode($postData){
        return isset($postData['website']) ? $postData['website'] : '';
    }

    public static function getRunType($postData){
        return isset($postData['website']) ? 'website' : 'store';
    }

    public static function isProjectSelected($postData){
        return isset($postData['project']) && $postData['project'] != false;
    }

    public static function sanitize($code){
        if (get_magic_quotes_gpc()) {
            $code = stripslashes($code);
        }
        $code = preg_replace('{^\s*<\?(php)?\s*}i', '', $code);
        return $code;
    }

    /**
     * @param $postData
     * @return bool
     */
    protected static function shouldGetProjectInfo($postData)
    {
        return isset($postData[ self::PROJECT_PATH ]);
    }
}