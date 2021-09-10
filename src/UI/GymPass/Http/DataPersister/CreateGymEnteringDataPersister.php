<?php

declare(strict_types=1);

namespace App\UI\GymPass\Http\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Application\GymPass\Dto\AddGymEnteringDto;
use App\Application\GymPass\Exception\GymPassNotFoundException;
use App\Application\GymPass\Exception\InvalidGymPassClientException;
use App\Application\GymPass\Service\GymEnteringCreator;
use App\Domain\GymPass\Exception\InactiveGymPassException;
use App\UI\GymPass\Http\Dto\GymEnteringForm;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateGymEnteringDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private GymEnteringCreator $enteringCreator)
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof GymEnteringForm && isset($context['collection_operation_name']) && 'post' === $context['collection_operation_name'];
    }

    public function persist($data, array $context = [])
    {
        try {
            $this->enteringCreator->create(
                new AddGymEnteringDto($data->cardNumber, $data->gymPassId)
            );
        } catch (GymPassNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (InvalidGymPassClientException  $e) {
            throw new AccessDeniedHttpException($e->getMessage());
        } catch (InactiveGymPassException $e) {
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
