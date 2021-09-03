<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Application\Offer\Dto\OfferDto as ApplicationDto;
use App\Application\Offer\Dto\UpdateOfferDto;
use App\Application\Offer\Service\UpdateOfferService;
use App\Domain\Offer\Exception\InvalidOfferStatusException;
use App\Domain\Offer\Exception\OfferUpdateBlockedException;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\Dto\OfferDto;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class UpdateOfferDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private UpdateOfferService $updateOfferService,
        private OfferDtoConverter $offerDtoConverter
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof OfferDto;
    }

    public function persist($data, array $context = [])
    {
        $dto = $this->updateData($data, $context);

        return $this->offerDtoConverter->createHttpFromApplicationDto($dto);
    }

    private function updateData(OfferDto $data, array $context): ApplicationDto
    {
        return match ($context['item_operation_name']) {
            OfferDto::OPERATION_DISABLE => $this->disableEditing($data),
            OfferDto::OPERATION_ENABLE => $this->enableEditing($data),
            default => $this->updateFields($data),
        };
    }

    private function disableEditing(OfferDto $dto): ApplicationDto
    {
        try {
            return $this->updateOfferService->disableEditing($dto->getId());
        } catch (InvalidOfferStatusException $e) {
            throw new AccessDeniedHttpException($e->getMessage());
        }
    }

    private function enableEditing(OfferDto $dto): ApplicationDto
    {
        try {
            return $this->updateOfferService->enableEditing($dto->getId());
        } catch (InvalidOfferStatusException $e) {
            throw new AccessDeniedHttpException($e->getMessage());
        }
    }

    private function updateFields(OfferDto $data): ApplicationDto
    {
        $offer = new UpdateOfferDto(
            $data->getId(),
            $data->getName(),
            $data->getPrice(),
            $data->getQuantity()
        );

        try {
            return $this->updateOfferService->updateOffer($offer);
        } catch (OfferUpdateBlockedException $e) {
            throw new AccessDeniedHttpException($e->getMessage());
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
