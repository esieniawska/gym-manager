<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Application\Offer\Service\GetOfferService;
use App\Domain\Offer\Exception\OfferNotFoundException;
use App\Domain\Shared\Exception\InvalidValueException;
use App\UI\Offer\Converter\OfferDtoConverter;
use App\UI\Offer\Http\Dto\OfferDto;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OfferItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private GetOfferService $getOfferService, private OfferDtoConverter $offerDtoConverter)
    {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        try {
            $offerDto = $this->getOfferService->getOfferById($id);
        } catch (OfferNotFoundException $ex) {
            throw new NotFoundHttpException();
        } catch (InvalidValueException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return $this->offerDtoConverter->createHttpFromApplicationDto($offerDto);
    }

    /**
     * @codeCoverageIgnore
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return OfferDto::class === $resourceClass;
    }
}
