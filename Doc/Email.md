# Email messager

How to use ?
````php

        ....
            use NeoxNotify\NeoxNotifyBundle\notify\NotificationStrategyFactory;
            use NeoxNotify\NeoxNotifyBundle\notify\notificationQueue;
        ....

        // ======================= message notification
        $attach_            = $this->getDataAttach( $contact, $email );
        
        $notification       = $notificationStrategyFactory->EmailStrategy()
            ->setSender(new Recipient('exemple@domain.com', "0000000000"))
//                $notification->setRecipient(new Recipient($entity->getEmail()))
            ->setAttachments($attach_)
            ->setTemplate("default")
            ->Subject('test')
            ->content('Content test')
            ->send();

        // ======================= message notification == END =======================
        
        OR
        
        // ======================= message notification LEGACY
        $attach_ = $this->getDataAttach( $contact, $email );
        
        $notificationQueue  = new NotificationQueue();
        $notification       = $notificationStrategyFactory->EmailStrategy();
        $notification->setSender(new Recipient('exemple@domain.com', "0000000000"));
//                $notification->setRecipient(new Recipient($entity->getEmail()));
        $notification->setAttachments($attach_);
        $notification->setTemplate("default");
        $notification->Subject('test');
        $notification->content('Content test');
        $notificationQueue->addNotification($notification);
        $notificationQueue->sendNotifications();
        // ======================= message notification LEGACY == END =======================
````