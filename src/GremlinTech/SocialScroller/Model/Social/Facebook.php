<?php

/**
 * Class GremlinTech_SocialScroller_Model_Social_Facebook
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 * @extends Mage_Core_Model_Abstract
 */
class GremlinTech_SocialScroller_Model_Social_Facebook extends Mage_Core_Model_Abstract implements GremlinTech_SocialScroller_Model_Social_Interface
{

    /**
     * @var
     */
    private $_config;

    private $_helper;
    /**
     * @var
     */
    private $_client;
    private $_feed;

    private $_limits;

    private $_cache;

    public function __construct()
    {
        if($this->_config->getEnabled()) {
            $config = Mage::getSingleton('gremlintech_socialscroller/config_store');
            $this->_cache = Mage::getModel('gremlintech_socialscroller/cache');

            $this->setLibConfig($config)
                ->setClient()
                ->setLimits()
                ->setFeed();
        }

        return $this;
    }

    /**
     * @param GremlinTech_SocialScroller_Model_Config_Store $config
     * @return GremlinTech_SocialScroller_Model_Social_Facebook
     */
    public function setLibConfig(GremlinTech_SocialScroller_Model_Config_Store $config)
    {
        $this->_config = $config->getFacebookConfig();
        //dump($this->_config);
        $this->_helper = Mage::helper('gremlintech_socialscroller');

        //facebook uses env vars
        $appId = $this->_config->getAppId();
        $appSecret = $this->_config->getAppSecret();
        //not really required
        $version = $this->_config->getDefaultGraphVersion();

        putenv("FACEBOOK_APP_ID=$appId");
        putenv("FACEBOOK_APP_SECRET=$appSecret");

        return $this;
    }

    /**
     * @return GremlinTech_SocialScroller_Model_Social_Facebook
     */
    public function setClient()
    {
        $this->_client = new \Facebook\Facebook();
        return $this;
    }

    /**
     * @return \Facebook\Facebook
     */
    public function getClient()
    {
        return $this->_client;
    }


    public function getFeed()
    {
        return $this->_feed;
    }

    public function setFeed()
    {
        $feed = array();


        if($this->_config->getEnabled()) {

            $fb = $this->getClient();

            $fb->setDefaultAccessToken($this->_config->getAccessToken());

            try {

                $query = '/' . $this->_config->getPageId() . '/feed';
                $query .= $this->getLimits();

                $response = $fb->get($query);

                $plainOldArray = $response->getDecodedBody();

                $posts = $plainOldArray['data'];

                if (count($posts) > 0) {
                    foreach ($posts as $post) {
                            array_push($feed, array(
                                'type' => 'facebook',
                                'post' => $this->formatPost($post),
                                'datetime' => $post['created_time']
                            ));
                    }
                }

            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                $this->_helper->log('Graph returned an error: ' . $e->getMessage());
                return $this;
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                $this->_helper->log('Facebook SDK returned an error: ' . $e->getMessage());
                return $this;
            }
        }
        $feed = $this->_cache->getCache($feed, 'facebook');

        $this->_feed = $feed;
        return $this;
    }

    public function setLimits($limit = false)
    {
        $config = Mage::getSingleton('gremlintech_socialscroller/config_store')->getFrontendConfig()->getLimits();
        $this->_limits = $limit ? $limit : $config;
        return $this;
    }

    public function getLimits()
    {
        $limits = $this->_limits;
        $limit = "";

        if(intval($limits) > 0)
        {

            $limit = "?limit=" . $limits;
        }

        return $limit;
    }


    public function formatPost($post)
    {
        $text = isset($post['message']) ? $post['message'] : $post['story'];
        $text = trim(preg_replace('/\s+/', ' ', $text));
        #do urls (do first to stop replacing other a links)
        $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        $text = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $text);
        //replace #tags
        $url = "https://www.facebook.com/hashtag/";
        $id = $post['id'];
        $text = preg_replace('/#(\w+)/', "<a target='_blank' href='$url$1?source=feed_text&story_id=$id'>#$1</a>", $text);
        //replace @tags
        $url = "https://www.facebook.com/";
        $id = $post['id'];
        $text = preg_replace('/@(\w+)/', "<a target='_blank' href='$url$1?source=feed_text&story_id=$id'>@$1</a>", $text);
        return $text;
    }
}
