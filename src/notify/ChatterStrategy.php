<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class ChatterStrategy extends ChatterStrategyAbstract
    {
        
        public function send(){
            $this->notificationQueue->sendNotifications();
        }
        public function sendNotifications(): void
        {
            $message = $this->getNotification();
            $this->chatter->send($message);
        }
    }