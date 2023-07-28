<?php

namespace App\Contracts;

use App\Models\Product;

interface ProductServiceInterface
{
    public function getAllProductsWithPaginate(array $arguments, $perPage = 15, $orderBy = '');

    public function store(array $arguments);

    public function update(Product $product, array $arguments);

    public function delete($product);

    public function show($product);
}
