<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    use \NeoxNotify\NeoxNotifyBundle\notify\NotificationStrategyAbstract;
    use \NeoxNotify\NeoxNotifyBundle\notify\MercureStrategyAbstract;
    use \NeoxNotify\NeoxNotifyBundle\notify\ChatterStrategyAbstract;
    
    class notificationQueue
    {
        private array $notifications = [];
        
        public function addNotification(NotificationStrategyAbstract|MercureStrategyAbstract|ChatterStrategyAbstract $notificationStrategy): void
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