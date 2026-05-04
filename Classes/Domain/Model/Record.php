<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Model;

final readonly class Record
{
    public function __construct(
        private int $id,
        private string $name,
        private string $type,
        private string $language,
        private array $data,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getHasProzesse(): bool
    {
        if (($this->data['prozesse'] ?? []) !== []) {
            return true;
        }

        foreach (($this->data['formulare'] ?? []) as $formular) {
            if (is_array($formular) && ($formular['typ'] ?? null) === 'ONLINEDIENST') {
                return true;
            }
        }

        return false;
    }

    public function getHasFormulare(): bool
    {
        foreach ($this->data['formulare'] ?? [] as $formular) {
            if (!is_array($formular) || ($formular['typ'] ?? null) !== 'ONLINEDIENST') {
                return true;
            }
        }

        return false;
    }
}
