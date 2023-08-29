<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\Notifier\NotifierInterface;
    
    class EmailNotificationStrategy extends NotificationStrategyAbstract
    {
        private $emails;
        private $include;
        protected $neoxTemplate;
        
        public function __construct(NotifierInterface $notifier, ParameterBagInterface $parameterBag, $neoxTemplate)
        {
            parent::__construct($notifier, $parameterBag, $neoxTemplate);
        }
        
        public function sendNotification(): void
        {
           
            $notification = new EmailCustomer($this, $this->neoxTemplate);
            $notification->subject($this->getSubject() ? : "testing email");
            $notification->content($this->getcontent() ? : "testing email");
            $notification->channels(["email"]);
//            $notification->importance(Notification::IMPORTANCE_HIGH);;
//            $pathTemplate = $this->parameterBag->get("neox_notify.template");
            // send
            $this->notifier->send($notification, $this->getRecipient());
        }
    }