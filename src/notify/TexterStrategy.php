<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class TexterStrategy extends TexterStrategyAbstract
    {
        
        public function send(){
            $this->notificationQueue->sendNotifications();
        }
        public function sendNotifications(): void
        {
            $message = $this->getNotification();
            $this->texter->send($message);
        }
    }