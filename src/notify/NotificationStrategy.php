<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class NotificationStrategy extends NotificationStrategyAbstract
    {
        public function sendNotification(): void
        {
            // send
            $this->notifier->send($this->getNotification(), $this->getRecipient());
        }
    }