<?php

class GremlinTech_SocialScroller_Model_Config_Twitter extends Mage_Core_Model_Abstract
{
    private $_enabled;
    private $_oauthAccessToken;
    private $_oauthAccessSecret;
    private $_consumerKey;
    private $_consumerSecret;
    private $_username;

    public function __construct(array $params)
    {
        $this->setEnabled(isset($params['enabled']) ? $params['enabled'] : false);
        $this->setOauthAccessToken(isset($params['oauthAccessToken']) ? $params['oauthAccessToken'] : "" );
        $this->setOauthAccessSecret(isset($params['oauthAccessTokenSecret']) ? $params['oauthAccessTokenSecret'] : "");
        $this->setConsumerKey(isset($params['consumerKey']) ? $params['consumerKey'] : "");
        $this->setConsumerSecret(isset($params['consumerSecret']) ? $params['consumerSecret'] : "");
        $this->setUsername(isset($params['username']) ? $params['username'] : "");
    }

    public function setEnabled($enabled)
    {
        $this->_enabled = $enabled;
        return $this;
    }

    public function getEnabled()
    {
        return $this->_enabled;
    }

    /**
     * @return mixed
     */
    public function getOauthAccessToken()
    {
        return $this->_oauthAccessToken;
    }

    /**
     * @param mixed $oauthAccessToken
     */
    public function setOauthAccessToken($oauthAccessToken)
    {
        $this->_oauthAccessToken = $oauthAccessToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOauthAccessSecret()
    {
        return $this->_oauthAccessSecret;
    }

    /**
     * @param mixed $oauthAccessSecret
     */
    public function setOauthAccessSecret($oauthAccessSecret)
    {
        $this->_oauthAccessSecret = $oauthAccessSecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsumerKey()
    {
        return $this->_consumerKey;
    }

    /**
     * @param mixed $consumerKey
     */
    public function setConsumerKey($consumerKey)
    {
        $this->_consumerKey = $consumerKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsumerSecret()
    {
        return $this->_consumerSecret;
    }

    /**
     * @param mixed $consumerSecret
     */
    public function setConsumerSecret($consumerSecret)
    {
        $this->_consumerSecret = $consumerSecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }

}