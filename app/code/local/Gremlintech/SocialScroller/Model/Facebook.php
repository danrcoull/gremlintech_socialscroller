<?php
/**
 * Class Gremlintech_SocialScroller_Model_Facebook
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 */

class Gremlintech_SocialScroller_Model_Facebook extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('socialscroller/facebook');
    }
}