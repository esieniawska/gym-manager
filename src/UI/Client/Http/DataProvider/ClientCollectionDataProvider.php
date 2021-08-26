<?php

declare(strict_types=1);

namespace App\UI\Client\Http\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Application\Client\Service\GetClientService;
use App\UI\Client\Converter\ClientDtoConverter;
use App\UI\Client\Http\Dto\ClientDto;

class ClientCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private GetClientService $clientService, private ClientDtoConverter $converter)
    {
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $clients = $this->clientService->getAllClients();

        return $this->converter->createHttpFromApplicationDtoCollection($clients);
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ClientDto::class === $resourceClass;
    }
}
