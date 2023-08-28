<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\Entity;
    
    use NeoxNotify\NeoxNotifyBundle\Entity\Traits\TimeStampable;
    use NeoxNotify\NeoxNotifyBundle\Repository\MessengerRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    
    #[ORM\Entity(repositoryClass: MessengerRepository::class)]
    #[ORM\HasLifecycleCallbacks]
    class Messenger
    {
        use TimeStampable;
        
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;
        
        #[ORM\Column(length: 50)]
        private ?string $messengerId = null;
        
        #[ORM\Column(length: 255)]
        private ?string $recipient = null;
        
        #[ORM\Column(length: 255)]
        private ?string $sender = null;
        
        #[ORM\Column(type: Types::JSON)]
        private array $channel = [];
        
        #[ORM\Column(type: Types::TEXT)]
        private ?string $data = null;
        
        #[ORM\Column(length: 10)]
        private ?string $status = null;
        
        #[ORM\Column(length: 50)]
        private ?string $service = null;
        
        public function getId(): ?int
        {
            return $this->id;
        }
        
        public function getRecipient(): ?string
        {
            return $this->recipient;
        }
        
        public function setRecipient(string $recipient): self
        {
            $this->recipient = $recipient;
            
            return $this;
        }
        
        public function getSender(): ?string
        {
            return $this->sender;
        }
        
        public function setSender(string $sender): self
        {
            $this->sender = $sender;
            
            return $this;
        }
        
        public function getChannel(): array
        {
            return $this->channel;
        }
        
        public function setChannel(array $channel): void
        {
            $this->channel = $channel;
        }
        
        
        public function getData(): ?string
        {
            return $this->data;
        }
        
        public function setData(string $data): self
        {
            $this->data = $data;
            
            return $this;
        }
        
        public function getStatus(): ?string
        {
            return $this->status;
        }
        
        public function setStatus(string $status): self
        {
            $this->status = $status;
            
            return $this;
        }
        
        public function getService(): ?string
        {
            return $this->service;
        }
        
        public function setService(string $service): self
        {
            $this->service = $service;
            
            return $this;
        }
        
        public function getMessengerId(): ?string
        {
            return $this->messengerId;
        }
        
        public function setMessengerId(string $messengerId): static
        {
            $this->messengerId = $messengerId;
            
            return $this;
        }
    }
