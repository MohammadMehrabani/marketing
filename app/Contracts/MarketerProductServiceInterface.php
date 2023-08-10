<?php

namespace App\Contracts;

use App\DTO\MarketerProductDto;

interface MarketerProductServiceInterface
{
    public function getAllProductsWithPaginate(MarketerProductDto $marketerProductDto, $perPage = 15, $orderBy = '');
    public function create(MarketerProductDto $marketerProductDto);
}
