<?php

namespace App\EventSubscriber;

//use App\Entity\Messenger;
//use App\Repository\MessengerRepository;
use NeoxNotify\NeoxNotifyBundle\Repository\MessengerRepository;
use NeoxNotify\NeoxNotifyBundle\Entity\Messenger;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;
use Symfony\Component\Notifier\Message\EmailMessage;



class NotifierSubscriber implements EventSubscriberInterface
{
    
    private MessengerRepository $messengerRepository;
    
    public function __construct(MessengerRepository $messengerRepository)
    {
        $this->messengerRepository = $messengerRepository;
    }
    
    //    https://symfony.com/doc/current/messenger.html#messenger-events
    public function onMessageEvent(WorkerMessageHandledEvent $event): void
    {
        // gets the message instance
        $message = $event->getEnvelope()->getMessage();
        $result = match (true) {
            $message instanceof SendEmailMessage => $this->handleSendEmailMessage($message, $event),
            $message instanceof EmailMessage => "dede",
            default => "Message type not recognized",
        };
    }

    public function onFailedMessageEvent(WorkerMessageFailedEvent $event): void
    {

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
     * @param SendEmailMessage          $message
     * @param WorkerMessageHandledEvent $event
     *
     * @return bool
     */
    public function handleSendEmailMessage(SendEmailMessage $message, WorkerMessageHandledEvent $event): bool
    {
        // Récupérer le contenu de l'email
        /** @var TemplatedEmail $email */
        $email          = $message->getMessage();
        $emailContext   = $email->getContext();
        $Html           = $event->getEnvelope()->last("Symfony\Component\Messenger\Stamp\HandledStamp")->getResult()->getOriginalMessage()->toString();
        
        if (!$this->messengerRepository->findOneBy(["messengerId"=>$emailContext["neox_uniqId"]])) {
            // create Messenger
            $messenger  = new Messenger();
            $messenger->setMessengerId($emailContext["neox_uniqId"]);
            $messenger->setRecipient($emailContext["recipient"]);
            $messenger->setSender($emailContext["sender"]);
            $messenger->setData($Html);
            $messenger->setChannel(["email"]);
            $messenger->setService("email");
            $messenger->setStatus("send");
            
            $this->messengerRepository->save($messenger, true);
        }
        return true;
    }
}