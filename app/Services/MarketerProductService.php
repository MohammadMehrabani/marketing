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
    ) {}

    public function getAllProductsWithPaginate(MarketerProductDto $marketerProductDto, $perPage = 15, $orderBy = '')
    {
        return new ProductCollection(
            $this->marketerProductRepository->getAllProductsWithPaginate(
                $marketerProductDto, $perPage, $orderBy
            )
        );
    }

    public function create(MarketerProductDto $marketerProductDto)
    {
        // if the product is not exists, an exception is thrown
        $this->productRepository->findOrFail($marketerProductDto->productId);

        $this->marketerProductRepository->create($marketerProductDto);

        return [
            'shareLink' => request()->getSchemeAndHttpHost().'/api/redirector'.
                            '?productId='.$marketerProductDto->productId.
                            '&marketerId='.$marketerProductDto->marketerId
        ];
    }
}
