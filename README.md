# NeoxNotifyBundle { Symfony 6 }
This bundle provides a simple and flexible to provide "warp" of notification* in your application.
Its main goal is to make it simple for you, give simple command to send by what eve you want : email (with or no attachment), browser, sms .....
Be aware that there is no testing code !

[![2023-08-28-15-43-05.png](https://i.postimg.cc/Njz9rBC5/2023-08-28-15-43-05.png)](https://postimg.cc/3k2Js5TT)

## Installation BETA VERSION !!
Install the bundle for Composer !! as is still on beta version !!

````
  composer require xorgxx/neoxNotifyBundle
  or 
  composer require xorgxx/neoxNotifyBundle:0.*
````

Make sure that is register the bundle in your AppKernel:
```php
Bundles.php
<?php

return [
    .....
    NeoxNotify\neoxNotifyBundle\neoxNotifyBundle::class => ['all' => true],
    .....
];
```

**NOTE:** _You may need to use [ symfony composer dump-autoload ] to reload autoloading_

 ..... Done 🎈

## !! you style need to make configuration !! 
it at this time we ded not optimize all !!

## Configuration
* Install and configure  [Symfony notifier](https://symfony.com/doc/current/notifier.html#installation)
* Creat folder for template 
```
└─── src
│   └─── Templates
│       └─── _Partial
|           └─── Emails
|               └─── include      <--- this to store the base template
|               └─── template     <--- to store Template
```
Configuration except that you have install stimulus/turbo-ux and setup correctly !!
Base css on Bootstrap 5 so if you have install on your project all css and js from Bs5 going to be applique.


## Contributing
If you want to contribute \(thank you!\) to this bundle, here are some guidelines:

* Please respect the [Symfony guidelines](http://symfony.com/doc/current/contributing/code/standards.html)
* Test everything! Please add tests cases to the tests/ directory when:
    * You fix a bug that wasn't covered before
    * You add a new feature
    * You see code that works but isn't covered by any tests \(there is a special place in heaven for you\)

## Todo
* Packagist

## Thanks