<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Authorization\Policy\RequestPolicyInterface;
use Authorization\Policy\ResultInterface;
use Cake\Error\Debugger;
use Cake\Http\ServerRequest;

class RequestPolicy implements RequestPolicyInterface
{
    /**
     * Method to check if the request can be accessed
     *
     * @param \Authorization\IdentityInterface|null $identity Identity
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return \Authorization\Policy\ResultInterface|bool
     */
    public function canAccess(?IdentityInterface $identity, ServerRequest $request): bool|ResultInterface
    {
        if ($request->getParam('prefix') != 'Admin') {
            return true;
        } elseif ($identity->getOriginalData()->isAdmin()) {
            return true;
        }

        return false;
    }
}
