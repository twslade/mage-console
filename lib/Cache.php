<?php

namespace Lib;

use Symfony\Component\Cache\Simple\FilesystemCache;

class Cache {

    protected static $_instance = null;

    protected function __construct()
    {
        self::$_instance = new FilesystemCache();
    }

    public static function getInstance(){
        if(!self::$_instance){
            new self();
        }
        return self::$_instance;
    }

    private function __clone(){}
    private function __wakeup(){}
}