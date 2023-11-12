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
        protected mixed $neoxTemplate;
        protected notificationQueue $notificationQueue;
        
        public function __construct(NotifierInterface $notifier, ParameterBagInterface $parameterBag, $neoxTemplate = null, notificationQueue $notificationQueue)
        {
            parent::__construct();
            $this->notifier             = $notifier;
            $this->parameterBag         = $parameterBag;
            $this->neoxTemplate         = $neoxTemplate;
            $this->notificationQueue    = $notificationQueue;
            
            // set by default sender & recipient to admin web site address
            $this->setSender(new Recipient($this->parameterBag->get("email-service"), $this->parameterBag->get("sms-service")));
            $this->setRecipient(new Recipient($this->parameterBag->get("email-service"), $this->parameterBag->get("sms-service")));
        }
        
        
        abstract public function sendNotifications(): void;
        
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
        
        public function setAttachment(?string $attachments): void
        {
            $this->attachments[] = $attachments;
        }
        
        public function setAttachments(?array $attachments): void
        {
            if ($attachments) {
                $this->attachments[] = $attachments;
            }
            
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
            // Add a prefix to the key to avoid conflicts in the template
            $prefixedKey = "neox_" . $key;
            
            // Set the value in the context array
            $this->context[$prefixedKey] = $value;
        }
        
        public function getNotification(): ?Notification
        {
            return $this->notification;
        }
        
        public function setNotification(?Notification $notification): self
        {
            $this->notification = $notification;
            $this->notificationQueue->addNotification(clone $this);
            return $this;
        }
        
    }