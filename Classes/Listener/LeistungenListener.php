<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Listener;

use JWeiland\ServiceBw2\Client\Event\ModifyServiceBwResponseEvent;
use JWeiland\ServiceBw2\Helper\LeistungenHelper;

/**
 * Listener that analyzes a Leistung record response and caches some additional information
 * that can not be fetched otherwise.
 */
class LeistungenListener
{
    /**
     * @var LeistungenHelper
     */
    protected $leistungenHelper;

    public function __construct(LeistungenHelper $leistungenHelper)
    {
        $this->leistungenHelper = $leistungenHelper;
    }

    public function __invoke(ModifyServiceBwResponseEvent $event): void
    {
        if (strpos($event->getPath(), '/portal/leistungsdetails') !== 0) {
            return;
        }
        $pathSegments = explode('/', $event->getPath());
        $this->leistungenHelper->saveAdditionalData(
            (int)end($pathSegments),
            [
                'hasFormulare' => !empty($event->getResponseBody()['formulare']),
                'hasProzesse' => !empty($event->getResponseBody()['prozesse'])
            ]
        );
    }
}
