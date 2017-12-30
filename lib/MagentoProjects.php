<?php

namespace Lib;

use Symfony\Component\Finder\Finder;

class MagentoProjects
{
    const MAGENTO_FILE_FINDER_PATTERN = '/../../*/*/app';
    const MAGE_FILE_NAME = 'Mage.php';

    public static $projects = [];

    /*
     * @return array
     */
    public static function getProjects(){
        $finder = new Finder();
        $finder
            ->name(self::MAGE_FILE_NAME)
            ->in(__DIR__ . self::MAGENTO_FILE_FINDER_PATTERN);

        foreach($finder as $mageFilePath){
            $project = new MagentoProject($mageFilePath->getPathname());
            self::$projects[] = $project->toArray();
        }

        return self::$projects;
    }

}