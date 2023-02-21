<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
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
        if (!str_starts_with($event->getPath(), '/portal/leistungsdetails')) {
            return;
        }

        $pathSegments = explode('/', $event->getPath());
        $this->leistungenHelper->saveAdditionalData(
            (int)end($pathSegments),
            $this->getAdditionalData($event)
        );
    }

    protected function getAdditionalData(ModifyServiceBwResponseEvent $event): array
    {
        $additionalData = ['hasProzesse' => false, 'hasFormulare' => false];
        if (!empty($event->getResponseBody()['prozesse'])) {
            $additionalData['hasProzesse'] = true;
        }
        $formulare = $event->getResponseBody()['formulare'] ?? [];
        while (
            ($form = array_shift($formulare))
            && !($additionalData['hasProzesse'] === true && $additionalData['hasFormulare'] === true)) {
            if (is_array($form) && isset($form['typ']) && $form['typ'] === 'ONLINEDIENST') {
                $additionalData['hasProzesse'] = true;
            } else {
                $additionalData['hasFormulare'] = true;
            }
        }

        return $additionalData;
    }
}
