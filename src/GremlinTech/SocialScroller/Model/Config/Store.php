<?php

/**
 * Class GremlinTech_SocialScroller_Model_Config_Store
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 * @implements GremlinTech_SocialScroller_Model_Config
 */
final class GremlinTech_SocialScroller_Model_Config_Store implements GremlinTech_SocialScroller_Model_Config_Interface
{
    /**
     * @var int storeId
     */
    protected $_storeId;
    /**
     * @var array general
     */
    protected $_general;
    /**
     * @var array facebook
     */
    protected $_facebook;
    /**
     * @var array twitter
     */
    protected $_twitter;

    protected $_google;

    protected $_frontend;

    /**
     *
     */
    CONST XML_CONFIG_SOCIALSCROLLER_PREFIX = "socialscroller/";
    CONST XML_CONFIG_SOCIAL_CORE = "socialcore/";


    public function __construct()
    {
        $storeId = Mage::app()->getStore()->getStoreId();
        $this->setStoreId($storeId);
        $this->setGeneralConfig();
        $this->setFacebookConfig();
        $this->setTwitterConfig();
        $this->setGoogleConfig();
        $this->setFrontendConfig();
    }

    /**
     * @param $storeId
     * @return GremlinTech_SocialScroller_Model_Config_Store
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * @return GremlinTech_SocialScroller_Model_Config_Store
     */
    public function setGeneralConfig()
    {
        if ($this->_general === null) {
            $prefix = self::XML_CONFIG_SOCIAL_CORE;
            $this->_general = Mage::getSingleton('gremlintech_socialscroller/config_general',
            array(
                'isActive' => $this->_getConfigFlag($prefix . 'general/is_active'),
                'debug' => $this->_getConfigFlag($prefix . 'general/debug'),
                'logName' => $this->_getConfig($prefix . 'general/log_name')
            ));
        }

        return $this;
    }

    /**
     * @return GremlinTech_SocialScroller_Model_Config_General
     */
    public function getGeneralConfig()
    {
        return $this->_general;
    }

    /**
     * @return GremlinTech_SocialScroller_Model_Config_Store
     */
    public function setFacebookConfig()
    {
        if ($this->_facebook === null) {
            $prefix = self::XML_CONFIG_SOCIAL_CORE;
            $this->_facebook = Mage::getSingleton('gremlintech_socialscroller/config_facebook',
                array(
                    'enabled' => $this->_getConfigFlag($prefix.'facebook/enabled'),
                    "appId" => $this->_getConfig($prefix.'facebook/app_id'),
                    "appSecret" => $this->_getConfig($prefix.'facebook/app_secret'),
                    "accessToken" => $this->_getConfig($prefix.'facebook/access_token'),
                    "defaultGraphVersion" => $this->_getConfig($prefix.'facebook/default_graph_version'),
                    "pageId" => $this->_getConfig($prefix.'facebook/page')
                ));
        }

        return $this;
    }

    /**
     * @return GremlinTech_SocialScroller_Model_Config_Facebook
     */
    public function getFacebookConfig()
    {
        return $this->_facebook;
    }


    public function getTwitterConfig()
    {
       return $this->_twitter;
    }

    public function setTwitterConfig()
    {
        if ($this->_twitter === null) {
            $prefix = self::XML_CONFIG_SOCIAL_CORE;
            $this->_twitter = Mage::getSingleton('gremlintech_socialscroller/config_twitter',
                array(
                    'enabled' => $this->_getConfigFlag($prefix.'twitter/enabled'),
                    'oauthAccessToken' => $this->_getConfig($prefix.'twitter/oauth_access_token'),
                    'oauthAccessTokenSecret' => $this->_getConfig($prefix.'twitter/oauth_access_token_secret'),
                    'consumerKey' => $this->_getConfig($prefix.'twitter/consumer_key'),
                    'consumerSecret' => $this->_getConfig($prefix.'twitter/consumer_secret'),
                    'username' =>  $this->_getConfig($prefix.'twitter/username')
                ));
        }

        return $this;
    }

    public function getGoogleConfig()
    {
        return $this->_google;
    }

    public function setGoogleConfig()
    {
        if ($this->_google === null) {
            $prefix = self::XML_CONFIG_SOCIAL_CORE;
            $this->_google = Mage::getSingleton('gremlintech_socialscroller/config_google',
                array(
                    'enabled' => $this->_getConfigFlag($prefix.'google/enabled'),
                    'applicationName' => $this->_getConfig($prefix.'google/application_name'),
                    'clientId' => $this->_getConfig($prefix.'google/client_id'),
                    'clientSecret' => $this->_getConfig($prefix.'google/client_secret'),
                    'developerKey' => $this->_getConfig($prefix.'google/developer_key'),
                    'page' => $this->_getConfig($prefix.'google/page'),
                ));
        }
        return $this;
    }

    public function setFrontendConfig()
    {
        if ($this->_frontend === null) {
            $prefix = self::XML_CONFIG_SOCIALSCROLLER_PREFIX;
            $this->_frontend = Mage::getSingleton('gremlintech_socialscroller/config_frontend',
                array(
                    'showInFrontend' => $this->_getConfigFlag($prefix.'frontend/show_in_frontend'),
                    'limits' => $this->_getConfig($prefix.'frontend/limits'),
                    'includeJquery' => $this->_getConfigFlag($prefix.'frontend/include_jquery')
                ));
        }
        return $this;
    }

    public function getFrontendConfig()
    {
        return $this->_frontend;
    }


    /**
     * @param $path
     * @return mixed
     */
    protected function _getConfig($path)
    {
        return Mage::getStoreConfig($path, $this->_storeId);
    }

    /**
     * @param $path
     * @return bool
     */
    protected function _getConfigFlag($path)
    {
        return Mage::getStoreConfigFlag($path, $this->_storeId);
    }


}