# Chatter for chat messages

How to use ?
````php

        ....
        use NeoxNotify\NeoxNotifyBundle\notify\NotificationStrategyFactory;
        use NeoxNotify\NeoxNotifyBundle\notify\notificationQueue;
        ....


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

        OR

        // ======================= message notification Push
        //  Here send notification PUSH to mercure hub topic - /chat/flash-sales
        $message = (new ChatMessage('Push -- Flash sales has been started!', new MercureOptions(['/chat/flash-sales'])))->transport('mercureChatter');
        $notificationStrategyFactory->ChatterStrategy()
            ->setNotification($message)
            ->send();
        // ======================= message notification Push == END =================

        
````