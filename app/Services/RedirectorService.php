<?php

namespace App\Services;

use App\Contracts\RedirectorServiceInterface;
use App\DTO\RedirectorDto;
use App\Exceptions\ApiException;
use App\Models\MarketerProduct;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RedirectorService implements RedirectorServiceInterface
{
    public function redirect(array $arguments)
    {
        $arguments = RedirectorDto::fromArray($arguments);

        try {
            DB::beginTransaction();
            $increamentViewCount = MarketerProduct::query()
                ->where('marketer_id', $arguments->marketerId)
                ->where('product_id', $arguments->productId)
                ->update(['view_count' => DB::raw('view_count + 1')]);

            if ($increamentViewCount) {
                $product = Product::query()->find($arguments->productId);
                $product->update(['view_count' => DB::raw('view_count + 1')]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }

        if (empty($product))
            throw new ApiException('invalid product or marketer', 404);

        return ['redirectUrl' => $product->url];
    }
}
