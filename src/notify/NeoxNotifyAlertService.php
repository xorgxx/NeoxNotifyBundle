<?php

    namespace NeoxNotify\NeoxNotifyBundle\notify;

    use NeoxNotify\NeoxNotifyBundle\Attribute\NeoxNotifyAlert;
    use ReflectionClass;
    use ReflectionMethod;
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Notifier\Notification\Notification;
    use Symfony\Component\Notifier\Recipient\Recipient;

    class NeoxNotifyAlertService
    {
        public ?NeoxNotifyAlert $neoxNotifyAlert = null;
        private ?string          $controller    = null;
        private ?string          $action        = null;

        public function __construct(private readonly RequestStack $requestStack, private readonly ParameterBagInterface $parameterBag, private readonly NotificationStrategyFactory $notificationStrategyFactory)
        {

        }

        public function getNeoxNotifyAlert(): NeoxNotifyAlert
        {
            if (!$this->neoxNotifyAlert) {
                $this->setNeoxNotifyAlert();
            }

            // send notification if handle is active
            if ($this->neoxNotifyAlert->isHandle()) {
                $this->send();
            }

            return $this->neoxNotifyAlert;
        }

        /**
         * @throws \ReflectionException
         */
        public function setNeoxNotifyAlert(): NeoxNotifyAlert
        {
            // Apply SEO settings from configuration
            $this->setNeoxBagParams();

            // Apply attributes from controller and method
            $attributes = $this->getAttributesFromControllerAndMethod();

            foreach ($attributes as $attribute) {
                $data = $attribute->newInstance();

                // Set 'handle' based on presence of data
                $this->neoxNotifyAlert->setHandle(!empty($data));

                foreach ($data as $key => $value) {
                    switch ($key) {
                        case "subject":
                            // Process 'subject' attribute
                            $parsedSubject = $this->parseRequest($value);
                            $subjectString = implode(" ", $parsedSubject);
                            $this->neoxNotifyAlert->setSubject($subjectString);
                            break;

                        case "contexts":
                            // Process 'context' attribute
                            foreach ($value as $k => $v) {
                                if ($k === "opt") {
                                    // Handle 'opt' context separately
                                    $parsedContext = $this->parseRequest($v);
                                    foreach ($parsedContext as $kk => $vv) {
                                        if (!empty($kk)) { // Ensure $kk is not empty or null
                                            $this->neoxNotifyAlert->setContext($kk, $vv);
                                        }
                                    }
                                } else {
                                    // Set other contexts directly
                                    $this->neoxNotifyAlert->setContext($k, $v);
                                }
                            }


                            break;

                        default:
                            // Set other properties dynamically
                            if ($value) {
                                $setter = "set" . $this->toCamelCase($key);
                                $this->neoxNotifyAlert->$setter($value);
                            }
                            break;
                    }
                }
            }

            return $this->neoxNotifyAlert;
        }


//        public function setNeoxNotifyAlert(): NeoxNotifyAlert
//        {
//            // first apply seo settings from configuration
//            $this->setNeoxBagParams();
//
//            // then apply the controller and method attributes
//            $attributes = $this->getAttributesFromControllerAndMethod();
//            foreach ($attributes as $attribute) {
//                $data = $attribute->newInstance();
//                // set handle active if not empty
//                $this->neoxNotifyAlert->setHandle(!empty($data));
//                $y = "";
//                foreach ($data as $key => $value) {
//                    if ($key === "subject") {
//                        $t = $this->parseRequest($value);
//                        foreach ($t as $k => $v) {
//                            $y .= $v . " ";
//                        }
//                        $this->neoxNotifyAlert->setSubject($y);
//                    }
//
//                    if ($key === "context") {
//                        $t = $this->parseRequest($value);
//                        foreach ($t as $k => $v) {
//                            $this->neoxNotifyAlert->setContexts($k, $v);
//                        }
//                    }
//
//
//                    if ($value) {
//                        $setter = "set" . $this->toCamelCase($key);
//                        $this->neoxNotifyAlert->$setter($value);
//                    }
//                }
//            }
//            return $this->neoxNotifyAlert;
//        }



        private function setNeoxBagParams(): void
        {
            $this->neoxNotifyAlert = new NeoxNotifyAlert();
            $neoxBagParams         = [];
            $neoxBagParams         = $this->parameterBag->get('neox_notify');
            $this->neoxNotifyAlert
                ->setChannels($neoxBagParams [ 'service' ][ 'channels' ] ?? [])
                ->setTemplate($neoxBagParams [ 'service' ][ 'template' ] ?? null)
                ->setSubject($neoxBagParams [ 'service' ][ 'subject' ] ?? null)
                ->setContent($neoxBagParams [ 'service' ][ 'content' ] ?? null);
        }

//        /**
//         * @throws \ReflectionException
//         */
//        private function getAttributesFromControllerAndMethod(): array
//        {
//            $this->getInfoAboutCurrentRequest();
//            $classAttributes  = (new ReflectionClass($this->controller))->getAttributes(NeoxNotifyAlert::class);
//            $methodAttributes = (new ReflectionMethod($this->controller, $this->action))->getAttributes(NeoxNotifyAlert::class);
//
//            return array_merge($classAttributes, $methodAttributes);
//        }
        /**
         */
        private function getAttributesFromControllerAndMethod(): array
        {
            $this->getInfoAboutCurrentRequest();

            if (!$this->controller || !$this->action) {
                return [];
            }

            try {
                $classAttributes = (new ReflectionClass($this->controller))->getAttributes(NeoxNotifyAlert::class);
                $methodAttributes = (new ReflectionMethod($this->controller, $this->action))->getAttributes(NeoxNotifyAlert::class);

                return array_merge($classAttributes, $methodAttributes);
            } catch (\ReflectionException $e) {
                // Log ou gérer l'erreur
                return [];
            }
        }

//        private function getInfoAboutCurrentRequest(): void
//        {
//            $request = $this->requestStack->getCurrentRequest();
//
//            if ($request) {
//                $controllerName = $request->attributes->get('_controller');
//                list($this->controller, $this->action) = explode('::', $controllerName);
//            }
//        }
        private function getInfoAboutCurrentRequest(): void
        {
            $request = $this->requestStack->getCurrentRequest();

            if ($request) {
                $controllerName = $request->attributes->get('_controller');

                if (is_string($controllerName) && strpos($controllerName, '::') !== false) {
                    list($this->controller, $this->action) = explode('::', $controllerName);
                } else {
                    // Gère les cas où le contrôleur est invalide
                    $this->controller = null;
                    $this->action = null;
                }
            }
        }

        private function toCamelCase($str): string
        {
            return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
        }

        private function send(): void
        {
            // send notification if handle is active
            if ($this->neoxNotifyAlert->isHandle()) {
                // Create listing Queue
                $notificationQueue = new NotificationQueue();

                foreach ($this->neoxNotifyAlert->getChannels() as $index => $item) {
                    switch ($item) {
                        case 'email':
                            // Create and configure email strategy
                            $notification = $this->notificationStrategyFactory->EmailStrategy();
                            $notification->setTemplate($this->neoxNotifyAlert->getTemplate());
                            $notification->Subject($this->neoxNotifyAlert->getSubject());
                            $notification->content($this->neoxNotifyAlert->getContent());
                            foreach ($this->neoxNotifyAlert->getContexts() as $key => $value) {
                                $notification->setContext($key, $value);
                            }
                            // put in Queue
                            $notificationQueue->addNotification($notification);
                            break;

                        case 'sms':
                            $phone        = "+33637543480";
                            $notification = $this->notificationStrategyFactory->NotificationStrategy();
                            $msg          = "Test send SMS";
                            $notification->setNotification((new Notification($msg, [ 'sms' ])));
                            $notification->setRecipient(new Recipient("info@exemple.com", $phone));
                            $notificationQueue->addNotification($notification);
                            break;
                        case 'push':

                            break;
                    }
                }
                // Send all notifications in the queue
                $notificationQueue->sendNotifications();
            }

        }

        private function parseRequest(array|string $string): array
        {
            $pos    = strpos($string, '[');
            $result = [];

            if ($pos !== false) {
                $result[ 'text' ]  = trim(substr($string, 0, $pos));
                $attributeNotation = substr($string, $pos + 1, -1);
                $parts             = explode(';', $attributeNotation);

                $result = $this->getValue($parts, $result);
            }

            return $result;
        }

        private function getValue(array $parts, array $result = []): array
        {
            foreach ($parts as $part) {
                [ $NKey, $NValue ] = explode('.', $part);
                $request           = $this->requestStack->getCurrentRequest();
                $result[ $NValue ] = $request->{$NKey}->get($NValue);
            }
            return $result;
        }
    }