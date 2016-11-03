<?php
class GremlinTech_SocialScroller_Model_Social_Google extends Mage_Core_Model_Abstract implements GremlinTech_SocialScroller_Model_Social_Interface
{

    private $_config;
    private $_helper;
    //google client
    private $_client;
    //google service
    private $_service;

    private $_feed;
    private $_limits;
    private $_cache;


    public function _construct()
    {
        if($this->_config->getEnabled()) {
            $config = Mage::getSingleton('gremlintech_socialscroller/config_store');
            $this->_cache = Mage::getModel('gremlintech_socialscroller/cache');
            $this->setLibConfig($config)
                ->setClient()
                ->setLimits()
                ->setFeed();
        }

        return $this;
    }

    public function setLibConfig(GremlinTech_SocialScroller_Model_Config_Store $config)
    {
        $this->_config = $config->getGoogleConfig();
        $this->_helper = Mage::helper('gremlintech_socialscroller');

        return $this;
    }

    public function getClient()
    {
        return $this->_client;
    }

    public function setClient()
    {
        try {
            //create new google client
            $this->_client = new Google_Client();
            //set the client with api key from config
            $this->_client->setApplicationName($this->_config->getApplicationName());
            $this->_client->setClientId($this->_config->getClientId());
            $this->_client->setClientSecret($this->_config->getClientSecret());
            $this->_client->setDeveloperKey($this->_config->getDeveloperKey());
            //create a service request to the key client
            $this->_service = new Google_Service_Plus($this->_client);

        }catch (Exception $e)
        {
            $this->_helper->log("* Error setting google client". $e->getMessage());
        }

        return $this;
    }

    public function getFeed()
    {
        return $this->_feed;
    }

    public function setFeed()
    {
        $feed = array();
        if($this->_config->getEnabled())
        {
            try
            {
                $optParams = array();
                $optParams = array_merge($optParams, $this->getLimits());
                $user = $this->_config->getPage();
                $activities = $this->_service->activities->listActivities("+" . $user, "public", $optParams);
                $posts = $activities->getItems();
                foreach($posts as $post)
                {
                    array_push($feed, array(
                        'type' => 'google',
                        'post' => $this->formatPost($post->getObject()->getContent()),
                        'datetime' => $post->getPublished()
                    ));
                }
            }catch (Exception $e)
            {
                $this->_helper->log("* Error getting google feed". $e->getMessage());
            }
        }

        $feed = $this->_cache->getCache($feed, 'google');

        $this->_feed = $feed;
        return $this;
    }

    public function setLimits($limit = false)
    {
        $config = Mage::getSingleton('gremlintech_socialscroller/config_store')->getFrontendConfig()->getLimits();
        $this->_limits = $limit ? $limit : $config;
        return $this;
    }

    public function getLimits()
    {
        $limits = $this->_limits;
        $limit = array();

        if(intval($limits) > 0)
        {
            $limit = array_merge($limit, array('maxResults' => $limits));
        }

        return $limit;
    }


    public function formatPost($post)
    {
        $post = str_replace("<br/>", "", $post);
        return $post;
    }
}