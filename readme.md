# Easy API response

## Installation

First, pull in the package through Composer.

```js
"require": {
    "mandeep03/api": "dev-master"
},
  "repositories": [
    {
        "type": "git",
        "url": "https://MandeepBot:MnPs4731@bitbucket.org/mandeep03/api.git"
    }
    ],
```

And then, include the service provider within `config/app.php`.

```php
'providers' => [
    mandeep03\api\ApiProvider::class
];
```

And, for convenience, add a facade alias to this same file at the bottom:

```php
'aliases' => [
    'api' => mandeep03\api\ApiFacade::class
];
```

Publish the configurations by running this artisan command:

```js
php artisan vendor:publish
```

## Usage

- `api::success(['data'=>'random data'])`
- `api::notFound(['errorMsg'=> 'user not found'])`
- `api::notAuth(['errorMsg'=> 'user not authorized'])`
- `api::notValid(['errorMsg'=> 'email field is required'])`
- `api::serverError(['errorMsg'=> 'soemthing went wrong'])`



Alternatively, you may reference the `api()` helper function, instead of the facade. Here's an example:


    api()->success(['data' => 'some data']);
    api()->notFound(['errorMsg' => 'something nto found']);


Or, for a success API response, just do: `api(['data' => 'Some message']);`.

## Method chaining

You can make use of method chaining:


```
#!php

api()->data(['data'=> 'my data'])->statusCode(201)->success();
api()->json()->notFound();

```