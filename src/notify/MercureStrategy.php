<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class MercureStrategy extends MercureStrategyAbstract
    {
        
        public function sendNotification(): void
        {
            $update = $this->getNotification();
            // send
            $this->hubNotifierInterface->publish($update);
        }
    }