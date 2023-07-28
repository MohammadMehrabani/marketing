<?php

namespace App\Http\Controllers\Marketer;

use App\Contracts\MarketerProductServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\Http\Controllers\Controller;
use App\Traits\SharedBetweenControllers;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    use SharedBetweenControllers;

    public function __construct(
        private ProductServiceInterface $productService,
        private MarketerProductServiceInterface $marketerProductService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate($inputs = [
            'title' => 'nullable',
            'merchantId' => 'nullable'
        ]);

        $data = $this->productService->getAllProductsWithPaginate(
            $this->getParams(array_keys($inputs)), $this->perPage(), $this->orderBy()
        );

        return response()->success($data);
    }

    public function productVisitCounts(Request $request)
    {
        $request->validate($inputs = [
            'fromDate' => 'nullable',
            'toDate' => 'nullable',
        ]);

        $inputs['marketerId'] = auth()->id();
        $request->merge(['marketerId' => auth()->id()]);

        $data = $this->marketerProductService->getAllProductsWithPaginate(
            $this->getParams(array_keys($inputs)), $this->perPage(), $this->orderBy()
        );

        return response()->success($data);
    }

    public function productAddForMarketing(Request $request)
    {
        $request->validate($inputs = [
            'productId' => 'required'
        ]);

        $inputs['marketerId'] = auth()->id();
        $request->merge(['marketerId' => auth()->id()]);

        $data = $this->marketerProductService->create($this->getParams(array_keys($inputs)));

        return response()->success($data);
    }
}
