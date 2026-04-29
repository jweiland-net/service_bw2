<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Helper;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

readonly class LanguageHelper
{
    public function __construct(
        protected ExtConf $extConf,
    ) {}

    public function getServeBwLanguageCodeByTypo3LanguageCode(string $typo3LanguageCode): string
    {
        $allowedLanguages = $this->extConf->getAllowedLanguages();

        return $allowedLanguages[$typo3LanguageCode] ?? $this->getDefaultServiceBwLanguageCode();
    }

    public function getDefaultServiceBwLanguageCode(): string
    {
        $allowedLanguages = $this->extConf->getAllowedLanguages();
        $firstKey = array_key_first($allowedLanguages);

        if ($firstKey === null) {
            throw new \RuntimeException('No allowed service-bw languages configured.', 1764420001);
        }

        return $allowedLanguages[$firstKey];
    }

    public function getTypo3LanguageCodeFromRequest(ServerRequestInterface $request): string
    {
        return strtolower($this->getSiteLanguage($request)->getLocale()->getCountryCode());
    }

    public function getServiceBwLanguageCodeFromRequest(ServerRequestInterface $request): string
    {
        return $this->getServeBwLanguageCodeByTypo3LanguageCode(
            $this->getTypo3LanguageCodeFromRequest($request),
        );
    }

    protected function getSiteLanguage(ServerRequestInterface $request): SiteLanguage
    {
        return $request->getAttribute('language');
    }
}
