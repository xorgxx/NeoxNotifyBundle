<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\EventSubscriber;
    
    //use App\Entity\Messenger;
    //use App\Repository\MessengerRepository;
    use Exception;
    use NeoxNotify\NeoxNotifyBundle\Repository\MessengerRepository;
    use NeoxNotify\NeoxNotifyBundle\Entity\Messenger;
    use Psr\Log\LoggerInterface;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\Mailer\Messenger\SendEmailMessage;
    use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
    use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
    use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;
    use Symfony\Component\Mime\Email;
    use Symfony\Component\Notifier\Message\EmailMessage;
    use Symfony\Component\Notifier\Message\SmsMessage;
    
    
    class NotifierSubscriber implements EventSubscriberInterface
    {
        
//        private MessengerRepository $messengerRepository;
//        private LoggerInterface     $logger;
        
        public function __construct(private readonly  MessengerRepository $messengerRepository, private readonly LoggerInterface $logger, private readonly ParameterBagInterface $parameterBag,)
        {
//            $this->messengerRepository  = $messengerRepository;
//            $this->logger               = $logger;
        }
        
        
        //    https://symfony.com/doc/current/messenger.html#messenger-events
        public function onMessageEvent(WorkerMessageHandledEvent $event): void
        {
            if ($this->parameterBag->get("neox_notify.save_notify")) {
                // gets the message instance
                $message    = $event->getEnvelope()->getMessage();
                $result     = match (true) {
                    $message instanceof SendEmailMessage    => $this->handleSendEmailMessage($message, $event),
                    $message instanceof EmailMessage        => "dede",
                    $message instanceof SmsMessage          => $this->handleSendSmsMessage($message, $event),
                    default => "Message type not recognized",
                };
            }

        }
        
        public function onFailedMessageEvent(WorkerMessageFailedEvent $event): void
        {
            if ($this->parameterBag->get("neox_notify.save_notify")) {
                // gets the message instance
                $message    = $event->getEnvelope()->getMessage();
                $result     = match (true) {
                    $message instanceof SendEmailMessage    => $this->handleFaileEmailMessage($message, $event),
                    $message instanceof EmailMessage        => "dede",
                    $message instanceof SmsMessage          => $this->handleFaileSmsMessage($message, $event),
                    default => "Message type not recognized",
                };
            }

        }
        
        public function onSentMessageEvent(WorkerMessageReceivedEvent $event): void
        {
            // gets the message instance
            
        }
        public static function getSubscribedEvents(): array
        {
            return [
                WorkerMessageHandledEvent::class    => 'onMessageEvent',
                WorkerMessageFailedEvent::class     => 'onFailedMessageEvent',
                WorkerMessageReceivedEvent::class   => 'onSentMessageEvent',
//            SentMessageEvent::class             => 'onSentMessageEvent',
            ];
        }
        
        /**
         * @param SmsMessage          $message
         * @param WorkerMessageHandledEvent $event
         *
         * @return bool
         */
        private function handleSendSmsMessage(SmsMessage $message, WorkerMessageHandledEvent $event): bool
        {
            // Récupérer le contenu de l'email
            /** @var SmsMessage $sms */
            $sms                = $message->getSubject();
            $messageContext     = $this->setContext($message);
            
            // check if existe in Db by uniqID
            if (!$this->messengerRepository->findOneBy(["messengerId"=>$messageContext["neox_uniqId"]])) {
                $this->setInDb($messageContext, $sms, type:"sms");
            }
            
            
            return true;
        }
        
        /**
         * @param SmsMessage                $message
         * @param WorkerMessageFailedEvent  $event
         *
         * @return bool
         */
        private function handleFaileSmsMessage(SmsMessage $message, WorkerMessageFailedEvent $event): bool
        {
            // Récupérer le contenu du sms
            /** @var SmsMessage $sms */
            $sms                = $message->getSubject();
            $messageContext     = $this->setContext($message);
            
            // check if existe in Db by uniqID
            if (!$this->messengerRepository->findOneBy(["messengerId"=>$messageContext["neox_uniqId"]])) {
                $this->setInDb($messageContext, $sms, status: "faile");
                // logger in to error
                $this->logger->error("Messenger error uniqId: " . $messageContext['neox_uniqId'] . " message error : Send sms !!" );
            }
            
            return true;
        }
        
        /**
         * @param SendEmailMessage          $message
         * @param WorkerMessageHandledEvent $event
         *
         * @return bool
         */
        private function handleSendEmailMessage(SendEmailMessage $message, WorkerMessageHandledEvent $event): bool
        {
            // Récupérer le contenu de l'email
            /** @var TemplatedEmail $email */
            $email          = $message->getMessage();
            $Html           = $event->getEnvelope()->last("Symfony\Component\Messenger\Stamp\HandledStamp")->getResult()->getOriginalMessage()->toString();
            
            $emailContext = $this->setContext($email);
            // check if existe in Db by uniqID
            if (!$this->messengerRepository->findOneBy(["messengerId"=>$emailContext["neox_uniqId"]])) {
                $this->setInDb($emailContext, $Html);
            }
            
            return true;
        }
        
        /**
         * @param SendEmailMessage         $message
         * @param WorkerMessageFailedEvent $event
         *
         * @return bool
         */
        private function handleFaileEmailMessage(SendEmailMessage $message, WorkerMessageFailedEvent $event): bool
        {
            // Récupérer le contenu de l'email
            /** @var TemplatedEmail $email */
            $email          = $message->getMessage();
            $faileMessage   = $event->getEnvelope()->last("Symfony\Component\Messenger\Stamp\ErrorDetailsStamp")->getExceptionMessage();
            
            $emailContext = $this->setContext($email);
            
            // check if existe in Db by uniqID
            if (!$this->messengerRepository->findOneBy(["messengerId"=>$emailContext["neox_uniqId"]])) {
                $this->setInDb($emailContext, $faileMessage, status: "faile");
                // logger in to error
                $this->logger->error("Messenger error uniqId: " . $emailContext['neox_uniqId'] . " message error : " . $faileMessage);
            }
            
            return true;
        }
        
        /**
         * @param array  $emailContext
         * @param        $Html
         * @param string $type
         * @param string $status
         *
         * @return void
         */
        private function setInDb(array $emailContext, $Html, string $type = "email", string $status = "send"): void
        {
            // create Messenger
            $messenger = new Messenger();
            $messenger->setMessengerId($emailContext["neox_uniqId"]);
            $messenger->setRecipient($emailContext["neox_recipient"]);
            $messenger->setSender($emailContext["neox_sender"]);
            $messenger->setData($Html);
            $messenger->setChannel([$type]);
            $messenger->setService($type);
            $messenger->setStatus($status);
            
            $this->messengerRepository->save($messenger, true);
        }
        
        /**
         * @param TemplatedEmail | SmsMessage $message
         *
         * @return array
         */
        public function setContext( TemplatedEmail|SmsMessage|Email  $message): array
        {
            if (method_exists($message, 'getContext')) {
                // La méthode getContext() existe dans l'objet $email
                $messageContext = $message->getContext();
            } else {
                
                // La méthode getContext() n'existe pas dans l'objet $email
                $messageContext = [
                    "neox_uniqId"       => uniqid('neox_', true),
                    "neox_recipient"    => $message instanceof SmsMessage ? $message->getPhone() : "system",
                    "neox_sender"       => $message instanceof SmsMessage ? $message->getFrom() : "system",
                ];
            }
            
            //            try {
//                $emailContext   = $email->getContext();
//            } catch (Exception $e) {
//                $emailContext["neox_uniqId"]        = uniqid('neox_fail_');
//                $emailContext["neox_recipient"]     = "system";
//                $emailContext["neox_sender"]        = "system";
//
//            }
            return $messageContext;
        }
    }