<?php

interface GremlinTech_SocialScroller_Model_Social_Interface
{
    public function setLibConfig(GremlinTech_SocialScroller_Model_Config_Store $config);

    public function getClient();
    public function setClient();

    public function getFeed();
    public function setFeed();

    public function setLimits($limit);
    public function getLimits();

    public function formatPost($post);


}