<?php

declare(strict_types=1);

namespace App\UI\Client\Http\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Application\Client\Service\GetClientService;
use App\Domain\Shared\Exception\InvalidValueException;
use App\UI\Client\Converter\ClientDtoCollectionConverter;
use App\UI\Client\Http\Dto\ClientDto;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private GetClientService $clientService, private ClientDtoCollectionConverter $converter)
    {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        try {
            $clientDto = $this->clientService->getClientById($id);

            return $this->converter->createHttpFromApplicationDto($clientDto);
        } catch (ClientNotFoundException $exception) {
            throw new NotFoundHttpException();
        } catch (InvalidValueException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ClientDto::class === $resourceClass;
    }
}
