<?php

class Gremlintech_SocialScroller_Block_Scroller extends Mage_Core_Block_Template
{

    private $_collection;

    /**
     * access point for the collection variable
     * @return Array from collection var
     */
    public function getCollection()
    {
        $this->setCollection();
        return $this->_collection;
    }

    /**
     * @description used to set collection from fresh feed or cache.
     * @return $this
     */
    public function setCollection()
    {
        //custom cache group from xml config
        $cacheGroup = 'socialscroller';
        //check if cache is enabled
        $useCache = Mage::app()->useCache($cacheGroup);
        //if cache is enabled
        if (true === $useCache) {
            //set custom cache id
            $cacheId = "social_array";
            //check if cacheid returns anything
            if ($cacheContent = Mage::app()->loadCache($cacheId)) {
                //add to original array var
                $array = $cacheContent;
            } else {
                //try to resave new array content
                try {
                    //get array
                    $array = $this->sortArray();
                    //get config lifetime
                    $lifetime = Mage::getStoreConfig('core/cache/lifetime');
                    //save the cache
                    Mage::app()->saveCache($array, $cacheId, array($cacheGroup), $lifetime);

                } catch (Exception $e) {
                    //lets retry to stop any errors
                    $this->setCollection();
                }
            }
        } else {
            //we are not cached so just run the original
            $array = $this->sortArray();
        }

        //set the collection to thes block
        $this->_collection = $array;
        //return $this for chaining.
        return $this;
    }

    /**
     * Request the model of arrays from all networks to be merged into a single for sorting.
     * @return array array merged from all networks
     */
    public function getSocialArray()
    {

        //get the twitter tweets array formated for merge
        $tweets = Mage::getModel('socialscroller/twitter')->formatTweetsArray();
        //get the google+ feed array formated for merge
        $feed = Mage::getModel('socialscroller/google')->formatPostsArray();

        return array_merge($tweets, $feed);
    }


    /**
     * @description set the array in date order.
     * @return array
     */
    public function sortArray()
    {
        $array = $this->getSocialArray();
        usort($array, array($this, 'date_compare'));
        return $array;
    }

    /**
     * @description function to be called by usort
     * @param $a previous array
     * @param $b next array
     * @return int return sort int./
     */
    public function date_compare($a, $b)
    {
        return strtotime($a['datetime']) < strtotime($b['datetime']) ? 1 : -1;
    }
}