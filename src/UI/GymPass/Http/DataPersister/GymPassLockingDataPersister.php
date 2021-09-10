<?php

declare(strict_types=1);

namespace App\UI\GymPass\Http\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Application\GymPass\Dto\GymPassLockingDto;
use App\Application\GymPass\Exception\GymPassNotFoundException;
use App\Application\GymPass\Exception\InvalidGymPassTypeException;
use App\Application\GymPass\Service\GymPassLockingService;
use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\UI\GymPass\Http\Dto\GymPassLockingForm;
use App\UI\GymPass\Http\Dto\GymPassLockingOutput;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GymPassLockingDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private GymPassLockingService $lockingService)
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof GymPassLockingForm && isset($context['collection_operation_name']) && 'post' === $context['collection_operation_name'];
    }

    public function persist($data, array $context = [])
    {
        try {
            $result = $this->lockingService->lockGymPass(
                new GymPassLockingDto($data->gymPassId, $data->numberOfDays)
            );

            return new GymPassLockingOutput(
                $result->getEndDate(),
                $result->getLockStartDate(),
                $result->getLockEndDate()
            );
        } catch (GymPassNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (InactiveGymPassException | InvalidGymPassTypeException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove($data, array $context = [])
    {
        throw new MethodNotAllowedHttpException(['POST'], 'Remove method is not supported.');
    }
}
