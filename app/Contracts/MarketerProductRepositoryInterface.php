<?php

namespace App\Contracts;

use App\DTO\MarketerProductDto;

interface MarketerProductRepositoryInterface
{
    public function getAllProductsWithPaginate(MarketerProductDto $arguments, $perPage = 15, $orderBy = '');
    public function create(MarketerProductDto $arguments);
    public function findByProduct($productId);
    public function incrementViewCount(MarketerProductDto $marketerProductDto);
}
