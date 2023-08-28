<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\notify;
    
    class EmailNotificationStrategy extends NotificationStrategyAbstract
    {
        public function sendNotification(): void
        {
            $notification = new EmailCustomer($this);
            $notification->subject($this->getSubject() ? : "testing email");
            $notification->content($this->getcontent() ? : "testing email");
            $notification->channels(["email"]);
//            $notification->importance(Notification::IMPORTANCE_HIGH);;
            
            // send
            $this->notifier->send($notification, $this->getRecipient());
        }
    }