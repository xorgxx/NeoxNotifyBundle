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
        private array $neoxTemplate;
        
        
        /**
         * @param NotificationStrategyAbstract $option
         * @param array                        $neoxTemplate
         */
        public function __construct(NotificationStrategyAbstract $option, array $neoxTemplate )
        {
            parent::__construct();
            $this->option           = $option;
            $this->neoxTemplate     = $neoxTemplate;
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
             * !!! neox_[xxxx] SETTING FOR TWIG PREFIX !!!  >> $option->setContext
             */
            
            // try to fund way to be able to have custom path to template
            // $option->getTemplate() == "default" ; null ; "xxxx/xxxxx/default.html.twig"
            $value = $option->getTemplate();
            $Template = match (true) {
                str_contains($value, '/') => $value,
                default => $this->neoxTemplate['emails'] . "/" . ($option->getTemplate() ? : 'default'). '.html.twig',
            };
            
            $email = $message->getMessage()->markAsPublic();
            $email->htmlTemplate( $Template );
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
            $fInfo      = new finfo(FILEINFO_MIME);     // Create a new finfo object with FILEINFO_MIME flag
            $attachs    = $option->getAttachments();        // Get the attachments from the $option object
            
            // Iterate through the attachments recursively (optimize for good)
            array_walk_recursive($attachs, function ($value, $key) use ($email, $fInfo) {
                $email->attach(file_get_contents($value), $key, $fInfo->file($value)); // Attach the file to the email
            });
            
            return $message;
        }
        
    }