<?php

/**
 * Interface GremlinTech_SocialScroller_Model_Config
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 */
interface  GremlinTech_SocialScroller_Model_Config_Interface
{
    /**
     * @return mixed
     */
    public function getGeneralConfig();

    /**
     * @return mixed
     */
    public function setGeneralConfig();

    /**
     * @return mixed
     */
    public function getFacebookConfig();

    /**
     * @return mixed
     */
    public function setFacebookConfig();


    public function getTwitterConfig();

    public function setTwitterConfig();

    public function getGoogleConfig();
    public function setGoogleConfig();

}