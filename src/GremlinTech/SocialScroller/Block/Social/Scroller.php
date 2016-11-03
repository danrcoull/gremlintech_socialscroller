<?php

class GremlinTech_SocialScroller_Block_Social_Scroller extends  Mage_Core_Block_Template
{
    private $_collection;

    public function _construct()
    {
        $this->setCollection();
    }

    /**
     * @return mixed
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    /**
     * @param mixed $collection
     */
    public function setCollection()
    {
        $facebookFeed = Mage::getModel("gremlintech_socialscroller/social_facebook")->getFeed();
        $facebook = is_array($facebookFeed ) ? $facebookFeed  : array() ;
        $googleFeed = Mage::getModel("gremlintech_socialscroller/social_google")->getFeed();
        $google =  is_array($googleFeed) ? $googleFeed : array();
        $twitterFeed = Mage::getModel("gremlintech_socialscroller/social_twitter")->getFeed();
        $twitter= is_array($twitterFeed) ? $twitterFeed : array();

        $this->_collection = array_merge($facebook, $google, $twitter);

        return $this;
    }


}