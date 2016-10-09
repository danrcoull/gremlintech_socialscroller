<?php

class GremlinTech_SocialScroller_Model_Cache extends Mage_Core_Model_Abstract
{
    const XML_CACHE_TAG     = "SOCIAL_SCROLLER_FEED";

    private $_useCache;

    public function _construct()
    {
        $this->setUseCache();
    }

    public function setUseCache()
    {
        $this->_useCache = Mage::app()->getCacheInstance()->canUse(self::XML_CACHE_TAG);
        return $this;
    }

    public function getUseCache()
    {
        return $this->_useCache;
    }

    public function setCache($feed, $lib)
    {
        $cacheId = "socialscroller_$lib";
        if ($this->getUseCache()) {
            try {
                $lifetime = Mage::getStoreConfig('core/cache/lifetime');
                //save the cache
                Mage::app()->saveCache(serialize($feed), $cacheId, array(self::XML_CACHE_TAG), $lifetime);
            } catch (Exception $e) {

            }
        }

        return $this;
    }

    public function getCache($feed, $lib)
    {
        $cacheId = "socialscroller_$lib";
        if ($this->getUseCache()) {
            if ($feed = Mage::app()->loadCache($cacheId)) {
                return unserialize($feed);
            } else {
                //if the feed is not set but we should use it set and reget
                $this->setCache($feed, $lib);
                $this->getCache($feed, $lib);
            }
        }

        //if we dont use cache return the original feed
        return $feed;
    }

    public function cleanCache()
    {
        Mage::app()->getCacheInstance()->cleanType(self::XML_CACHE_TAG);
        return $this;
    }


}