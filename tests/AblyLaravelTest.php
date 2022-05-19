<?php

use Ably\AblyRest;
use Ably\Http;
use Ably\Utils\Miscellaneous;

class AblyLaravelTest extends Orchestra\Testbench\TestCase
{
    const TEST_KEY = 'unit.test:key';

    public function setUp(): void
    {
        parent::setUp();

        $this->app->config->set('ably', [
            'key' => self::TEST_KEY,
        ]);
    }

    protected function getPackageProviders($app)
    {
        return ['Ably\Laravel\AblyServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Ably' => 'Ably\Laravel\Facades\Ably'
        ];
    }

    /**
     * Retrieves a key from an Ably\Auth instance, which is
     * normally not accessible, via reflection.
     */
    protected static function retrieveKeyFromAblyAuth($ablyAuth)
    {
        $authRefl = new ReflectionObject($ablyAuth);
        $optionsProperty = $authRefl->getProperty('defaultAuthOptions');
        $optionsProperty->setAccessible(true);
        return $optionsProperty->getValue($ablyAuth)->key;
    }

    /**
     * Verify if the configuration is read properly
     */
    public function testConfig()
    {
        $config = config('ably');
        $this->assertEquals(self::TEST_KEY, $config['key']);
    }

    /**
     * Verify that the service provider works
     */
    public function testServiceProvider()
    {
        $ably = App::make('ably'); // retrieve singleton instance via service provider
        $this->assertInstanceOf(\Ably\AblyRest::class, $ably);

        $this->assertEquals(self::TEST_KEY, self::retrieveKeyFromAblyAuth($ably->auth)); // check if the instance has the proper auth key
    }

    /**
     * Verify that the facade works
     */
    public function testFacade()
    {
        $this->assertGreaterThan(0, Miscellaneous::systemTime()); // verify that function calls work
        
        $this->assertInstanceOf(\Ably\Auth::class, Ably::auth()); // verify that accessing members as function calls works

        $this->assertEquals(self::TEST_KEY, self::retrieveKeyFromAblyAuth(Ably::auth())); // check if the instance has the proper auth key
    }

    /**
     * Verify that AblyFactory works
     */
    public function testFactory()
    {
        $ablyFactory = App::make('\Ably\Laravel\AblyFactory');
        $customKey = 'another.test:key';

        $ably1 = $ablyFactory->make(); // get a new instance with the default configuration
        $ably2 = $ablyFactory->make([ 'key' => $customKey ]); // get a new instance with custom configuration

        $this->assertEquals(self::TEST_KEY, self::retrieveKeyFromAblyAuth($ably1->auth));
        $this->assertEquals($customKey, self::retrieveKeyFromAblyAuth($ably2->auth));
    }

    /**
     * Verify that AblyService works
     */
    public function testService()
    {
        $ably = App::make('\Ably\Laravel\AblyService');
        $this->assertInstanceOf(\Ably\Laravel\AblyService::class, $ably);

        $this->assertGreaterThan(0, Miscellaneous::systemTime()); // verify that function calls work

        $this->assertInstanceOf(\Ably\Auth::class, $ably->auth); // verify that accessing members works
        
        $this->assertEquals(self::TEST_KEY, self::retrieveKeyFromAblyAuth($ably->auth)); // check if the instance has the proper auth key
    }

    /**
     * Verify that the service provider, facade and AblyService always return
     * the same instance. AblyFactory must always generate new instances.
     */
    public function testInstances()
    {
        $ablyFactory = App::make('\Ably\Laravel\AblyFactory');

        $ablySPAuth = App::make('ably')->auth;
        $ablySPAuth2 = App::make('ably')->auth;
        $ablyServiceAuth = App::make('\Ably\Laravel\AblyService')->auth;
        $ablyServiceAuth2 = App::make('\Ably\Laravel\AblyService')->auth;
        $ablyFacadeAuth = Ably::auth();
        $ablyFacadeAuth2 = Ably::auth();
        $factoryInstanceAuth = $ablyFactory->make()->auth;
        $factoryInstanceAuth2 = $ablyFactory->make()->auth;

        $this->assertSame($ablySPAuth, $ablySPAuth2);
        $this->assertSame($ablySPAuth, $ablyServiceAuth);
        $this->assertSame($ablySPAuth, $ablyServiceAuth2);
        $this->assertSame($ablySPAuth, $ablyFacadeAuth);
        $this->assertSame($ablySPAuth, $ablyFacadeAuth2);

        $this->assertNotSame($ablySPAuth, $factoryInstanceAuth);
        $this->assertNotSame($factoryInstanceAuth, $factoryInstanceAuth2);

        $this->assertInstanceOf(\Ably\Auth::class, $ablySPAuth);
        $this->assertInstanceOf(\Ably\Auth::class, $factoryInstanceAuth);
        $this->assertInstanceOf(\Ably\Auth::class, $factoryInstanceAuth2);
    }

    public function testLaravelAblyAgentHeader()
    {
        $ablyFactory = App::make('\Ably\Laravel\AblyFactory');
        $ably = $ablyFactory->make([
            'key' => self::TEST_KEY,
            'httpClass' => 'HttpMock',
        ]);

        $ably->time();
        $expectedLaravelHeader = 'ably-php/'.\Ably\Defaults::LIB_VERSION.' '.'php/'.Miscellaneous::getNumeric(phpversion()).' laravel/'.app()->version();
        $this->assertcontains( 'Ably-Agent: '.$expectedLaravelHeader, $ably->http->lastHeaders, 'Expected PHP laravel header in HTTP request' );
    }
}


class HttpMock extends Http
{
    public $lastUrl;
    public $lastHeaders;
    
    public function request($method, $url, $headers = array(), $params = array())
    {
        $this->lastUrl = $url;
        $this->lastHeaders = $headers;

        // mock response to /time
        return array(
            'headers' => "HTTP/1.1 200 OK\n",
            'body' => array(round(microtime(true) * 1000 ), 0),
        );
    }
}
