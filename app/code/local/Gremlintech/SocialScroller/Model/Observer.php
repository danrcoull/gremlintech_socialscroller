<?php
define('FACEBOOK_SDK_V4_SRC_DIR', 'lib/Facebook/');

class Gremlintech_SocialScroller_Model_Observer
{
    const AUTOLOADER_FILE = 'Facebook/autoload.php';
    public function addAutoloader()
    {
        require_once self::AUTOLOADER_FILE;
        return $this;
    }
}