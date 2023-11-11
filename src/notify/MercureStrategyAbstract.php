<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    //    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Mercure\HubInterface;
    use Symfony\Component\Mercure\Update;
    use Symfony\Component\Messenger\MessageBusInterface;

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
    
    abstract class MercureStrategyAbstract
    {
        protected HubInterface|null            $hubNotifierInterface;
        protected MessageBusInterface|null     $messageBus;
        private ?Update $notification;
        
        public function __construct( HubInterface $hubNotifierInterface, MessageBusInterface $messageBus, RequestStack $requestStack)
        {
            $this->hubNotifierInterface = $hubNotifierInterface;
            $this->messageBus           = $messageBus;
            $this->requestStack         = $requestStack;
        }
        
        /**
         * @var NotificationQueue
         * Can be use be static BUt dont like this way !!!
         */
        protected static NotificationQueue $notificationQueue;
        
        public static function setNotificationQueue(NotificationQueue $notificationQueue): void
        {
            static::$notificationQueue = $notificationQueue;
        }
        
        public function addToQueue(): void
        {
            if (static::$notificationQueue) {
                static::$notificationQueue->addNotification($this);
            }
        }
        
        abstract public function sendNotification(): void;
        
        public function getNotification(): ?Update
        {
            return $this->notification;
        }
        
        public function setNotification(?Update $notification, bool $async = false): void
        {
            $this->async        = $async;
            $this->notification = $notification;
        }
        
        public function setSweetNotification(string $data, string $topic = null,  string $icon = "success"): void
        {
            
            $topic = $topic ? : '/msg:system/' . $this->requestStack->getCurrentRequest()->getSession()->getId();
            
            $update = new Update(
                $topic,
                json_encode(["data" => $data, "icon" => $icon], JSON_THROW_ON_ERROR),
                false,
            );
            $this->setNotification($update);
            
        }
        
        public function getAsync(): bool
        {
            return $this->async;
        }
    }