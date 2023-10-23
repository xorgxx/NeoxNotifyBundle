<?php

    namespace NeoxNotify\NeoxNotifyBundle\Entity\Traits;

    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    trait TimeStampable
    {
        #[ORM\Column(type: Types::DATETIME_MUTABLE, precision:10)]
        private ?\DateTimeImmutable $createdAt = null;
        
        #[ORM\Column(type: Types::DATETIME_MUTABLE, precision:10)]
        private ?\DateTimeImmutable $updatedAt = null;

        public function getCreatedAt(): ?\DateTimeImmutable
        {
            return $this->createdAt;
        }

        public function setCreatedAt(\DateTimeImmutable $createdAt): self
        {
            $this->createdAt = $createdAt;

            return $this;
        }

        public function getUpdatedAt(): ?\DateTimeImmutable
        {
            return $this->updatedAt;
        }

        public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }

        #[ORM\PrePersist]
        public function onPrePersist(): void
        {
            $this->setCreatedAt(new \DateTimeImmutable());
            $this->setUpdatedAt(new \DateTimeImmutable());
        }

        #[ORM\PreUpdate]
        public function onPreUpdate(): void
        {
            $this->setUpdatedAt(new \DateTimeImmutable());
        }
    }
