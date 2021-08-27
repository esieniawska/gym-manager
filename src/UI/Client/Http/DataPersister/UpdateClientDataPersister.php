<?php

declare(strict_types=1);

namespace App\UI\Client\Http\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Application\Client\Dto\UpdateClientDto;
use App\Application\Client\Service\UpdateClientService;
use App\UI\Client\Converter\ClientDtoCollectionConverter;
use App\UI\Client\Http\Dto\ClientDto;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class UpdateClientDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private UpdateClientService $clientService, private ClientDtoCollectionConverter $converter)
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof ClientDto && 'put' === $context['item_operation_name'];
    }

    public function persist($data, array $context = [])
    {
        $updateClientDto = new UpdateClientDto(
            $data->getId(),
            $data->getFirstName(),
            $data->getLastName(),
            $data->getGender(),
            $data->getStatus(),
            $data->getPhoneNumber(),
            $data->getEmail()
        );

        $updatedDto = $this->clientService->updateClient($updateClientDto);

        return $this->converter->createHttpFromApplicationDto($updatedDto);
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove($data, array $context = [])
    {
        throw new MethodNotAllowedHttpException(['PUT'], 'Remove method is not supported.');
    }
}
