<?php

/**
 * Class GremlinTech_SocialScroller_Model_Config_Frontend
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 * @extends Mage_Core_Model_Abstract
 */
class GremlinTech_SocialScroller_Model_Config_Frontend extends Mage_Core_Model_Abstract
{

    private $_showInFrontend;
    private $_limits;
    private $_includeJquery;


    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->setShowInFrontend(isset($params['showInFrontend']) ? $params['showInFrontend'] : false);
        $this->setLimits(isset($params['limits']) ? $params['limits'] : "");
        $this->setIncludeJquery(isset($params['includeJquery']) ? $params['includeJquery'] : "");
    }


    /**
     * @return bool
     */
    public function getShowInFrontend()
    {
        return $this->_showInFrontend;
    }

    /**
     * @param bool $showInFrontend
     * @return GremlinTech_SocialScroller_Model_Config_Frontend
     */
    public function setShowInFrontend($showInFrontend)
    {
        $this->_showInFrontend = $showInFrontend;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLimits()
    {
        return $this->_limits;
    }

    /**
     * @param integer $limits
     * @return GremlinTech_SocialScroller_Model_Config_Frontend
     */
    public function setLimits($limits)
    {
        $this->_limits = $limits;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIncludeJquery()
    {
        return $this->_includeJquery;
    }

    /**
     * @param bool $includeJquery
     * @return GremlinTech_SocialScroller_Model_Config_Frontend
     */
    public function setIncludeJquery($includeJquery)
    {
        $this->_includeJquery = $includeJquery;
        return $this;
    }




}