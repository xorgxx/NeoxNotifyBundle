<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace NeoxNotify\NeoxNotifyBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The model of e-mail package used to send out with SwiftMailer.
 */
class Email
{

    #[Assert\NotBlank(message: 'email.subject.NotBlank')]
    #[Assert\Length(min: 3, max: 250, minMessage: 'email.subject.Length.minMessage', maxMessage: 'email.subject.Length.maxMessage')]
    protected string $subject;

//    #[Assert\NotBlank(message: 'email.recipient.NotBlank')]
//    #[Assert\Length(min: 10, max: 250, minMessage: 'email.recipient.Length.minMessage', maxMessage: 'email.recipient.Length.maxMessage')]
    protected string $recipient;

    #[Assert\NotBlank(message: 'email.name.NotBlank')]
    #[Assert\Length(min: 3, max: 250, minMessage: 'email.name.Length.minMessage', maxMessage: 'email.name.Length.maxMessage')]
    protected string $senderName;

    #[Assert\NotBlank(message: 'email.sender.NotBlank')]
    #[Assert\Length(min: 8, max: 250, minMessage: 'email.sender.Length.minMessage', maxMessage: 'email.sender.Length.maxMessage')]
    protected string $sender;

    #[Assert\NotBlank(message: 'email.message.NotBlank')]
    #[Assert\Length(min: 50, max: 1500, minMessage: 'email.message.Length.minMessage', maxMessage: 'email.message.Length.maxMessage')]
    protected string $message;
    
    public function getSubject(): string
    {
        return $this->subject;
    }
    
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
    
    public function getRecipient(): string
    {
        return $this->recipient;
    }
    
    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }
    
    public function getSenderName(): string
    {
        return $this->senderName;
    }
    
    public function setSenderName(string $senderName): void
    {
        $this->senderName = $senderName;
    }
    
    public function getSender(): string
    {
        return $this->sender;
    }
    
    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }
    
    public function getMessage(): string
    {
        return $this->message;
    }
    
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
    
}