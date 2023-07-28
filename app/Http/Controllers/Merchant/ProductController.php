<?php

namespace App\Http\Controllers\Merchant;

use App\Contracts\ProductServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\SharedBetweenControllers;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use SharedBetweenControllers;

    public function __construct(
        private ProductServiceInterface $productService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate($inputs = [
            'title' => 'nullable'
        ]);

        $inputs['merchantId'] = auth()->id();
        $request->merge(['merchantId' => auth()->id()]);

        $data = $this->productService->getAllProductsWithPaginate(
            $this->getParams(array_keys($inputs)), $this->perPage(), $this->orderBy()
        );

        return response()->success($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($inputs = [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png',
            'url' => 'required|url',
        ]);

        $data = $this->productService->store($this->getParams(array_keys($inputs)));

        if ($data)
            return response()->success($data);
        else
            return response()->error('not successfully create');
    }

    /**
     * Display the specified resource.
     */
    public function show($product)
    {
        return response()->success($this->productService->show($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Product $product, Request $request)
    {
        $request->validate($inputs = [
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'url' => 'required|url',
        ]);

        $data = $this->productService->update($product, $this->getParams(array_keys($inputs)));

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
        $data = $this->productService->delete($product);

        if ($data)
            return response()->success('deleted successfully');
        else
            return response()->error('not successfully deleted');
    }
}
