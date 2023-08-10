<?php

namespace App\Http\Controllers\Marketer;

use App\Contracts\MarketerProductServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\DTO\MarketerProductDto;
use App\DTO\ProductDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Marketer\AddProductForMarketerRequest;
use App\Http\Requests\Marketer\IndexRequest;
use App\Http\Requests\Marketer\MarketerProductsRequest;
use App\Traits\SharedBetweenControllers;

class MarketingController extends Controller
{
    use SharedBetweenControllers;

    public function __construct(
        private ProductServiceInterface $productService,
        private MarketerProductServiceInterface $marketerProductService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $dto = ProductDto::fromRequest($request->safe());

        $data = $this->productService->getAllProductsWithPaginate(
            $dto, $this->perPage(), $this->orderBy()
        );

        return response()->success($data);
    }

    public function marketerProducts(MarketerProductsRequest $request)
    {
        $validatedRequest = $request->safe()->merge(['marketerId' => auth()->id()]);

        $dto = MarketerProductDto::fromRequest($validatedRequest);

        $data = $this->marketerProductService->getAllProductsWithPaginate(
            $dto, $this->perPage(), $this->orderBy()
        );

        return response()->success($data);
    }

    public function addProductForMarketer(AddProductForMarketerRequest $request)
    {
        $validatedRequest = $request->safe()->merge(['marketerId' => auth()->id()]);

        $dto = MarketerProductDto::fromRequest($validatedRequest);

        $data = $this->marketerProductService->create($dto);

        return response()->success($data);
    }
}
