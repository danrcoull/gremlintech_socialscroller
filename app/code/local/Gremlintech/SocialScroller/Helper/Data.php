<?php
/**
 * Class Gremlintech_SocialScroller_Helper_Data
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 */

class Gremlintech_SocialScroller_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_IS_ENABLED = "socialscroller/general/enabled";
    const XML_DEBUG = "socialscroller/general/debug";

    public function isEnabled()
    {
        return Mage::getStoreConfig(self::XML_IS_ENABLED) == 1 ? true : false;
    }

    public function debugEnabled()
    {
        return Mage::getStoreConfig(self::XML_DEBUG) == 1 ? true : false;
    }

    public function logMessage($message)
    {
        if($this->debugEnabled())
        {
            Mage::log($message, null, 'socialscroller_debug.log');
        }

        return $this;
    }

}
	 