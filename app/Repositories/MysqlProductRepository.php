<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\DTO\ProductDto;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class MysqlProductRepository extends MysqlBaseRepository implements ProductRepositoryInterface
{
    public function model()
    {
        return Product::class;
    }

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

    public function delete(Product $product)
    {
        return $product->delete();
    }

    public function incrementViewCount(ProductDto $productDto)
    {
        $product = Product::query()->find($productDto->id);
        $product->update(['view_count' => DB::raw('view_count + 1')]);
        return $product;
    }
}
