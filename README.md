# NeoxNotifyBundle { Symfony 6 }
This bundle provides a simple and flexible to provide "warp" of notification* in your application.
Its main goal is to make it simple for you, give simple command to send by what eve you want : email (with or no attachment), browser, sms .....
Be aware that there is no testing code !

[![2023-08-28-15-43-05.png](https://i.postimg.cc/Njz9rBC5/2023-08-28-15-43-05.png)](https://postimg.cc/3k2Js5TT)

## Installation BETA VERSION !!
Install the bundle for Composer !! as is still on beta version !!

````
  composer require xorgxx/neox-notify-bundle
  or 
  composer require xorgxx/neox-notify-bundle:0.*
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

## How to use ?
```php
myController.php
<?php
....
    use NeoxNotify\NeoxNotifyBundle\notify\NotificationStrategyFactory;
    use NeoxNotify\NeoxNotifyBundle\notify\notificationQueue;
....

        #[Route('/{id}/send', name: 'app_admin_tokyo_crud_send', methods: ['GET'])]
        public function send( Request $request, Tokyo $tokyo, NotificationStrategyFactory $notificationStrategyFactory): Response
        {
            
            $urlToken           = $this->generateUrl("app_home_tokyo_switch",["token" => $tokyo->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
            
            // Create listing Queue
            $notificationQueue  = new NotificationQueue();
            // Create and configure email strategy
            $notification = $notificationStrategyFactory->EmailStrategy();
//            $notification->setRecipient(new Recipient($tokyo->getEmail()));  < --- This will set by default valeur
            $notification->setTemplate("tokyo");
            $notification->Subject('Test ONLY !!');
            $notification->content('....');
            $notification->setContext('urlToken',$urlToken);
            // put in Queue
            $notificationQueue->addNotification($notification);
            
            // ====================== SMS ==========================
            $emailStrategy  = $notificationStrategyFactory->NotificationStrategy();
            $phone          = "+33xxxxxxx";
            $msg            = "Only for testing dev !!";
            $emailStrategy->setNotification((new Notification($msg, ['sms'])));
            $emailStrategy->setRecipient(new NoRecipient());
            // put in Queue
            $notificationQueue->addNotification($emailStrategy);
            
            // ====================== STANDARD NOTIFICATION -> BROWSER =================
            $emailStrategy  = $notificationStrategyFactory->createNotificationStrategy();
            $msg            = "This is test to try ....";
            $emailStrategy->setNotification((new Notification($msg, ['browser'])));
            $emailStrategy->setRecipient(new NoRecipient());
            $notificationQueue->addNotification($emailStrategy);
     
     
            // Send all notifications in the queue
            $notificationQueue->sendNotifications();
            
            $this->addFlash('success', "Un email a été envoyer.");
            // 🔥🔥 The Black magic happens here! 🔥🔥
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('@NeoxTable/msg.stream.html.twig', ["domaine" => "tokyo"]);
        }

```


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