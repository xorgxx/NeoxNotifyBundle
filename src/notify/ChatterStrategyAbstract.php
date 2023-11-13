<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    //    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Notifier\Message\ChatMessage;
    use Symfony\Component\Notifier\ChatterInterface;
//    use Symfony\Component\Notifier\NotifierInterface;
    
    /**
     * $notificationQueue = new NotificationQueue();
     * NotificationStrategyAbstract::setNotificationQueue($notificationQueue);
     *
     * ====================== EMAIL ==========================
     * // Create and configure email strategy
     * $emailStrategy = $notificationStrategyFactory->EmailStrategy();
     * $emailStrategy->setRecipient(new Recipient('recipient@example.com'));
     * $emailStrategy->setSubject('Email Notification');
     * $emailStrategy->setMessage('This is an email notification.');
     * $emailStrategy->setContext('szs',50);
     * $emailStrategy->setContext('dede', 50);
     * $notificationQueue->addNotification($emailStrategy);
     *
     * // Send all notifications in the queue
     * $notificationQueue->sendNotifications();
     *
     *
     * ====================== SMS ==========================
     * $emailStrategy  = $notificationStrategyFactory->NotificationStrategy();
     * $phone          = "+33xxxxxxx";
     * $msg            = "Only for testing dev !!";
     * $emailStrategy->setNotification((new Notification($msg, ['browser'])));
     * $emailStrategy->setRecipient(new NoRecipient());
     * $notificationQueue->addNotification($emailStrategy);
     *
     *
     * ====================== STANDARD NOTIFICATION -> BROWSER =================
     * $emailStrategy  = $notificationStrategyFactory->createNotificationStrategy();
     * $msg            = "This is test to try ....";
     * $emailStrategy->setNotification((new Notification($msg, ['browser'])));
     * $emailStrategy->setRecipient(new NoRecipient());
     * $notificationQueue->addNotification($emailStrategy);
     *
     * // Add more notifications to the queue...
     *
     * $notificationQueue->sendNotifications();
     *
     */
    
    abstract class ChatterStrategyAbstract
    {
        protected ChatterInterface $chatter;
        protected RequestStack $requestStack;
        protected notificationQueue $notificationQueue;
        
        public function __construct(ChatterInterface $chatter,RequestStack $requestStack, notificationQueue $notificationQueue)
        {
            $this->chatter              = $chatter;
            $this->requestStack         = $requestStack;
            $this->notificationQueue    = $notificationQueue;
        }

//        abstract public function sendNotifications(): void;
        
        public function getNotification(): ChatMessage
        {
            return $this->notification;
        }
        
        public function setNotification(ChatMessage $notification, bool $async = false): self
        {
            $this->async = $async;
            $this->notification = $notification;
            $this->notificationQueue->addNotification(clone $this);
            return $this;
        }
        
        public function setSweetNotification(string $data, string $topic = null, string $icon = "success"): self
        {
            
            $topic = $topic ?: '/msg:system/' . $this->requestStack->getCurrentRequest()->getSession()->getId();
            
            $update = new Update(
                $topic,
                json_encode(["data" => $data, "icon" => $icon], JSON_THROW_ON_ERROR),
                false,
            );
            
            return $this->setNotification($update);
            
        }
        
        public function getAsync(): bool
        {
            return $this->async;
        }
    }