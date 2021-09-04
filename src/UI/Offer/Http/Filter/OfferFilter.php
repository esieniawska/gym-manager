<?php

declare(strict_types=1);

namespace App\UI\Offer\Http\Filter;

use ApiPlatform\Core\Serializer\Filter\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

class OfferFilter implements FilterInterface
{
    public const FILTER_CONTEXT_FIELD_NAME = 'search_filter';

    public function __construct(public array $properties = [])
    {
    }

    public function apply(Request $request, bool $normalization, array $attributes, array &$context)
    {
        foreach ($request->query->getIterator() as $name => $value) {
            if (array_key_exists($name, $this->properties)) {
                $context[self::FILTER_CONTEXT_FIELD_NAME][$name] = $value;
            }
        }
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'name' => [
                'property' => 'name',
                'type' => 'string',
                'required' => false,
            ],
            'status' => [
                'property' => 'status',
                'type' => 'string',
                'required' => false,
            ],
        ];
    }
}
