<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class notificationQueue
    {
        private array $notifications = [];
        
        public function addNotification(NotificationStrategyAbstract $notificationStrategy): void
        {
            $this->notifications[] = $notificationStrategy;
        }
        
        public function sendNotifications(): void
        {
            foreach ($this->notifications as $notificationStrategy) {
                $notificationStrategy->sendNotification();
            }
        }
    }