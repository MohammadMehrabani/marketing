<?php

namespace App\Contracts;

use App\DTO\ProductDto;
use App\Models\Product;

interface ProductServiceInterface
{
    public function getAllProductsWithPaginate(ProductDto $productDto, $perPage = 15, $orderBy = '');

    public function store(ProductDto $productDto);

    public function update(Product $product, ProductDto $productDto);

    public function delete(Product $product, $userId);

    public function show(Product $product, $userId);
}
