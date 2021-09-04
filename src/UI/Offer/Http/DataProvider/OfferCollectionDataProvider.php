<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Application\Offer\Dto\Filter;
use App\Application\Offer\Service\GetOfferService;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\Dto\OfferDto;
use App\UI\Offer\Http\Filter\OfferFilter;

class OfferCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private GetOfferService $clientService, private OfferDtoConverter $converter)
    {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $filters = $this->prepareFilterData($context);
        $clients = $this->clientService->getAllOffer($filters);

        return $this->converter->createHttpFromApplicationDtoCollection($clients);
    }

    private function prepareFilterData(array $context): Filter
    {
        return new Filter(
            $context[OfferFilter::FILTER_CONTEXT_FIELD_NAME]['name'] ?? null,
            $context[OfferFilter::FILTER_CONTEXT_FIELD_NAME]['status'] ?? null
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return OfferDto::class === $resourceClass;
    }
}
