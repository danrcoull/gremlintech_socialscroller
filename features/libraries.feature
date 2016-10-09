@backend
Feature: Social Feeds
    In order to load all social feeds
    As magento
    I need to be able to load all Magento Models

    @model @helper @php
     Scenario: Getting the Google Feed
            Given that the 'Google_Client' library exists
                And a 'Google' Model exists in the module
                And the 'Google' config keys are loaded from admin
                    But if debug is 'true' log output like 'test log'
            Then the construct entry point 'google' can be configured with class 'Google_Client'
            When the feed is loaded for 'google'
                Given the cache is enabled or disabled
                Then i should get an 'array' of 'google' posts

    @model @helper @php
    Scenario: Getting the Twitter Feed
        Given that the 'TwitterAPIExchange' library exists
            And a 'Twitter' Model exists in the module
            And the 'Twitter' config keys are loaded from admin
                But if debug is 'true' log output like 'test log'
        Then the construct entry point 'twitter' can be configured with class 'TwitterAPIExchange'
        When the feed is loaded for 'twitter'
            Given the cache is enabled or disabled
            Then i should get an 'array' of 'twitter' posts

    @model @helper @php
    Scenario: Getting the Facebook Feed
            Given that the '\Facebook\Facebook' library exists
                And a 'Facebook' Model exists in the module
                And the 'Facebook' config keys are loaded from admin
                    But if debug is 'true' log output like 'test log'
            Then the construct entry point 'facebook' can be configured with class '\Facebook\Facebook'
            When the feed is loaded for 'facebook'
                Given the cache is enabled or disabled
                Then i should get an 'array' of 'facebook' posts






