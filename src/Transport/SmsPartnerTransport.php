<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NeoxNotify\NeoxNotifyBundle\Transport;

use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class SmsPartnerTransport extends AbstractTransport
{
    // Base on  : see https://developer.vonage.com/messaging/sms/overview

    private string $apiKey;
    private string $apiSecret;
    private string $from;
    private string $dns;

    /**
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $from
     * @param string $dns
     * @param HttpClientInterface|null $client
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(string $apiKey, string $apiSecret, string $from, string $dns, HttpClientInterface $client = null, EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct($client, $dispatcher);
        $this->apiKey       = $apiKey;
        $this->apiSecret    = $apiSecret;
        $this->from         = $from;
        $this->dns          = $dns;
    }

    public function __toString(): string
    {
        return sprintf($this->dns . '://%s?from=%s', $this->getEndpoint(), $this->from);
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof SmsMessage;
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function doSend(MessageInterface $message): SentMessage
    {
        if (!$message instanceof SmsMessage) {
            throw new UnsupportedMessageTypeException(__CLASS__, SmsMessage::class, $message);
        }

        $response = $this->client->request('POST', 'https://' . $this->getEndpoint(), [
            'body' => [
                'sender'        => $this->from,
                'phoneNumbers'  => $message->getPhone(),
                'message'       => $message->getSubject(),
                'apiKey'        => $this->apiKey,
//                'api_secret'    => $this->apiSecret,
            ],
        ]);

        try {
            $result = $response->toArray(false);
        } catch (TransportExceptionInterface $e) {
            throw new TransportException("Could not reach the remote" . $this->dns . " server.", $response, 0, $e);
        }

        foreach ($result as $msg) {
            if ($msg['success'] ?? false) {
                throw new TransportException('Unable to send the SMS: '.$msg['error-text'].sprintf(' (code %s).', $msg['status']), $response);
            }
        }

        $success = $response->toArray(false);

        $sentMessage = new SentMessage($message, (string) $this);
        $sentMessage->setMessageId($success['message_id']);
        return $sentMessage;
    }
}