<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use Psr\Http\Message\ResponseInterface;
use JWeiland\ServiceBw2\Request\Portal\Lebenslagen;

/**
 * Class LebenslagenController
 */
class LebenslagenController extends AbstractController
{
    protected Lebenslagen $lebenslagen;

    public function injectLebenslagen(Lebenslagen $lebenslagen): void
    {
        $this->lebenslagen = $lebenslagen;
    }

    public function listAction(): ResponseInterface
    {
        $this->view->assign('lebenslagenbaum', $this->lebenslagen->findLebenslagenbaum());

        return $this->htmlResponse();
    }

    public function showAction(int $id): ResponseInterface
    {
        $lebenslage = $this->lebenslagen->findById($id);
        if ($lebenslage === []) {
            $this->addFlashMessage('Requested Lebenslage could not be found for current language');
        } else {
            $this->view->assign('lebenslage', $lebenslage);
            $this->setPageTitle($lebenslage['name'] ?? '');
        }

        return $this->htmlResponse();
    }
}
