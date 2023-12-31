<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Mercure\HubInterface;
    use Symfony\Component\Messenger\MessageBusInterface;
    use Symfony\Component\Notifier\NotifierInterface;
    use Symfony\Component\Notifier\ChatterInterface;
    use Symfony\Component\Notifier\TexterInterface;
    
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
    
    class NotificationStrategyFactory extends notificationQueue
    {
        private RequestStack                    $requestStack;
        private NotifierInterface               $notifier;
        private ChatterInterface                $chatter;
        private ParameterBagInterface           $parameterBag;
        private mixed                           $neoxTemplate;
        private HubInterface                    $hub;
        private MessageBusInterface             $messageBus;
        private notificationQueue               $notificationQueue;
        
        public function __construct(
            RequestStack $requestStack,
            NotifierInterface $notifier,
            ChatterInterface $chatter,
            TexterInterface $texter,
            ParameterBagInterface $parameterBag,
            mixed $neoxTemplate,
            HubInterface $hub,
            MessageBusInterface $messageBus,
            notificationQueue $notificationQueue
        )
        {
            $this->requestStack         = $requestStack;
            $this->notifier             = $notifier;
            $this->chatter              = $chatter;
            $this->texter               = $texter;
            $this->parameterBag         = $parameterBag;
            $this->neoxTemplate         = $neoxTemplate;
            $this->hub                  = $hub;
            $this->messageBus           = $messageBus;
            $this->notificationQueue    = $notificationQueue;
        }
        
        /**
         * Not really good option !! maybe TODO ?
         * public static function create(NotifierInterface $notifier, ParameterBagInterface $parameterBag, mixed $neoxTemplate): self
         * {
         *    return new self($notifier, $parameterBag, $neoxTemplate);
         * }
         * */
        
        public function EmailStrategy(): EmailNotificationStrategy
        {
            return new EmailNotificationStrategy($this->notifier, $this->parameterBag, $this->notificationQueue, $this->neoxTemplate);
        }
        
        public function NotificationStrategy(): NotificationStrategy
        {
            return new NotificationStrategy($this->notifier, $this->parameterBag, $this->notificationQueue, null);
        }
        
        public function MercureStrategy(): MercureStrategy
        {
            return new MercureStrategy($this->hub, $this->messageBus, $this->requestStack, $this->notificationQueue);
        }
        
        public function ChatterStrategy(): ChatterStrategy
        {
            return new ChatterStrategy($this->chatter, $this->requestStack, $this->notificationQueue);
        }
        
        public function TexterStrategy(): TexterStrategy
        {
            return new TexterStrategy($this->texter, $this->notificationQueue);
        }
    }