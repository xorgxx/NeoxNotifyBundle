<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\Mercure\HubInterface;
    use Symfony\Component\Mercure\Update;
    use Symfony\Component\Notifier\NotifierInterface;
    
    class MercureStrategy extends MercureStrategyAbstract
    {
        
        public function sendNotification(): void
        {
            $update = $this->getNotification();
            // send
            $this->hubNotifierInterface->publish($update);
        }
    }