<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\DTO\ProductDto;
use App\Models\Product;

class MysqlProductRepository implements ProductRepositoryInterface
{
    public function getAllProductsWithPaginate(ProductDto $arguments, $perPage = 15, $orderBy = '')
    {
        $query = Product::query()->filter($arguments)->customOrderBy($orderBy);
        return $query->paginate($perPage);
    }

    public function create(ProductDto $arguments)
    {
        return Product::create([
            'title' => $arguments->title,
            'description' => $arguments->description,
            'image' => $arguments->imageName,
            'url' => $arguments->url,
            'merchant_id' => $arguments->merchantId,
        ]);
    }

    public function update(Product $product, ProductDto $arguments)
    {
        $product->update([
            'title' => $arguments->title ?: $product->title,
            'description' => $arguments->description ?: $product->description,
            'image' => $arguments->imageName ?: $product->image,
            'url' => $arguments->url ?: $product->url,
            'merchant_id' => $arguments->merchantId ?: $product->merchant_id,
            'view_count' => $arguments->viewCount ?: $product->view_count,
        ]);

        return $product;
    }

    public function delete($product)
    {
        return Product::findOrFail($product)->delete();
    }

    public function find($id)
    {
        return Product::query()->findOrFail($id);
    }
}
