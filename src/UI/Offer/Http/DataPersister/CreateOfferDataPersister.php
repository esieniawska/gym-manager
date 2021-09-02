<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Application\Offer\Service\CreateOfferService;
use App\UI\Offer\Http\Dto\OfferDto;
use App\UI\Offer\Http\Factory\CreateOfferDtoFactory;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class CreateOfferDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private CreateOfferService $createOfferService, private CreateOfferDtoFactory $createOfferDtoFactory)
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof OfferDto && isset($context['collection_operation_name']) && 'post' === $context['collection_operation_name'];
    }

    public function persist($data, array $context = [])
    {
        $offer = $this->createOfferDtoFactory->createDtoFromHttp($data);
        $this->createOfferService->create($offer);
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove($data, array $context = [])
    {
        throw new MethodNotAllowedHttpException(['POST'], 'Remove method is not supported.');
    }
}
