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

use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class SmsPartnerTransportFactory extends AbstractTransportFactory
{
    const NAMEHOST          = 'smspartner';

    public function create(Dsn $dsn): SmsPartnerTransport
    {
        $scheme = $dsn->getScheme();

        // Oblige to hardcode name of scheme to make test if exist !!!
        if (self::NAMEHOST !== $scheme) {
            throw new UnsupportedSchemeException($dsn, self::NAMEHOST, $this->getSupportedSchemes());
        }

        $apiKey     = $this->getUser($dsn);
        $apiSecret  = $this->getPassword($dsn);
        $from       = $dsn->getRequiredOption('from');
        $dns        = $dsn->getRequiredOption('dns');
        $host       = 'default' === $dsn->getHost() ? null : $dsn->getHost() . $dsn->getPath();
        $port       = $dsn->getPort();

        return (new SmsPartnerTransport($apiKey, $apiSecret, $from, $dns, $this->client, $this->dispatcher))->setHost($host)->setPort($port);
    }

    protected function getSupportedSchemes(): array
    {
        return [self::NAMEHOST];
    }
}