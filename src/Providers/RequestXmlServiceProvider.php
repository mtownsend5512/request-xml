<?php

namespace Mtownsend\RequestXml\Providers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Mtownsend\RequestXml\Exceptions\CouldNotParseXml;
use Mtownsend\XmlToArray\XmlToArray;

class RequestXmlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerWantsXml();
        $this->registerIsXml();
        $this->registerXml();
    }

    /**
     * Determine if the request wants to receive an xml response.
     *
     * @return void
     */
    protected function registerWantsXml()
    {
        Request::macro('wantsXml', function () {
            return Str::contains($this->header('Accept') ?? '', 'xml');
        });
    }

    /**
     * Determine if the request body is xml.
     *
     * @return void
     */
    protected function registerIsXml()
    {
        Request::macro('isXml', function () {
            if (method_exists($this, 'getContentType')) {
                return Str::contains(strtolower($this->getContentType() ?? ''), 'xml');
            }

            return Str::contains(strtolower($this->getContentTypeFormat() ?? ''), 'xml');
        });
    }

    /**
     * Convert xml in a request to an array.
     *
     * @return void
     */
    protected function registerXml()
    {
        Request::macro('xml', function () {
            if (!$this->isXml() || empty($this->getContent())) {
                return [];
            }
            try {
                return XmlToArray::convert($this->getContent()) ?: [];
            } catch (Exception $exception) {
                throw CouldNotParseXml::payload($this->getContent());
            }
        });
    }
}
