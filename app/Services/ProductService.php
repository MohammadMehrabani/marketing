<?php

namespace App\Services;

use App\Contracts\MarketerProductRepositoryInterface;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ProductServiceInterface;
use App\DTO\ProductDto;
use App\Exceptions\ApiException;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private MarketerProductRepositoryInterface $marketerProductRepository,
    ) {}

    public function getAllProductsWithPaginate(ProductDto $productDto, $perPage = 15, $orderBy = '')
    {
        return new ProductCollection($this->productRepository->getAllProductsWithPaginate($productDto, $perPage, $orderBy));
    }

    public function store(ProductDto $productDto)
    {
        $product = $this->productRepository->create($productDto);

        if ($product->id && $productDto->image && $productDto->imageName)
            $productDto->image->move(public_path('uploads/product/'.$product->id), $productDto->imageName);

        return $product;
    }

    public function update(Product $product, ProductDto $productDto)
    {
        if ($product->merchant_id !== $productDto->merchantId)
            throw new ApiException('access denied',403);

        $product = $this->productRepository->update($product, $productDto);

        if ($product->id && $productDto->image && $productDto->imageName) {
            File::deleteDirectory(public_path('uploads/product/'.$product->id));
            $productDto->image->move(public_path('uploads/product/'.$product->id), $productDto->imageName);
        }

        return $product;
    }

    public function delete(Product $product, $userId)
    {
        // Note: We don't delete the image, because softDeletes is enabled

        if ($product->merchant_id !== $userId)
            throw new ApiException('access denied',403);

        // if exists product into marketer list can't delete
        $existsProduct = $this->marketerProductRepository->findByProduct($product);

        if ($existsProduct)
            throw new ApiException('exists product into marketer list', 400);

        return $this->productRepository->delete($product);
    }

    public function show(Product $product, $userId)
    {
        if ($product->merchant_id !== $userId)
            throw new ApiException('access denied',403);

        return $product;
    }
}
