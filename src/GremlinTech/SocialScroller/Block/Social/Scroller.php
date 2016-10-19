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
        $facebook = Mage::getModel("gremlintech_socialscroller/social_facebook")->getFeed();
        $google = Mage::getModel("gremlintech_socialscroller/social_google")->getFeed();
        $twitter= Mage::getModel("gremlintech_socialscroller/social_twitter")->getFeed();

        $this->_collection = array_merge($facebook, $google, $twitter);

        return $this;
    }


}