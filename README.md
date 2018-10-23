The missing XML support for Laravel's Request class.

This package is designed to work with the [Laravel](https://laravel.com) framework.

## Installation

Install via composer:

```
composer require mtownsend/request-xml
```

### Registering the service provider

For Laravel 5.4 and lower, add the following line to your ``config/app.php``:

```php
/*
 * Package Service Providers...
 */
Mtownsend\RequestXml\Providers\RequestXmlServiceProvider::class,
```

For Laravel 5.5 and greater, the package will auto register the provider for you.

### Using Lumen

To register the service provider, add the following line to ``app/bootstrap/app.php``:

```php
$app->register(Mtownsend\RequestXml\Providers\RequestXmlServiceProvider::class);
```

### Middleware

It's important to register the middleware so your application can convert an XML request and merge it into the Request object. You will then be able to run XML through Laravel's powerful validation system.

**Once you register the middleware, you do not need to do anything special to access your request xml. It will be available in the Request object like it would if it was a json or form request.**

To setup the middleware, open up your ``app/Http/Kernel.php`` file.

To add the middleware globally:

```php
protected $middleware = [
    Mtownsend\RequestXml\Middleware\XmlRequest::class,
];
```

To add the middleware to web routes only:

```php
protected $middlewareGroups = [
    'web' => [
        \Mtownsend\RequestXml\Middleware\XmlRequest::class,
    ],
];
```

To add the middleware to api routes only:

```php
protected $middlewareGroups = [
    'api' => [
        \Mtownsend\RequestXml\Middleware\XmlRequest::class,
    ],
];
```

Or, if you want named middleware for specific routes:

```php
protected $routeMiddleware = [
    'xml' => \Mtownsend\RequestXml\Middleware\XmlRequest::class,
];
```

## Quick start

### Determine if the request wants an xml response

```php
if (request()->wantsXml()) {
    // send xml response
}
```

### Determine if the request contains xml

```php
if (request()->isXml()) {
    // do something
}
```

### Get the converted xml as an array

```php
$data = request()->xml();
```

## Methods

**Request method**

``->wantsXml()``

Works very similar to Laravel's ``->wantsJson()`` method by returning a boolean. It will tell you if the incoming request would like to receive an XML response back.

**Request method**

``->isXml()``

Returns a boolean. This will tell you if the incoming request is XML.

**Request method**

``->xml()``

Returns an array. This converts the XML request into a PHP array. You are free to cast it to an object:

```php
$xml = (object) request()->xml();
```

Or wrap it in a collection:

```php
$xml = collect(request()->xml());
```

## Exceptions

In the event invalid XML is received in a request, the application will throw an Exception containing the raw, invalid XML: If you would like to handle this exception whenever it occurs in your application, you can easily catch it and supply your own code in your applications ``app/Exceptions/Handler.php`` like so:

```php
if ($exception instanceof \Mtownsend\RequestXml\Exceptions\CouldNotParseXml) {
    // do something
}
```

## Purpose

Have you ever wondered why Laravel offered useful methods for transforming data into JSON but completely forgot about XML? This package aims to add the missing XML functionality to Laravel's Request class. Your Laravel application may now detect and auto-merge incoming XML requests into the Request object. You can run an XML request through Laravel's built in validation system - it just works! XML's days of being a second class citizen in your Laravel app have come to an end.

## Other packages you may be interested in

- [mtownsend/collection-xml](https://github.com/mtownsend5512/collection-xml)
- [mtownsend/response-xml](https://github.com/mtownsend5512/response-xml)
- [mtownsend/xml-to-array](https://github.com/mtownsend5512/xml-to-array)

## Credits

- Mark Townsend
- All Contributors

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.