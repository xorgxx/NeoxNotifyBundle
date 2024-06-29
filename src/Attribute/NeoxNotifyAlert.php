<?php

    namespace NeoxNotify\NeoxNotifyBundle\Attribute;

    use Attribute;
    use phpDocumentor\Reflection\Types\Boolean;

    #[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
    class NeoxNotifyAlert
    {
        public function __construct(
            public Bool     $handle     = false,
            public ?array   $channels   = [],
            public ?string  $template   = null,
            public ?string  $subject    = null,
            public ?string  $content    = null,
        )
        {
        }

        public function isHandle(): bool
        {
            return $this->handle;
        }

        public function setHandle(bool $handle): NeoxNotifyAlert
        {
            $this->handle = $handle;
            return $this;
        }


        public function getChannels(): ?array
        {
            return $this->channels;
        }

        public function setChannels(?array $channels): NeoxNotifyAlert
        {
            $this->channels = $channels;
            return $this;
        }


        public function getTemplate(): ?string
        {
            return $this->template;
        }

        public function setTemplate(?string $template): NeoxNotifyAlert
        {
            $this->template = $template;
            return $this;
        }

        public function getSubject(): ?string
        {
            return $this->subject;
        }

        public function setSubject(?string $subject): NeoxNotifyAlert
        {
            $this->subject = $subject;
            return $this;
        }

        public function getContent(): ?string
        {
            return $this->content;
        }

        public function setContent(?string $content): NeoxNotifyAlert
        {
            $this->content = $content;
            return $this;
        }


    }