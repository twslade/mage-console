<?php

namespace Lib;

class MagentoProject
{
    protected $_mageFilePath = null;
    protected $_data = [];

    public function __construct($mageFilePath)
    {
        $this->_mageFilePath = $mageFilePath;
        $this->_collectData();
    }

    public function injectMagento($runCode = '', $runType = 'store'){
        require_once $this->_mageFilePath;
        \Mage::app($runCode, $runType);
    }

    protected function _collectData(){
        $this->injectMagento();
        $this->_data = [
            'file' => $this->_mageFilePath,
            'title' => \Mage::getStoreConfig('design/header/logo_alt'),
            'logo' => \Mage::getDesign()->getSkinUrl() . \Mage::getStoreConfig('design/header/logo_src'),
            'websites' => $this->_getWebsites(),
            'stores' => $this->_getStores(),
        ];
    }

    public function toArray(){
        return $this->_data;
    }

    protected function _getStores(){
        $stores = \Mage::getModel('core/store')->getCollection();
        $storesData = [];
        foreach ($stores as $store){
            $storesData[] = $store->getData();
        }
        return $storesData;
    }

    protected function _getWebsites(){
        $websites = \Mage::getModel('core/website')->getCollection();
        $websiteData = [];
        foreach ($websites as $website){
            $websiteData[] = $website->getData();
        }
        return $websiteData;
    }
}