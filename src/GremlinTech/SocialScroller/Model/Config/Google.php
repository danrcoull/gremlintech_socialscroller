<?php

class GremlinTech_SocialScroller_Model_Config_Google extends Mage_Core_Model_Abstract
{

    private $_enabled;
    private $_applicationName;
    private $_clientId;
    private $_clientSecret;
    private $_developerKey;
    private $_page;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->setEnabled(isset($params['enabled']) ? $params['enabled'] : false);
        $this->setApplicationName(isset($params['applicationName']) ? $params['applicationName'] : "");
        $this->setClientId(isset($params['clientId']) ? $params['clientId'] : "");
        $this->setClientSecret(isset($params['clientSecret']) ? $params['clientSecret'] : "");
        $this->setDeveloperKey(isset($params['developerKey']) ? $params['developerKey'] : "");
        $this->setPage(isset($params['page']) ? $params['page'] : "");
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->_enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->_enabled = $enabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicationName()
    {
        return $this->_applicationName;
    }

    /**
     * @param mixed $applicationName
     */
    public function setApplicationName($applicationName)
    {
        $this->_applicationName = $applicationName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->_clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->_clientId = $clientId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->_clientSecret;
    }

    /**
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->_clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeveloperKey()
    {
        return $this->_developerKey;
    }

    /**
     * @param mixed $developerKey
     */
    public function setDeveloperKey($developerKey)
    {
        $this->_developerKey = $developerKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->_page = $page;
        return $this;
    }


}