<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class notificationQueue
    {
        private array $notifications = [];
        
        public function addNotification(NotificationStrategyAbstract|MercureStrategyAbstract $notificationStrategy): void
        {
            $this->notifications[] = clone $notificationStrategy;
        }
        
        public function sendNotifications(): void
        {
            foreach ($this->notifications as $notificationStrategy) {
                $notificationStrategy->sendNotifications();
            }
        }
    }