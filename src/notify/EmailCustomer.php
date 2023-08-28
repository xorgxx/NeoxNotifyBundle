<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    use finfo;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Component\Notifier\Message\EmailMessage;
    use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
    use Symfony\Component\Notifier\Notification\Notification;
    use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
    
    class EmailCustomer extends Notification implements EmailNotificationInterface
    {
        private NotificationStrategyAbstract $option;
        
        /**
         * @param NotificationStrategyAbstract $option
         */
        public function __construct(NotificationStrategyAbstract $option )
        {
            parent::__construct();
            $this->option = $option;
        }
        
        public function asEmailMessage(EmailRecipientInterface $recipient, string $transport = null): ?EmailMessage
        {
            $option     = $this->option;
            $message    = EmailMessage::fromNotification( $this, $recipient, $transport );
            /**
             * custom Email - as emailMessage
             *
             * @var TemplatedEmail $email
             * markAsPublic() take all annotation symfony.
             */
            $email = $message->getMessage()->markAsPublic();
            $email->htmlTemplate( 'Partial/Emails/' . ($option->getTemplate() ? : 'default'). '.html.twig' );
            $option->setContext("subject",$option->getSubject());
            $option->setContext("content",$option->getContent());
            
            // define to retrieve later in the WorkerMessageHandledEvent $event to maybe keep history Db of messenger
            $option->setContext("uniqId",uniqid());
            
            $option->setContext("sender",$option->getSender()->getEmail());
            $option->setContext("recipient",$option->getRecipient()->getEmail());
            $email->context(($option->getContext() ? : null));
            
            /**
             * set if attach files
             * return mime type ala mimetype extension
             **/
            $fInfo = new finfo(FILEINFO_MIME);
            foreach ( $option->getAttachments() as $key => $attach ) {
                $mineType   = $fInfo->file($attach);
                $email->attach(file_get_contents($attach), $key,$mineType);
            }
            
            return $message;
        }
        
    }