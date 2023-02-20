<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use JWeiland\ServiceBw2\Request\Portal\Lebenslagen;

/**
 * Class LebenslagenController
 */
class LebenslagenController extends AbstractController
{
    protected Lebenslagen $lebenslagen;

    public function __construct(Lebenslagen $lebenslagen)
    {
        $this->lebenslagen = $lebenslagen;
    }

    /**
     * List action
     */
    public function listAction(): void
    {
        $this->view->assign('lebenslagenbaum', $this->lebenslagen->findLebenslagenbaum());
    }

    /**
     * Show action
     *
     * @param int $id
     */
    public function showAction(int $id): void
    {
        $lebenslage = $this->lebenslagen->findById($id);
        $this->view->assign('lebenslage', $lebenslage);
        $this->setPageTitle($lebenslage['name'] ?? '');
    }
}
