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
## !! IMPORTANT NOTE !!
All transports Mercure, RabbitMQ .... have to be installed in order to use them !!!
NeoxNotifyBundle will use your transport configuration. be aware that first you need to install and to set them properly, then only notify can work.
* NeoxNotifyBundle will not install and set for you Mercure, RabbitMQ ....


## news 
* Add transport configuration automatic for service provide by (not free) | Partner [Sms Partner](https://www.smspartner.fr)
* Add reporting send SMS as emailSend in dataBase + in add in symfony logger if it failed
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
    parameters:
        neox_notify:
            template: ~
                include: Partial\include\fdgdgdf
                emails: Partial\emails\
            save_notify: true # by default true mean all notification send will be save in Db messenger. 
            it will give error in Db data. it will also in monolog as log ERROR.
         
            service:
                channels: [] # email, slack, mercure, webhook, ...
                subject: subject
                template: default
                content: ....
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
            
            ......
    
        }

```
## Send notify be using Attribute !!

```php
myController.php
<?php
....
  use NeoxNotify\NeoxNotifyBundle\Attribute\NeoxNotifyAlert;
....

        #[Route('/{id}/send', name: 'app_admin_tokyo_crud_send', methods: ['GET'])]
        #[NeoxNotifyAlert(channels: ["email", "sms"], template: 'default', subject: 'Download file', content: '....', contexts: ["name" => "windev"])]
        public function send( Request $request, Tokyo $tokyo, NotificationStrategyFactory $notificationStrategyFactory): Response
        {
                     
            ......
    
        }
        
        all is set by default! attributes :
        channels: ["email", "sms"]      -> chose witch channel you want to use.
        template: 'default'             -> template name in folder "emails: Partial\emails\"
        subject: 'Download file'        -> subject of email
        content: '....'                 -> content/body email, sms, ....
        
        ------------ !!!! SUBJECT and CONTEXTS are special !!!! ------------------------------
        contexts: [ "name" => "file", "opt" => "[attributes.file;attributes.controller]"], 
        subject: "Download file [attributes.file;attributes.controller]"
        
        In subject | add "xxxxx xxxxx [attributes.file;attributes.controller]" in subject it will read in the Request object -> attribute-get("file")
        In contexts: [ "opt" => "[attributes.file;attributes.controller]"] it will read in the Request object -> attribute-get("file") and pass (neox_file, neox_controller) to twig in [template: 'default'] 
        
```

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
* more abort Email notify  [EMAIL]( Doc/Email.md )
* more abort SMS notify  [SMS]( Doc/Texter.md )
* more abort CHAT notify  [CHAT]( Doc/Chatter.md )
* more abort Mercure set flash  [set flash ]( Doc/Mercure.md )

* ADVANCE you can create class with your full logic !! as you will do normally with NotificationBundle. it have to return Notification.



## Contributing
If you want to contribute \(thank you!\) to this bundle, here are some guidelines:

* Please respect the [Symfony guidelines](http://symfony.com/doc/current/contributing/code/standards.html)
* Test everything! Please add tests cases to the tests/ directory when:
    * You fix a bug that wasn't covered before
    * You add a new feature
    * You see code that works but isn't covered by any tests \(there is a special place in heaven for you\)

## Todo

## Thanks