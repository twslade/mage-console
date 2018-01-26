<?php

namespace Lib;

class Executor
{

    const EXECUTION_FLAG = 'code';

    const PROJECT_PATH = 'proj';

    const DEBUG_FLAG = 'debug';

    const PROJECT_FLAG = 'project';

    const WEBSITE_FLAG = 'website';

    const PRETTY_DUMP_FLAG = 'pretty';

    const AUTOLOAD_FLAG = 'autoload';

    private static function __getProjectInfo($postData){
        $project = new MagentoProject($postData[ self::PROJECT_PATH ]);
        $project->injectMagento(self::__getRunCode($postData), self::__getRunType($postData));
        echo json_encode($project->toArray());
        die;
    }

    public static function execute(){
        $postData = json_decode(file_get_contents('php://input'), true);
        if(self::__shouldExecuteMagentoCode($postData)){
            self::__runMageCode($postData);
        } elseif (self::__shouldGetProjectInfo($postData)){
            self::__getProjectInfo($postData);
        }
    }

    private static function __shouldExecuteMagentoCode($postData){
        return isset($postData[ self::EXECUTION_FLAG ]);
    }

    private static function __runMageCode($postData){
        if(self::__isProjectSelected($postData)){
            $project = new MagentoProject($postData[ self::PROJECT_FLAG ]);
            $project->injectMagento(self::__getRunCode($postData), self::__getRunType($postData));
        }

        $code = self::__prepareCode($postData);

        ob_start();
        eval($code);

        echo ob_get_clean();
        die;
    }

    private static function __prepareCode($postData){
        $code = $postData[ self::EXECUTION_FLAG ];
        $code = self::__sanitize($code);
        $code = self::__injectDebugCode($code, $postData[ self::DEBUG_FLAG ]);
        $code = self::__replaceDumps($code, $postData[ self::PRETTY_DUMP_FLAG ]);
        $code = self::__injectAutoloader($code, $postData[ self::AUTOLOAD_FLAG ]);
        return $code;
    }

    private static function __replaceDumps($code, $pretty){
        if($pretty) {
            $code = 'Kint_Renderer_Rich::$theme = "solarized-dark.css";Kint::$expanded=true;' . $code;
            $code = str_replace(array('print_r', 'var_dump'), 'd', $code);
        }
        return $code;
    }

    private static function __injectAutoloader($code, $autoload){
        if($autoload){
            $code = 'Mage::getConfig()->init()->loadEventObservers("global"); Mage::app()->addEventArea("global"); Mage::dispatchEvent("add_spl_autoloader");' . $code;
        }
        return $code;
    }

    private static function __injectDebugCode($code, $debug){
        return $debug . $code;
    }

    private static function __getRunCode($postData){
        return isset($postData[ self::WEBSITE_FLAG ]) ? $postData[ self::WEBSITE_FLAG ] : '';
    }

    private static function __getRunType($postData){
        return isset($postData[ self::WEBSITE_FLAG ]) ? self::WEBSITE_FLAG : 'store';
    }

    private static function __isProjectSelected($postData){
        return isset($postData[ self::PROJECT_FLAG ]) && $postData[ self::PROJECT_FLAG ] != false;
    }

    private static function __sanitize($code){
        if (get_magic_quotes_gpc()) {
            $code = stripslashes($code);
        }
        $code = preg_replace('{^\s*<\?(php)?\s*}i', '', $code);
        return $code;
    }

    private static function __shouldGetProjectInfo($postData)
    {
        return isset($postData[ self::PROJECT_PATH ]);
    }
}