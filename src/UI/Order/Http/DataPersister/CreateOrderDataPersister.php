<?php

declare(strict_types=1);

namespace App\UI\Order\Http\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Application\Client\Exception\ClientCanNotCreateOrderException;
use App\Application\Client\Exception\ClientNotFoundException;
use App\Application\Offer\Exception\OfferCanNotBeOrderedException;
use App\Application\Offer\Exception\OfferNotFoundException;
use App\Application\Order\Dto\CreateOrderDto;
use App\Application\Order\Exception\OrderFailedException;
use App\Application\Order\Service\CreateOrderService;
use App\UI\Order\Http\Dto\CreateOrderForm;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateOrderDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private CreateOrderService $createOrderService)
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof CreateOrderForm && isset($context['collection_operation_name']) && 'post' === $context['collection_operation_name'];
    }

    public function persist($data, array $context = [])
    {
        /** @var CreateOrderForm $data */
        $dto = new CreateOrderDto(
            $data->cardNumber,
            $data->offerId,
            $data->startDate
        );
        try {
            $this->createOrderService->create($dto);
        } catch (OrderFailedException | ClientCanNotCreateOrderException | OfferCanNotBeOrderedException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (ClientNotFoundException | OfferNotFoundException  $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove($data, array $context = [])
    {
        throw new MethodNotAllowedHttpException(['POST'], 'Remove method is not supported.');
    }
}
