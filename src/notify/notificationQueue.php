<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    use \NeoxNotify\NeoxNotifyBundle\notify\NotificationStrategyAbstract;
    use \NeoxNotify\NeoxNotifyBundle\notify\MercureStrategyAbstract;
    use \NeoxNotify\NeoxNotifyBundle\notify\ChatterStrategyAbstract;
    use \NeoxNotify\NeoxNotifyBundle\notify\TexterStrategyAbstract;
    
    class notificationQueue
    {
        private array $notifications = [];
        
        public function addNotification(NotificationStrategyAbstract|MercureStrategyAbstract|ChatterStrategyAbstract|TexterStrategyAbstract $notificationStrategy): void
        {
            $this->notifications[] = clone $notificationStrategy;
        }
        
        public function sendNotifications(): void
        {
            array_walk($this->notifications, function ($notificationStrategy, $key) {
                $notificationStrategy->sendNotifications();
                unset($this->notifications[$key]);
            });
  
//            foreach ($this->notifications as $key => $notificationStrategy) {
//                $notificationStrategy->sendNotifications();
//                unset($this->notifications[$key]);
//            }
        }
    }