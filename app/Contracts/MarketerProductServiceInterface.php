<?php

namespace App\Contracts;

interface MarketerProductServiceInterface
{
    public function getAllProductsWithPaginate(array $arguments, $perPage = 15, $orderBy = '');
    public function create(array $arguments);
}
