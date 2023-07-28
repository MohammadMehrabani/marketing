<?php

namespace App\Services;

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
        private ProductRepositoryInterface $productRepository
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

    public function delete(Product $product)
    {
        try {
            // We don't delete the image, because softDeletes is enabled
            // TODO: if exists product into marketer list can't delete
            return $this->productRepository->delete($product);
        } catch (\Exception $e) {
            throw new ApiException(trans('messages.errors.404'), 404);
        }
    }

    public function show($product)
    {
        $product = $this->productRepository->find($product);

        if ($product->merchant_id !== auth()->id())
            throw new ApiException('access denied',403);

        return $product;
    }
}
