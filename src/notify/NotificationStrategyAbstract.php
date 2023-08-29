<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\Notifier\Notification\Notification;
    use Symfony\Component\Notifier\NotifierInterface;
    use Symfony\Component\Notifier\Recipient\NoRecipient;
    use Symfony\Component\Notifier\Recipient\Recipient;
    
    abstract class NotificationStrategyAbstract extends notification
    {
        
        protected Recipient $sender;
        protected Recipient|NoRecipient|null $recipient = null;
        protected string $template                      = "default";
        protected array|null $attachments               = [];
        protected array|null $context                   = [];
        protected Notification|null $notification       = null;
        
        protected NotifierInterface $notifier;
        protected ParameterBagInterface $parameterBag;
        protected $neoxTemplate;
        
        public function __construct(NotifierInterface $notifier, ParameterBagInterface $parameterBag, $neoxTemplate = null)
        {
            parent::__construct();
            $this->notifier         = $notifier;
            $this->parameterBag     = $parameterBag;
            $this->neoxTemplate     = $neoxTemplate;
            
            // set by default sender & recipient to admin web site address
            $this->setSender(new Recipient($this->parameterBag->get("email-service"), $this->parameterBag->get("sms-service")));
            $this->setRecipient(new Recipient($this->parameterBag->get("email-service"), $this->parameterBag->get("sms-service")));
            
            
            
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
        
        public function getSender(): Recipient
        {
            return $this->sender;
        }
        
        public function setSender(Recipient $sender): void
        {
            $this->sender = $sender;
        }
        
        public function getRecipient(): Recipient|NoRecipient|null
        {
            return $this->recipient;
        }
        
        public function setRecipient(Recipient|NoRecipient|null $recipient): void
        {
            $this->recipient = $recipient;
        }
        
        public function getAttachments(): ?array
        {
            return $this->attachments;
        }
        
        public function setAttachments(?string $attachments): void
        {
            $this->attachments[] = $attachments;
        }
        
        public function getTemplate(): ?string
        {
            return $this->template;
        }
        
        public function setTemplate(?string $template): void
        {
            $this->template = $template;
        }
        
        public function getContext(): ?array
        {
            return $this->context;
        }
        
        public function setContext($key, $value): void
        {
            $this->context[$key] = $value;
        }
        
        public function getNotification(): ?Notification
        {
            return $this->notification;
        }
        
        public function setNotification(?Notification $notification): void
        {
            $this->notification = $notification;
        }
        
    }