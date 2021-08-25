<?php

declare(strict_types=1);

namespace App\UI\Client\Http\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Application\Client\Dto\CreateClientDto;
use App\Application\Client\Service\CreateClientService;
use App\UI\Client\Converter\ClientDtoConverter;
use App\UI\Client\Http\Dto\ClientDto;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class CreateClientDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private CreateClientService $clientService, private ClientDtoConverter $converter)
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof ClientDto;
    }

    public function persist($data, array $context = [])
    {
        $dto = new CreateClientDto(
            $data->getFirstName(),
            $data->getLastName(),
            $data->getGender(),
            $data->getPhoneNumber(),
            $data->getEmail()
        );
        $clientDto = $this->clientService->createClient($dto);

        return $this->converter->createHttpFromApplicationDto($clientDto);
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove($data, array $context = [])
    {
        throw new MethodNotAllowedHttpException(['POST'], 'Remove method is not supported.');
    }
}
