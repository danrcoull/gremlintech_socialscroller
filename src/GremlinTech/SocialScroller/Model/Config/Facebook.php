<?php

/**
 * Class GremlinTech_SocialScroller_Model_Config_Facebook
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 * @extends Mage_Core_Model_Abstract
 */
class GremlinTech_SocialScroller_Model_Config_Facebook extends Mage_Core_Model_Abstract
{

    private $_enabled;
    private $_appId = "";
    private $_appSecret = "";
    private $_accessToken = "";
    private $_defaultGraphVersion = "2.7";
    private $_pageId;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->setEnabled(isset($params['enabled']) ? $params['enabled'] : false);
        $this->setAppId(isset($params['appId']) ? $params['appId'] : "");
        $this->setAppSecret(isset($params['appSecret']) ? $params['appSecret'] : "");
        $this->setAccessToken(isset($params['accessToken']) ? $params['accessToken'] : "");
        $this->setDefaultGraphVersion(isset($params['defaultGraphVersion']) ? $params['defaultGraphVersion'] : "2.7");
        $this->setPageId(isset($params['pageId']) ? $params['pageId'] : "");
    }

    public function setEnabled($enabled = false)
    {
        $this->_enabled = $enabled;
        return $this;
    }

    public function getEnabled()
    {
        return $this->_enabled;
    }

    public function setAppId( $appId)
    {
        $this->_appId = $appId;
        return $this;
    }

    public function getAppId()
    {
        return $this->_appId;
    }

    public function setAppSecret( $appSecret)
    {
        $this->_appSecret = $appSecret;
        return $this;
    }

    public function getAppSecret()
    {
        return $this->_appSecret;
    }

    public function setDefaultGraphVersion($graphVersion = "2.7")
    {
        $this->_defaultGraphVersion = $graphVersion;
        return $this;
    }

    public function getDefaultGraphVersion()
    {
        return $this->_defaultGraphVersion;
    }

    public function setPageId( $pageId)
    {
        $this->_pageId = $pageId;
        return $this;
    }

    public function getPageId()
    {
        return $this->_pageId;
    }

    public function setAccessToken($token)
    {
        $this->_accessToken = $token;
        return $this;
    }

    public function getAccessToken()
    {
        return $this->_accessToken;
    }


}