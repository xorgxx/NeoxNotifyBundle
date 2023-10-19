# NeoxNotifyBundle { Symfony 6 }
This bundle provides a simple and flexible to provide "warp" of notification* in your application.
Its main goal is to make it simple for you, give simple command to send by what eve you want : email (with or no attachment), browser, sms .....
Be aware that there is no testing code !

[![2023-08-28-15-43-05.png](https://i.postimg.cc/Njz9rBC5/2023-08-28-15-43-05.png)](https://postimg.cc/3k2Js5TT)

## Installation !!
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
## news 
* Add transport configuration automatique for service provide by (not free) | Partner [Sms Partner](https://www.smspartner.fr)
```
.env
    .....
      ###> smspartner/SMS ###
      SMSPARTNER_DSN=smspartner://API-KEY:SECRET@api.smspartner.fr/v1/send?from=xxxx&dns=smspartner
      ###> smspartner/SMS ###  
    .....
```

  **NOTE:** _You may need to use [ symfony composer dump-autoload ] to reload autoloading_

 ..... Done 🎈

## !! you style need to make configuration !! 
it at this time we ded not optimize all !!

## Configuration
* Install and configure  ==> [Symfony notifier](https://symfony.com/doc/current/notifier.html#installation)
* Creat folder for template 
```
└─── src
│   └─── Templates
│       └─── _Partial
|           └─── Emails
|               └─── include      <--- this to store the base template
|               └─── template     <--- to store Template
```
## neox_notify.yaml
It set automatique but you can custom
```
  neox_notify:
    template: ~
        include: Partial\include\fdgdgdf
        emails: Partial\emails\
    save_notify: true # by default true mean all notification send will be save in Db messenger. 
    it will give error in Db data. it will also in monolog as log ERROR.
```

it's away possible to custom path twig template to render !

```
  as you can see in code if you setTemplate() to what eve "xxx/xxxx/xxx.tmh.twig it will set.
  
  // try to fund way to be able to have custom path to template
  // $option->getTemplate() == "default" ; null ; "xxxx/xxxxx/default.html.twig"
  $value = $option->getTemplate();
  $Template = match (true) {
    str_contains($value, '/') => $value, 
    default => $this->neoxTemplate['emails'] . "/" . ($option->getTemplate() ? : 'default'). '.html.twig',
  };
```

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
            $notification  = $this->notificationStrategyFactory->NotificationStrategy();
            $phone         = "+" . tokyo->getPhoneNumber()->getCountryCode() . tokyo->getPhoneNumber()->getNationalNumber();
            $msg           = "Only for testing dev !!";            
            $notification->setNotification((new Notification($msg, ['sms'])));
            $notification->setRecipient(new Recipient("null", $phone));
            $notificationQueue->addNotification($notification);
            // put in Queue
            $notificationQueue->addNotification($emailStrategy);
            
            // ====================== STANDARD NOTIFICATION -> BROWSER =================
            $emailStrategy  = $notificationStrategyFactory->createNotificationStrategy();
            $msg            = "This is test to try ....";
            $emailStrategy->setNotification((new Notification($msg, ['browser'])));
            $emailStrategy->setRecipient(new NoRecipient());
            $notificationQueue->addNotification($emailStrategy);
              
            // ========== 🔥🔥 ========== ** MERCURE ** ========== 🔥🔥 =============
            $notification  = $this->notificationStrategyFactory->NotificationStrategy();
            $msg           = "Flash-2 sales has been started ❤️😉";            
            $notification->setNotification((new Notification($msg, ['chat/mercureChatter'])));
            $notification->setRecipient(new NoRecipient());
            $notificationQueue->addNotification($notification);
            // put in Queue
            $notificationQueue->addNotification($emailStrategy);
     
            // Send all notifications in the queue
            $notificationQueue->sendNotifications();
            
            $this->addFlash('success', "Un email a été envoyer.");
            // 🔥🔥 The Black magic happens here! 🔥🔥
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('@NeoxTable/msg.stream.html.twig', ["domaine" => "tokyo"]);
        }

```
## How to use ?
Now is you set save
## By aware !!
All variable you pass in twig going to be set with prefix ["neox_"] this option we choose to avoid conflicts in the template
```php
        $key    = "name"
        $value  = "trying for conflicts avoid"
          public function setContext($key, $value): void
        {
            // Add a prefix to the key to avoid conflicts in the template
            $prefixedKey = "neox_" . $key;
            
            // Set the value in the context array
            $this->context[$prefixedKey] = $value;
        }
        
        <p style="line-height: 100%; font-size: 18px;"><em><strong>{{ "-----" ~ neox_name|default("Message interne") ~ "-----"}}</strong></em></p>
```


## How to use ADVANCE 🎉 ?
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
                        
            // Create listing Queue
            $notificationQueue  = new NotificationQueue();
            
            // ====================== STANDARD NOTIFICATION -> BROWSER =================
            $emailStrategy  = $notificationStrategyFactory->createNotificationStrategy();
            $msg            = "This is test to try ....";
            $emailStrategy->setNotification((new advance($msg, ['browser'])));   <---------- HERE Advance*
            $emailStrategy->setRecipient(new NoRecipient());
            // put in Queue
            $notificationQueue->addNotification($emailStrategy);
     
            // Send all notifications in the queue
            $notificationQueue->sendNotifications();
        }
```
*ADVANCE you can create class with your full logic !! as you will do normally with NotificationBundle. it have to return Notification.



## Contributing
If you want to contribute \(thank you!\) to this bundle, here are some guidelines:

* Please respect the [Symfony guidelines](http://symfony.com/doc/current/contributing/code/standards.html)
* Test everything! Please add tests cases to the tests/ directory when:
    * You fix a bug that wasn't covered before
    * You add a new feature
    * You see code that works but isn't covered by any tests \(there is a special place in heaven for you\)

## Todo
* !!!

## Thanks