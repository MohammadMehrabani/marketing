<?php

namespace App\Repositories;

use App\Contracts\MarketerProductRepositoryInterface;
use App\DTO\MarketerProductDto;
use App\Models\MarketerProduct;

class MysqlMarketerProductRepository implements MarketerProductRepositoryInterface
{
    public function getAllProductsWithPaginate(MarketerProductDto $arguments, $perPage = 15, $orderBy = '')
    {
        $query = MarketerProduct::query()
            ->with(['product:id,title,image'])
            ->filter($arguments)
            ->customOrderBy($orderBy);

        return $query->paginate($perPage);
    }

    public function create(MarketerProductDto $arguments)
    {
        return MarketerProduct::query()->firstOrCreate([
            'marketer_id' => $arguments->marketerId,
            'product_id' => $arguments->productId,
            'creation_date' => date('Y-m-d'),
        ]);
    }
}
