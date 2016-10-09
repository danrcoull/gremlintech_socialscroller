<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;


/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{

    private $_libConfig;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(){
         Mage::app('admin');
        //Mage::app()->setCurrentStore('default');
    }

    /**
     * @Given that the :library library exists
     */
    public function thatTheLibraryExists($library)
    {
        //make sure the library entry class exists
        $exists = class_exists($library);
        PHPUnit_Framework_Assert::assertTrue($exists, "$library class does not exist");
    }

    /**
     * @Given a :model Model exists in the module
     */
    public function aModelExistsInTheModule($model)
    {
        //check standard class exists
        $class = "GremlinTech_SocialScroller_Model_Social_".$model;
        $classExists = class_exists($class);
        PHPUnit_Framework_Assert::assertTrue($classExists, "$model Model Does not exist");

        //make sure model follows practice
        $doesClassExtend = is_subclass_of(new $class(),'Mage_Core_Model_Abstract');
        PHPUnit_Framework_Assert::assertTrue($doesClassExtend, "$model Model does not extend Mage_Core_Model_Abstract");

        //is loaded within the magento framework
        $library = strtolower($model);
        $modelMagento = Mage::getModel("gremlintech_socialscroller/social_$library");
        PHPUnit_Framework_Assert::assertInstanceOf($class, $modelMagento, "Class doesnt match magento instance");

        return true;
    }

    /**
     * @Given the :library config keys are loaded from admin
     */
    public function theConfigKeysAreLoadedFromAdmin($library)
    {
        $config = Mage::getSingleton('gremlintech_socialscroller/config_store');

        //check the class runs common practice by inheriting an interface
        $interface = class_implements($config);
        PHPUnit_Framework_Assert::assertArrayHasKey('GremlinTech_SocialScroller_Model_Config_Interface',$interface,
            "Class Does
         not inherit an interface breeching common practice");
        //check the class is a member of magento framework
        PHPUnit_Framework_Assert::assertInstanceOf('GremlinTech_SocialScroller_Model_Config_Store', $config,  "Config Doesnt Exist in Magento");

        //make sure the config is set from admin and can be retrrived
        $storeId = Mage::app()->getStore()->getStoreId();
        //use dependancy injection to get and set config makes testing easier.
        $generalConfig = $config->setStoreId($storeId)->setGeneralConfig()->getGeneralConfig();
        PHPUnit_Framework_Assert::assertInstanceOf('GremlinTech_SocialScroller_Model_Config_General', $generalConfig,
            "Config should return an object class instance");

        //check that the general config from dependancy is the same as straight call
        $straightTest = Mage::getStoreConfigFlag('socialcore/general/is_active', $storeId);
        PHPUnit_Framework_Assert::assertSame($straightTest, $generalConfig->getIsActive(), "Module General Config doesnt exist");

        //use set get based on library for general purpose
        $setter = "set".$library."Config";
        $getter = "get".$library."Config";
        //make sure the config returns the correct instance of the config library
        $this->_libConfig = $libConfig = $config->setStoreId($storeId)->$setter()->$getter();
        PHPUnit_Framework_Assert::assertInstanceOf("GremlinTech_SocialScroller_Model_Config_$library", $libConfig,
            "$library config should return an object class instance");


    }

    /**
     * @Given if debug is :enabled log output like :log
     */
    public function ifDebugIsEnabledLogAllOutput($enabled, $log)
    {
        $config = Mage::getModel('gremlintech_socialscroller/config_store');
        //make sure the config is set from admin and can be retrrived
        $storeId = Mage::app()->getStore()->getStoreId();
        //use dependancy injection to get and set config makes testing easier.
        $generalConfig = $config->setStoreId($storeId)->setGeneralConfig()->getGeneralConfig();

        //just to mock the test without applying admin settings we inject it
        if($generalConfig->setDebug(true)->getDebug() == $enabled)
        {

            $helper = Mage::helper('gremlintech_socialscroller');
            $logName = $generalConfig->setLogName('socialscroller.log')->getLogName();
            PHPUnit_Framework_Assert::assertEquals('socialscroller.log', $logName, "Log Name Not as expected");
            $helper->log($log);
            $fileContents = file_get_contents(getCwd()."/../../http/var/log/$logName");
            PHPUnit_Framework_Assert::assertContains($log,$fileContents,"Log File Does not contain test log");

        }

        return true;
    }


    /**
     * @Then the construct entry point :library can be configured with class :class
     */
    public function theConstructEntryPointCanBeConfigured($library, $class)
    {
        $model = Mage::getModel("gremlintech_socialscroller/social_$library");

        //normally done by constructor but for testing we are doing manually
        //setLibConfig as differnt librarys assign config differntly
        //set and get client for testing and easy access to the instance
        $config = Mage::getSingleton('gremlintech_socialscroller/config_store');
        $client = $model->setLibConfig($config)->setClient()->getClient();
        //lets be sure that the assertion of the entry point is correct
        PHPUnit_Framework_Assert::assertNotNull($client, "Entry Point not configuring");
        PHPUnit_Framework_Assert::assertInstanceOf($class, $client, "Entry Point Wrong class");

    }


    /**
     * @When the feed is loaded for :library
     */
    public function theFeedIsLoaded($library)
    {
        $model = Mage::getModel("gremlintech_socialscroller/social_$library");
        $config = Mage::getSingleton('gremlintech_socialscroller/config_store');
        $feed = $model->setLibConfig($config)->setClient()->setFeed()->getFeed();
        PHPUnit_Framework_Assert::assertNotNull($feed, 'feed no response');
        PHPUnit_Framework_Assert::assertNotEmpty($feed, 'feed should not be empty');


    }

    /**
     * @Given the cache is enabled or disabled
     */
    public function theCacheIsEnabledOrDisabled()
    {

    }


    /**
     * @Then i should get an :arg1 of :arg2 posts
     */
    public function iShouldGetAnOfPosts($arg1, $arg2)
    {
        $model = Mage::getModel("gremlintech_socialscroller/social_$arg2");
        $feed = $model->getFeed();
        PHPUnit_Framework_Assert::assertInternalType($arg1, $feed, 'Feed is not an array' );
    }

    /**
     * @Given a limit of :arg1
     */
    public function aLimitOf($arg1)
    {
        $config = Mage::getSingleton('gremlintech_socialscroller/config_store');

        $originalLimit = Mage::getStoreConfig('socialscroller/frontend/limits');

        PHPUnit_Framework_Assert::assertNotNull($originalLimit, 'Limit Config Option doesnt exist');

        //check the actual admin config exists by saving mock value
        Mage::getConfig()->saveConfig('socialscroller/frontend/limits', $arg1, 'default', 0);

        //use getter and setter to mock the values

        $limit = $config->getFrontendConfig()->getLimits();

        PHPUnit_Framework_Assert::assertSame(intval($arg1), intval($limit), "Limit Set in config not expected");

    }

    /**
     * @Then each library should only load :arg1 posts
     */
    public function eachLibraryShouldOnlyLoadPosts($arg1)
    {
        $arg1 = intval($arg1);

        $facebook = Mage::getModel("gremlintech_socialscroller/social_facebook")->getFeed();
        $google = Mage::getModel("gremlintech_socialscroller/social_google")->getFeed();
        $twitter= Mage::getModel("gremlintech_socialscroller/social_twitter")->getFeed();

        PHPUnit_Framework_Assert::assertCount($arg1, $facebook, "Facebook Not matching limit requirments");
        PHPUnit_Framework_Assert::assertCount($arg1, $google, "Google Not matching limit requirments");
        PHPUnit_Framework_Assert::assertCount($arg1, $twitter, "Twitter Not matching limit requirments");
    }

    /**
     * @When collection is called the total should only be :arg1 x Libraries enabled
     */
    public function collectionIsCalledTheTotalShouldOnlyBeXLibrariesEnabled($arg1)
    {
        $collection = Mage::app()->getLayout()->getBlockSingleton('gremlintech_socialscroller/social_scroller')
            ->getCollection();

        PHPUnit_Framework_Assert::assertCount($arg1 * 3, $collection, "Collection count of block to high");
    }

    /**
     * @Given im on the url :arg1
     */
    public function imOnTheUrl($arg1)
    {

    }

    /**
     * @Given :arg1 is enabled in admin for frontend
     */
    public function isEnabledInAdminForFrontend($arg1)
    {
        $config = Mage::getSingleton('gremlintech_socialscroller/config_store');
        $jquery = $config->getFrontendConfig()->setIncludeJquery(true)->getIncludeJquery();
        if($jquery)
        {
            $update = Mage::app()->getLayout()->getUpdate();
            $update->load(array('default'));
            $xml = $update->asSimplexml();
            $js = $xml->xpath('//action[@method="addJs"]/script');
            dump(array_values($js));
        }
    }



    /**
     * @Transform /^\[(.*)\]$/
     */
    public function castStringToArray($string)
    {
        return explode(',', $string);
    }

    /**
     * @Then :source should be included in the source
     */
    public function shouldBeIncludedInTheSource($source)
    {
        if ('array' !== gettype($source)) {
            throw new Exception('Array of JS/CSS Includes expected');
        }
    }



    /**
     * @Given the page handle :arg1 is within the layout
     */
    public function thePageHandleIsWithinTheLayout($arg1)
    {
        $layout = Mage::app()->getLayout();
        $layout->getUpdate()
            ->addHandle('default')
            ->load();

        /*
         * Generate blocks, but XML from previously loaded layout handles must be
         * loaded first.
         */
        $layout->generateXml()
            ->generateBlocks();

        PHPUnit_Framework_Assert::assertArrayHasKey('social_scroller',$layout->getAllBlocks(),"Block Does Not
        exist");
    }

    /**
     * @Then :arg1 should contain class :arg2
     */
    public function shouldContainClass($arg1, $arg2)
    {

    }
}
