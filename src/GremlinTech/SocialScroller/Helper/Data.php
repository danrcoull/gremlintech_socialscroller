<?php

class GremlinTech_SocialScroller_Helper_Data extends  Mage_Core_Helper_Abstract
{

    public function __construct()
    {

    }

    public function log($mesage)
    {
        $config = Mage::getModel('gremlintech_socialscroller/config_store');

        if($config->getGeneralConfig()->getDebug()) {
            $translate = $this->__($mesage);
            Mage::log($translate,null, $config->getGeneralConfig()->getLogName(), true);
        }

        return $this;
    }
}