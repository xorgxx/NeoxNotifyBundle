# Texter for SMS

How to use ?
````php

        ....
            use NeoxNotify\NeoxNotifyBundle\notify\NotificationStrategyFactory;
            use NeoxNotify\NeoxNotifyBundle\notify\notificationQueue;
        ....

        // ======================= message notification SMS
        //  Here send notification PUSH to mercure hub topic - /chat/flash-sales
        $message = (new SmsMessage("+33xxxxxxxx",'Push -- Flash sales has been started!', "test-tyty"));
        $notificationStrategyFactory->TexterStrategy()
            ->setNotification($message)
            ->send();
        // ======================= message notification SMS == END =================
        
        OR 
        
        // ====================== SMS *legacy* ==========================
        $notification  = $this->notificationStrategyFactory->NotificationStrategy();
        $phone         = "+33xxxxxxxx";
        $msg           = "Only for testing dev !!";            
        $notification->setNotification((new Notification($msg, ['sms'])));
        $notification->setRecipient(new Recipient("null", $phone));
        $notificationQueue->addNotification($notification);
        // put in Queue
        $notificationQueue->addNotification($emailStrategy);
        
````