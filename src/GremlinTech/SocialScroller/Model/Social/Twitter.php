<?php

class GremlinTech_SocialScroller_Model_Social_Twitter extends Mage_Core_Model_Abstract implements GremlinTech_SocialScroller_Model_Social_Interface
{

    private $_config;
    private $_helper;
    private $_client;
    private $_feed;

    private $_settings;

    private $_limits;
    private $_cache;


    public function _construct()
    {
        $config = Mage::getSingleton('gremlintech_socialscroller/config_store');
        $this->_cache = Mage::getModel('gremlintech_socialscroller/cache');

        $this->setLibConfig($config)
            ->setClient()
            ->setLimits()
            ->setFeed();
    }

    public function setLibConfig(GremlinTech_SocialScroller_Model_Config_Store $config)
    {
        $this->_config = $config->getTwitterConfig();
        $this->_helper = Mage::helper('gremlintech_socialscroller');

        $this->_settings = array(
            'oauth_access_token' => $this->_config->getOauthAccessToken(),
            'oauth_access_token_secret' => $this->_config->getOauthAccessSecret(),
            'consumer_key' => $this->_config->getConsumerKey(),
            'consumer_secret' => $this->_config->getConsumerSecret()
        );

        return $this;
    }

    public function getClient()
    {
        return $this->_client;
    }

    public function setClient()
    {
        $this->_client = new TwitterAPIExchange($this->_settings);
        return $this;
    }

    public function getFeed()
    {
        return $this->_feed;
    }

    public function setFeed()
    {
        $feed = array();

        if ($this->_config->getEnabled()) {
            $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
            $username = $this->_config->getUsername();
            if ($username != null) {
                $getfield = '?screen_name=' . $username;
                $getfield .= $this->getLimits();

                $requestMethod = 'GET';
                $response = $this->getClient()->setGetfield($getfield)
                    ->buildOauth($url, $requestMethod)
                    ->performRequest();
                $posts = json_decode($response, true);
                if (count($posts) > 1) {
                    foreach ($posts as $post) {
                        array_push($feed, array(
                            'type' => 'twitter',
                            'post' => $this->formatPost($post),
                            'datetime' => $post['created_at']
                        ));
                    }
                }
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
            $limit = '&count=' . $limits;
        }

        return $limit;
    }


    public function formatPost($post)
    {
        $text = $post['text'];
        // hastags
        $linkified = array();
        foreach ($post['entities']['hashtags'] as $hashtag) {
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
        foreach ($post['entities']['user_mentions'] as $userMention) {
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
        foreach ($post['entities']['urls'] as $url) {
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