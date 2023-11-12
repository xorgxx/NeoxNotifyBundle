# Mercure flash messager with sweetAlert setup


## Configuration
* Copy & past from  
```
NeoxNotifyBundle
└─── assets
│   └─── controllers
│       └─── coreController.js
│       └─── notify_controller.js

to your root app 
└─── assets
│   └─── controllers
│       └─── coreController.js
│       └─── notify_controller.js
```

## Npm Configuration

npm -i @sweetalert2/themes sweetalert2

in twig Template 
```twig
    {{ stream_notifications() }}  // this is for mercure "PUSH" notification
    {{ neox_notify(topics=['/my/topic/1','/my/topic/2','/my/topic/3']) }} // this is for mercure "wss" notification
```

How to use ?
````php

        // ======================= message notification LEGACY
        // Create
        //  Here send notification to mercure hub topic "*TO ALL*" - 'https://symfony.com/notifier'
        $notification       = $notificationStrategyFactory->NotificationStrategy();
        $msg                = "PUSH --- Flash-1 sales has been started ❤️😉";
        $notification
            ->setNotification(new Notification($msg, ['chat/mercureChatter']))
//            ->setNotification(new Notification($msg, ['chat/mercureChatter']))
            ->send();
        
//        // add to queue
//        $notificationStrategyFactory->addNotification($notification);
//        // If you want to send now -> $notificationStrategyFactory->sendNotifications();
//        // ======================= message notification LEGACY == END =================

        // ======================= message notification Fast & multi notifications

        // Create Mercure
        //  1 - here send first notification to mercure hub topic - /my/topic/1
        //  2 - seconde notification to "flash browser" by sweetAlert
        $o = new Update(
            '/my/topic/1',
            json_encode(["data" => "he he he -Mercure sales has been started ❤️😉", "icon" => "success"], JSON_THROW_ON_ERROR),
            false,
        );
        $notificationStrategyFactory->MercureStrategy()
            ->setNotification( $o, false)
            ->setSweetNotification( "Flash-Mercure XORG sales has been started ❤️😉")
            ->send();
        // ======================= message notification Fast & multi notifications == END =================

        // ======================= message notification Push
        //  Here send notification PUSH to mercure hub topic - /chat/flash-sales
        $message = (new ChatMessage('Push -- Flash sales has been started!', new MercureOptions(['/chat/flash-sales'])))->transport('mercureChatter');
        $notificationStrategyFactory->ChatterStrategy()
            ->setNotification($message)
            ->send();
        // ======================= message notification Push == END =================

        // ======================= message notification Legacy Notification
        // 1 - send notification to "flash browser" by sweetAlert
        $t = $notificationStrategyFactory->MercureStrategy();
        $notification =   $t->setNotification( $o, false);
        // add to queue
        $notificationStrategyFactory->addNotification($notification);
        
        // 2 - send notification to "flash browser" by sweetAlert
        $notification =   $t->setSweetNotification( "Flash-Mercure XORG sales has been started ❤️😉");
        // add to queue
        $notificationStrategyFactory->addNotification($notification);
        // Send all notifications
        $notificationStrategyFactory->sendNotifications();
        // ======================= message notification Legacy Notification == END =================

        
````