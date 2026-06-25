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

    public function getTextBloecke(): array
    {
        return $this->data['textbloecke'] ?? [];
    }

    public function getProcessedTextBloecke(): string
    {
        return strip_tags(
            implode(
                ',',
                array_filter(
                    array_column($this->getTextBloecke(), 'text'),
                ),
            ),
        );
    }

    public function getUebergeordneteOE(): ?self
    {
        $parent = $this->data['uebergeordneteOE'] ?? null;
        if (!is_array($parent)) {
            return null;
        }

        return new self(
            (int)($parent['id'] ?? 0),
            (string)($parent['name'] ?? ''),
            $this->type,
            $this->language,
            $parent,
        );
    }

    /**
     * Returns all nested Organisationseinheiten as Record objects.
     * The raw data array is preserved; conversion happens lazily on each call.
     *
     * @return array<int, Record>
     */
    public function getUntergeordneteOEs(): array
    {
        $untergeordnete = $this->data['untergeordneteOEs'] ?? [];

        if (!is_array($untergeordnete)) {
            return [];
        }

        $records = [];
        foreach ($untergeordnete as $item) {
            if (!is_array($item)) {
                continue;
            }
            $records[] = new self(
                (int)($item['id'] ?? 0),
                (string)($item['name'] ?? ''),
                $this->type,
                $this->language,
                $item,
            );
        }

        return $records;
    }

    public function withData(array $data): self
    {
        return new self($this->id, $this->name, $this->type, $this->language, $data);
    }

    public function asArray(): array
    {
        $data = $this->data;

        if ($this->getTextBloecke() !== []) {
            $data['processed_textbloecke'] = $this->getProcessedTextBloecke();
        }

        return $data;
    }
}
