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
    )
    {
    }

    public function getAllProductsWithPaginate(array $arguments, $perPage = 15, $orderBy = '')
    {
        $arguments = ProductDto::fromArray($arguments);

        return new ProductCollection($this->productRepository->getAllProductsWithPaginate($arguments, $perPage, $orderBy));
    }

    public function store(array $arguments)
    {
        $imageName = !empty($arguments['image']) ? time().'_'.$arguments['image']->getClientOriginalName() : null;
        $arguments['imageName'] = $imageName;
        $arguments['merchantId'] = auth()->id();
        $arguments = ProductDto::fromArray($arguments);

        $product = $this->productRepository->create($arguments);

        if ($product->id && $imageName)
            $arguments->image->move(public_path('uploads/product/'.$product->id), $imageName);

        return $product;
    }

    public function update(Product $product, array $arguments)
    {
        $imageName = !empty($arguments['image']) ? time().'_'.$arguments['image']->getClientOriginalName() : $product->getRawOriginal('image');
        $arguments['imageName'] = $imageName;
        $arguments = ProductDto::fromArray($arguments);

        $product = $this->productRepository->update($product, $arguments);

        if ($product->id && $arguments->image) {
            File::deleteDirectory(public_path('uploads/product/'.$product->id));
            $arguments->image->move(public_path('uploads/product/'.$product->id), $imageName);
        }

        return $product;
    }

    public function delete($product)
    {
        // Note: We don't delete the image, because softDeletes is enabled

        // if exists product into marketer list can't delete
        $existsProduct = $this->marketerProductRepository->findByProduct($product);

        if ($existsProduct)
            throw new ApiException('exists product into marketer list', 400);

        return $this->productRepository->delete($product);

    }

    public function show($product)
    {
        $product = $this->productRepository->find($product);

        if ($product->merchant_id !== auth()->id())
            throw new ApiException('access denied',403);

        return $product;
    }
}
