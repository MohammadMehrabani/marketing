<?php

namespace App\Services;

use App\Contracts\MarketerProductRepositoryInterface;
use App\Contracts\MarketerProductServiceInterface;
use App\Contracts\ProductRepositoryInterface;
use App\DTO\MarketerProductDto;
use App\Http\Resources\ProductCollection;

class MarketerProductService implements MarketerProductServiceInterface
{
    public function __construct(
        private MarketerProductRepositoryInterface $marketerProductRepository,
        private ProductRepositoryInterface $productRepository,
    )
    {
    }

    public function getAllProductsWithPaginate(array $arguments, $perPage = 15, $orderBy = '')
    {
        $arguments = MarketerProductDto::fromArray($arguments);

        return new ProductCollection($this->marketerProductRepository->getAllProductsWithPaginate($arguments, $perPage, $orderBy));
    }

    public function create(array $arguments)
    {
        $arguments = MarketerProductDto::fromArray($arguments);

        $this->productRepository->find($arguments->productId);

        $this->marketerProductRepository->create($arguments);

        return [
            'shareLink' => request()->getSchemeAndHttpHost().'/api/redirector?product='.
                            $arguments->productId.'&marketer='.$arguments->marketerId
        ];
    }
}
