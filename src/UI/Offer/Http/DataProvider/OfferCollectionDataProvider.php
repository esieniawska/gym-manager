<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Application\Offer\Service\GetOfferService;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\Dto\OfferDto;

class OfferCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private GetOfferService $clientService, private OfferDtoConverter $converter)
    {
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $clients = $this->clientService->getAllOffer();

        return $this->converter->createHttpFromApplicationDtoCollection($clients);
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return OfferDto::class === $resourceClass;
    }
}
