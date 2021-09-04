<?php

declare(strict_types=1);

namespace App\UI\Client\Http\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Application\Client\Dto\Filter;
use App\Application\Client\Service\GetClientService;
use App\UI\Client\Converter\ClientDtoCollectionConverter;
use App\UI\Client\Http\Dto\ClientDto;
use App\UI\Client\Http\Filter\ClientFilter;

class ClientCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private GetClientService $clientService, private ClientDtoCollectionConverter $converter)
    {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $clients = $this->clientService->getAllClients(
            $this->prepareFilterData($context)
        );

        return $this->converter->createHttpFromApplicationDtoCollection($clients);
    }

    private function prepareFilterData(array $context): Filter
    {
        return new Filter(
            $context[ClientFilter::FILTER_CONTEXT_FIELD_NAME]['cardNumber'] ?? null,
            $context[ClientFilter::FILTER_CONTEXT_FIELD_NAME]['firstName'] ?? null,
            $context[ClientFilter::FILTER_CONTEXT_FIELD_NAME]['lastName'] ?? null,
            $context[ClientFilter::FILTER_CONTEXT_FIELD_NAME]['status'] ?? null
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ClientDto::class === $resourceClass;
    }
}
