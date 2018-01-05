<?php

namespace Lib;

use Symfony\Component\Finder\Finder;

class MagentoProjects
{
    const MAGENTO_FILE_FINDER_PATTERN = '/../../*/*/app';
    const MAGE_FILE_NAME = 'Mage.php';
    const CACHE_KEY = 'PROJECTS';

    public static $projects = [];

    const PROJECT_PARSER_REGEX = '/\.\.\/\.\.\/((\w)*(\.)?(\w)*)\//';

    /*
     * @return array
     */
    public static function getProjects(){

        if(Cache::getInstance()->has(self::CACHE_KEY)){
           return Cache::getInstance()->get(self::CACHE_KEY);
        }

        $finder = new Finder();
        $finder
            ->name(self::MAGE_FILE_NAME)
            ->in(__DIR__ . self::MAGENTO_FILE_FINDER_PATTERN);

        foreach($finder as $mageFilePath){
            $regMatches = [];
            preg_match(self::PROJECT_PARSER_REGEX, $mageFilePath->getPathname(), $regMatches);
            self::$projects[] = [
                'path' => $mageFilePath->getPathname(),
                'project_id' => array_key_exists(1, $regMatches) ? $regMatches[1] : $regMatches[0],
            ];
        }

        Cache::getInstance()->set(self::CACHE_KEY, self::$projects);

        return self::$projects;
    }

}