<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class MercureStrategy extends MercureStrategyAbstract
    {
        
        public function sendNotification(): void
        {
            $update = $this->getNotification();
            
            // send
            if ( $this->getAsync() ) {
                $this->messageBus->dispatch($update);
            }else {
                $this->hubNotifierInterface->publish($update);
            }
        }
    }