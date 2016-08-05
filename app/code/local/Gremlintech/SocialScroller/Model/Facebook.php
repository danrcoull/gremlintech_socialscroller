<?php

/**
 * Class Gremlintech_SocialScroller_Model_Facebook
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 */
class Gremlintech_SocialScroller_Model_Facebook extends Mage_Core_Model_Abstract
{

    protected $fb;
    protected $accessToken;


    const XML_FACEBOOK_APP_ID = "socialscroller/facebook/app_id";
    const XML_FACEBOOK_APP_SECRET = "socialscroller/facebook/app_secret";
    const XML_FACEBOOK_ACCESS_TOKEN = "socialscroller/facebook/access_token";

    const XML_FACEBOOK_PAGE = "socialscroller/facebook/facebook_page";
    const XML_FACEBOOK_PAGE_LIMIT = "socialscroller/facebook/facebook_page_limit";

    protected function _construct()
    {
        $this->_init('socialscroller/facebook');
        $this->createAppInstance();
        $this->getAccessToken();

    }

    public function createAppInstance()
    {
        $config = array('app_id' => Mage::getStoreConfig(self::XML_FACEBOOK_APP_ID),
            'app_secret' => Mage::getStoreConfig(self::XML_FACEBOOK_APP_SECRET),
            'default_graph_version' => 'v2.6');

        if (($accesstoken = Mage::getStoreConfig(self::XML_FACEBOOK_ACCESS_TOKEN)) == "") {
            $config['default_access_token'] = $accesstoken;
        }

        $this->fb = new \Facebook\Facebook($config);
        return $this;
    }

    public function getAccessToken()
    {

        if (Mage::getStoreConfig(self::XML_FACEBOOK_ACCESS_TOKEN) == "") {
            $helper = $this->fb->getCanvasHelper();
            try {
                $accessToken = $helper->getAccessToken();
                var_dump($accessToken);
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            if (isset($accessToken)) {
                // Logged in.
                $this->accessToken = $accessToken;
            }
        } else {
            $this->accessToken = Mage::getStoreConfig(self::XML_FACEBOOK_ACCESS_TOKEN);
        }

        return $this;
    }

    public function getFacebookPage()
    {
        try {
            $page = Mage::getStoreConfig(self::XML_FACEBOOK_PAGE);
            $query = '/' . $page . '/feed';

            if (($limit = Mage::getStoreConfig(self::XML_FACEBOOK_PAGE_LIMIT)) != "") {
                $query .= "?limit=" . $limit;
            }

            $response = $this->fb->get($query, $this->accessToken);
            $plainOldArray = $response->getDecodedBody();
            $posts = $plainOldArray['data'];

            if(count($posts) > 1)
            {
                return $posts;
            }

            return false;

        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            $message =  'Facebook SDK returned an error: ' . $e->getMessage();
            Mage::helper('socialscroller')->logMessage($message);
            return false;
        }

        return false;

    }

    public function formatFacebookArray()
    {
        $social = array();
        $posts = $this->getFacebookPage();
        if($posts)
        {
            foreach($posts as $post)
            {
                $newStructure = array(
                    'type' => 'facebook',
                    'post' => $this->facebook_html_text($post),
                    'datetime' => $post['created_time']
                );

                array_push($social,$newStructure);
            }

            $message = Mage::helper('socialscroller')->__("* Items Retrieved From facebook:");
            Mage::helper('socialscroller')->logMessage($message);
            Mage::helper('socialscroller')->logMessage($posts);


            return $social;
        }

        $message = Mage::helper('socialscroller')->__("* There was a problem from facebook response:");
        Mage::helper('socialscroller')->logMessage($message);

        return false;
    }

    public function facebook_html_text($post)
    {
        $text = $post['message'];

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