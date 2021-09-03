<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Application\Offer\Dto\UpdateOfferDto;
use App\Application\Offer\Service\UpdateOfferService;
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
        return $data instanceof OfferDto && 'put' === $context['item_operation_name'];
    }

    public function persist($data, array $context = [])
    {
        /***@var $data OfferDto */
        $offer = new UpdateOfferDto(
            $data->getId(),
            $data->getName(),
            $data->getPrice(),
            $data->getQuantity()
        );

        try {
            $dto = $this->updateOfferService->updateOffer($offer);
        } catch (OfferUpdateBlockedException $e) {
            throw new AccessDeniedHttpException($e->getMessage());
        }

        return $this->offerDtoConverter->createHttpFromApplicationDto($dto);
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove($data, array $context = [])
    {
        throw new MethodNotAllowedHttpException(['POST'], 'Remove method is not supported.');
    }
}
