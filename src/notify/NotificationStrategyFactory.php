<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\Notifier\NotifierInterface;
    
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
    
    class NotificationStrategyFactory
    {
        private NotifierInterface       $notifier;
        private ParameterBagInterface   $parameterBag;
        
        public function __construct(NotifierInterface $notifier, ParameterBagInterface $parameterBag)
        {
            $this->notifier             = $notifier;
            $this->parameterBag         = $parameterBag;
        }
        public function EmailStrategy(): EmailNotificationStrategy
        {
            return new EmailNotificationStrategy($this->notifier, $this->parameterBag);
        }
        
        public function NotificationStrategy(): NotificationStrategy
        {
            return new NotificationStrategy($this->notifier, $this->parameterBag);
        }
    }