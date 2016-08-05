<?php

/**
 * Class Gremlintech_SocialScroller_Model_Twitter
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 */

class Gremlintech_SocialScroller_Model_Twitter extends Mage_Core_Model_Abstract
{

    //oath authentication paths
    const XML_OAUTH_ACCESS_TOKEN = "socialscroller/twitter/oauth_access_token";
    const XML_OAUTH_ACCESS_TOKEN_SECRET = "socialscroller/twitter/oauth_access_token_secret";

    //consumer key authentication paths
    const XML_CONSUMER_KEY = "socialscroller/twitter/consumer_key";
    const XML_CONSUMER_KEY_SECRET = "socialscroller/twitter/consumer_secret";

    //the xpath for magento config beyond authentication.
    const XML_TWITTER_USERNAME = "socialscroller/twitter/twitter_username";
    const XML_SHOW_TWEET_LINKS = "socialscroller/twitter/show_tweet_links";
    const XML_TWEETS_TO_SHOW = "socialscroller/twitter/tweets_to_show";

    protected function _construct()
    {
        //abstract init for the twitter model.
        $this->_init('socialscroller/twitter');
    }

    public function getSettings()
    {
        //build the settings array from magento config.
        $settings = array(
            'oauth_access_token' => Mage::getStoreConfig(self::XML_OAUTH_ACCESS_TOKEN),
            'oauth_access_token_secret' => Mage::getStoreConfig(self::XML_OAUTH_ACCESS_TOKEN_SECRET),
            'consumer_key' => Mage::getStoreConfig(self::XML_CONSUMER_KEY),
            'consumer_secret' => Mage::getStoreConfig(self::XML_CONSUMER_KEY_SECRET)
        );

        return $settings;
    }

    public function formatTweetsArray()
    {
        $social = array();
        $tweets = $this->getTweets();
        if(array_key_exists('errors',$tweets) == false && $tweets != false) {
            foreach ($tweets as $tweet) {

                if(Mage::getStoreConfig(self::XML_SHOW_TWEET_LINKS)) {
                    $text = $this->tweet_html_text($tweet);
                }else
                {
                    $text = $tweet['text'];
                }

                $newStructure = array(
                    'type' => 'twitter',
                    'post' => $text,
                    'datetime' => $tweet['created_at']
                );

                array_push($social,$newStructure);
            }

            $message = Mage::helper('socialscroller')->__("* Items Retrieved From twitter:");
            Mage::helper('socialscroller')->logMessage($message);
            Mage::helper('socialscroller')->logMessage($tweets);


            return $social;
        }

        $message = Mage::helper('socialscroller')->__("* There was a problem from twitter response:");
        $errorIndex = array_search("errors",array_keys($tweets));
        Mage::helper('socialscroller')->logMessage($message);
        Mage::helper('socialscroller')->logMessage($tweets[$errorIndex]);

        return false;

    }


    public function getTweets()
    {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $username = Mage::getStoreConfig(self::XML_TWITTER_USERNAME);
        $count = Mage::getStoreConfig(self::XML_TWEETS_TO_SHOW);

        $getfield = "";
        if($username != null) {
            $getfield = '?screen_name=' . $username;
            if ($count != null && $count > 0) {
                $getfield .= '&count=' . $count;
            }

            $requestMethod = 'GET';
            $twitter = new Twitter_APIExchange($this->getSettings());
            $response = $twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest();

            return json_decode($response, true);
        }

        $message = Mage::helper('socialscroller')->__("* Please Configure twitter screen name.");
        Mage::helper('socialscroller')->logMessage($message);

        return false;
    }


    function tweet_html_text($tweet) {
        $text = $tweet['text'];

        // hastags
        $linkified = array();
        foreach ($tweet['entities']['hashtags'] as $hashtag) {
            $hash = $hashtag['text'];

            if (in_array($hash, $linkified)) {
                continue; // do not process same hash twice or more
            }
            $linkified[] = $hash;

            // replace single words only, so looking for #Google we wont linkify >#Google<Reader
            $text = preg_replace('/#\b' . $hash . '\b/', sprintf('<a href="https://twitter.com/search?q=%%23%2$s&src=hash">#%1$s</a>', $hash, urlencode($hash)), $text);
        }

        // user_mentions
        $linkified = array();
        foreach ($tweet['entities']['user_mentions'] as $userMention) {
            $name = $userMention['name'];
            $screenName = $userMention['screen_name'];

            if (in_array($screenName, $linkified)) {
                continue; // do not process same user mention twice or more
            }
            $linkified[] = $screenName;

            // replace single words only, so looking for @John we wont linkify >@John<Snow
            $text = preg_replace('/@\b' . $screenName . '\b/', sprintf('<a href="https://www.twitter.com/%1$s" title="%2$s">@%1$s</a>', $screenName, $name), $text);
        }

        // urls
        $linkified = array();
        foreach ($tweet['entities']['urls'] as $url) {
            $url = $url['url'];

            if (in_array($url, $linkified)) {
                continue; // do not process same url twice or more
            }
            $linkified[] = $url;

            $text = str_replace($url, sprintf('<a href="%1$s">%1$s</a>', $url), $text);
        }

        return $text;
    }



}