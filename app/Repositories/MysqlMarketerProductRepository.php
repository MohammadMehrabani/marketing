<?php

namespace App\Repositories;

use App\Contracts\MarketerProductRepositoryInterface;
use App\DTO\MarketerProductDto;
use App\Models\MarketerProduct;
use Illuminate\Support\Facades\DB;

class MysqlMarketerProductRepository extends MysqlBaseRepository implements MarketerProductRepositoryInterface
{
    public function model()
    {
        return MarketerProduct::class;
    }

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

    public function findByProduct($productId)
    {
        return MarketerProduct::query()->where('product_id', $productId)->first();
    }

    public function incrementViewCount(MarketerProductDto $marketerProductDto)
    {
        return MarketerProduct::query()
            ->where('marketer_id', $marketerProductDto->marketerId)
            ->where('product_id', $marketerProductDto->productId)
            ->where('creation_date', date('Y-m-d'))
            ->update(['view_count' => DB::raw('view_count + 1')]);
    }
}
