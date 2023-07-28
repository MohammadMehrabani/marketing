<?php

namespace App\Contracts;

use App\DTO\ProductDto;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function getAllProductsWithPaginate(ProductDto $arguments, $perPage = 15, $orderBy = '');

    public function create(ProductDto $arguments);

    public function update(Product $product, ProductDto $arguments);

    public function delete(Product $product);

    public function find($id);
}
