<?php

/**
 * Class GremlinTech_SocialScroller_Model_Config_General
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 * @extends Mage_Core_Model_Abstract
 */
class GremlinTech_SocialScroller_Model_Config_General extends Mage_Core_Model_Abstract
{
    /**
     * @var
     */
    private $_isActive;
    /**
     * @var
     */
    private $_debug;
    /**
     * @var
     */
    private $_logName;


    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        parent::_construct();

        $this->setIsActive(isset($params['isActive']) ? $params['isActive'] : false);
        $this->setDebug(isset($params['debug']) ? $params['debug'] : false);
        $this->setLogName(isset($params['logName']) ? $params['logName'] : "");
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->_isActive;
    }

    /**
     * @param $active
     * @return GremlinTech_SocialScroller_Model_Config_General
     */
    public function setIsActive($active)
    {
        $this->_isActive = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    /**
     * @param $debug
     * @return GremlinTech_SocialScroller_Model_Config_General
     */
    public function setDebug($debug)
    {
        $this->_debug = $debug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogName()
    {
        return $this->_logName;
    }

    /**
     * @param string $logName
     * @return GremlinTech_SocialScroller_Model_Config_General
     */
    public function setLogName($logName = "module.log")
    {
        $this->_logName = $logName;
        return $this;
    }




}
