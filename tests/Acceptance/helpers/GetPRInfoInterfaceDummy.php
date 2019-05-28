<?php

declare(strict_types=1);

namespace Tests\Acceptance\helpers;

use Slub\Domain\Entity\PR\PRIdentifier;
use Slub\Domain\Query\GetPRInfoInterface;
use Slub\Domain\Query\PRInfo;

/**
 * @author    Samir Boulil <samir.boulil@akeneo.com>
 */
class GetPRInfoInterfaceDummy implements GetPRInfoInterface
{
    public function fetch(PRIdentifier $PRIdentifier): PRInfo
    {
        $result = new PRInfo();
        $result->GTMCount = 0;
        $result->notGTMCount = 0;
        $result->comments = 0;
        $result->CIStatus = 'PENDING';
        $result->isMerged = false;

        return $result;
    }
}
