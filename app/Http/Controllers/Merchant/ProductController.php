<?php

namespace App\Http\Controllers\Merchant;

use App\Contracts\ProductServiceInterface;
use App\DTO\ProductDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\IndexRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Traits\SharedBetweenControllers;

class ProductController extends Controller
{
    use SharedBetweenControllers;

    public function __construct(
        private ProductServiceInterface $productService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $validatedRequest = $request->safe()->merge(['merchantId' => auth()->id()]);

        $dto = ProductDto::fromRequest($validatedRequest);

        $data = $this->productService->getAllProductsWithPaginate(
            $dto, $this->perPage(), $this->orderBy()
        );

        return response()->success($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $imageName = !empty($request->image) ? time().'_'.$request->image->getClientOriginalName() : null;
        $validatedRequest = $request->safe()->merge(['imageName' => $imageName, 'merchantId' => auth()->id()]);

        $dto = ProductDto::fromRequest($validatedRequest);

        $data = $this->productService->store($dto);

        if ($data)
            return response()->success($data);
        else
            return response()->error('not successfully create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->success($this->productService->show($product, auth()->id()));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Product $product, UpdateRequest $request)
    {
        $imageName = !empty($request->image) ? time().'_'.$request->image->getClientOriginalName() : $product->getRawOriginal('image');
        $validatedRequest = $request->safe()->merge(['imageName' => $imageName, 'merchantId' => auth()->id()]);

        $dto = ProductDto::fromRequest($validatedRequest);

        $data = $this->productService->update($product, $dto);

        if ($data)
            return response()->success($data);
        else
            return response()->error('not successfully update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $data = $this->productService->delete($product, auth()->id());

        if ($data)
            return response()->success('deleted successfully');
        else
            return response()->error('not successfully deleted');
    }
}
