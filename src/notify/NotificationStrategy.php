<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class NotificationStrategy extends NotificationStrategyAbstract
    {
        public function sendNotifications(): void
        {
            // send
            $this->notifier->send($this->getNotification(), $this->getRecipient());
        }
    }