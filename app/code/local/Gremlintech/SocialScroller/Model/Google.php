<?php


class Gremlintech_SocialScroller_Model_Google extends Mage_Core_Model_Abstract
{

    const XML_GOOGLEAPI_KEY = "socialscroller/google/api_key";
    const XML_GOOGLE_PAGE   = "socialscroller/google/page";

    protected function _construct()
    {
        //init the model construct from abstract.
        $this->_init('socialscroller/google');
    }


    public function formatPostsArray()
    {
        $social = array();
        //request page feed items
        $posts = $this->getPageFeed();
        if($posts != false) {
            foreach ($posts as $post) {
                //create a new structure to match twitter and facebook
               $newStructure = array(
                    'type' => 'google-plus',
                    'post' => str_replace("<br />", "", $post->getObject()->getContent()),
                    'datetime' => $post->getPublished()
                );
                //push the new item to the array
                array_push($social,$newStructure);
            }
            return $social;
        }

        //returm false if no posts are returned.
        return false;
    }

    public function getPageFeed()
    {
        //Get the settings from magento config
        $key = Mage::getStoreConfig(self::XML_GOOGLEAPI_KEY);
        $user = Mage::getStoreConfig(self::XML_GOOGLE_PAGE);

        //check both keys are not null
        if($key && $user) {
            //try catch, incase authentication fails
            try {
                //create new google client
                $client = new Google_Client();
                //set the client with api key from config
                $client->setDeveloperKey($key);
                //create a service request to the key client
                $plus = new Google_Service_Plus($client);
                //create paramters
                $optParams = array('maxResults' => 10);
                //request the feed for username above, with params
                $activities = $plus->activities->listActivities("+" . $user, "public", $optParams);
                //collect items and return
                $items = $activities->getItems();

                return $items;

            }catch (Exception $e)
            {
                //TODO:: add debug logic to give error

                //return false if any errors
                return false;
            }
        }

        //TODO:: add debug logic to notify admin to configure first
        return false;
    }


}