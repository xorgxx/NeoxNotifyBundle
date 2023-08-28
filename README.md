# NeoxNotifyBundle { Symfony 6 }
This bundle provides a simple and flexible to provide "warp" of notification* in your application.
Its main goal is to make it simple for you, give simple command to send by what eve you want : email (with or no attachment), browser, sms .....
Be aware that there is no testing code !

[![2023-05-24-12-05-31.png](https://i.postimg.cc/zfLGNQ3r/2023-05-24-12-05-31.png)](https://postimg.cc/rdkkCQSn)
[![2023-05-05-00-08-37.png](https://i.postimg.cc/K8DnLR5z/2023-05-05-00-08-37.png)](https://postimg.cc/FY1dXF85)

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