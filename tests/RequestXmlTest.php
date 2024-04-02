<?php

use Illuminate\Http\Request;
use Mtownsend\RequestXml\Providers\RequestXmlServiceProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass as Reflect;

class RequestXmlTest extends TestCase
{

    /** @test array */
    protected $testArray = [];

    /** @test string */
    protected $testXml;

    public function setUp(): void
    {
        $this->createDummyprovider()->register();

        $this->testArray = [
            'carrier' => 'fedex',
            'id' => 123,
            'tracking_number' => '9205590164917312751089'
        ];
        $this->testXml = '<?xml version="1.0"?><root><carrier>fedex</carrier><id>123</id><tracking_number>9205590164917312751089</tracking_number></root>';
    }

    /**
     * Create a mock request
     *
     * @return Illuminate\Http\Request
     */
    public function createDummyRequest($headers = [], $payload = null): Request
    {
        return new Request([], [], [], [], [], $headers, $payload);
    }

    /**
     * Bootstrap the provider to introduce the xml macros
     *
     */
    protected function createDummyprovider(): RequestXmlServiceProvider
    {
        $reflectionClass = new Reflect(RequestXmlServiceProvider::class);
        return $reflectionClass->newInstanceWithoutConstructor();
    }

    /**
     * Remove new lines from xml to standardize testing
     *
     */
    protected function removeNewLines($string)
    {
        return preg_replace('~[\r\n]+~', '', $string);
    }

    /** @test */
    public function request_wants_xml_based_on_accept_header()
    {
        $request = $this->createDummyRequest([], $this->testXml);
        $request->headers->set('Accept', 'text/xml');
        $this->assertTrue($request->wantsXml());
    }

    /** @test */
    public function request_is_xml()
    {
        $request = $this->createDummyRequest(['CONTENT_TYPE' => 'application/xml'], $this->testXml);
        $this->assertTrue($request->isXml());
    }

    /** @test */
    public function request_can_retrieve_xml_as_array()
    {
        $request = $this->createDummyRequest(['CONTENT_TYPE' => 'application/xml'], $this->testXml);
        $this->assertIsArray($request->xml());
    }

    /** @test */
    public function request_can_parse_xml_as_a_valid_array()
    {
        $request = $this->createDummyRequest(['CONTENT_TYPE' => 'application/xml'], $this->testXml);
        $this->assertEquals($request->xml(), $this->testArray);
    }
}
