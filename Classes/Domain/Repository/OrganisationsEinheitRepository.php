<?php
namespace JWeiland\ServiceBw2\Domain\Repository;

/*
 * This file is part of the service_bw2 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use function GuzzleHttp\Promise\is_fulfilled;
use JWeiland\ServiceBw2\Request;
use JWeiland\ServiceBw2\Service\TranslationService;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrganisationsEinheitRepository extends AbstractRepository
{
    /**
     * @var TranslationService
     */
    protected $translationService;

    /**
     * inject translationService
     *
     * @param TranslationService $translationService
     * @return void
     */
    public function injectTranslationService(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Get all organizational units from Service BW
     *
     * @return array
     */
    public function getAll()
    {
        /** @var Request\OrganisationsEinheiten\Roots $request */
        $request = $this->objectManager->get(Request\OrganisationsEinheiten\Roots::class);
        $records = $this->serviceBwClient->processRequest($request);
        $this->addChildrenToRecords($records);
        $this->translationService->translate($records);

        return $records;
    }

    /**
     * Adds children recursive to $records
     * Will add children into $record[<id>]['_children'] = [];
     *
     * Children are from type OrganisationsEinheit BUT doesn´t contain
     * all fields! Take a look at service_bw API documentation if you
     * want to know which fields are provided.
     *
     * @param array $records
     * @return void
     */
    protected function addChildrenToRecords(array &$records)
    {
        if (is_array($records)) {
            foreach ($records as &$organisationsEinheit) {
                $children = $this->getChildren($organisationsEinheit['id']);
                if (!empty($children)) {
                    $this->addChildrenToRecords($children);
                    $organisationsEinheit['_children'] = $children;
                }
            }
        }
    }

    /**
     * Add anschriften to storage
     *
     * @param array $records
     *
     * @return void
     */
    protected function addAnschriften(array &$records = [])
    {
        foreach ($records as $language => $organisationsEinheiten) {
            foreach ($organisationsEinheiten as $id => $organisationsEinheit) {
                $records[$language][$id]['anschrift'] = $this->getAnschriften($id, $language);
            }
        }
    }

    /**
     * Get anschriften for a given Organisations Einheit ID
     *
     * @param int $id
     * @param string $language
     *
     * @return array
     */
    protected function getAnschriften($id, $language = 'de')
    {
        if (!$this->requestCache->has('anschriften_' . (int)$id)) {
            /** @var Request\OrganisationsEinheiten\Anschriften $request */
            $request = $this->objectManager->get(Request\OrganisationsEinheiten\Anschriften::class);
            $request->addParameter('id', $id);
            $this->requestCache->set(
                'anschriften_' . (int)$id,
                $this->serviceBwClient->processRequest($request),
                ['anschriften']
            );
        }
        $anschriften = $this->requestCache->get('anschriften_' . (int)$id);
        if (isset($anschriften[$language])) {
            return $anschriften[$language];
        }
        return [];
    }

    /**
     * Get children records of ID
     *
     * @param int $id
     *
     * @return array
     */
    public function getChildren(int $id): array
    {
        /** @var Request\OrganisationsEinheiten\Children $request */
        $request = $this->objectManager->get(Request\OrganisationsEinheiten\Children::class);
        $request->addParameter('id', (int)$id);
        $children = $this->serviceBwClient->processRequest($request);
        $childrenWithId = [];
        if (is_array($children)) {
            foreach ($children as $child) {
                $childrenWithId[$child['id']] = $child;
            }
        }
        return $childrenWithId;
    }
}
