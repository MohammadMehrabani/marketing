<?php

namespace App\Services;

use App\Contracts\MarketerProductRepositoryInterface;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\RedirectorServiceInterface;
use App\DTO\MarketerProductDto;
use App\DTO\ProductDto;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RedirectorService implements RedirectorServiceInterface
{
    public function __construct(
        private MarketerProductRepositoryInterface $marketerProductRepository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function redirect(MarketerProductDto $marketerProductDto)
    {
        try {
            DB::beginTransaction();
            $increamentViewCount = $this->marketerProductRepository->incrementViewCount($marketerProductDto);

            if ($increamentViewCount) {

                $product = $this->productRepository->incrementViewCount(
                    ProductDto::fromArray(['id' => $marketerProductDto->productId])
                );

            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }

        if (empty($product))
            throw new ApiException('invalid productId or marketerId', 400);

        return ['redirectToUrl' => $product->url];
    }
}
