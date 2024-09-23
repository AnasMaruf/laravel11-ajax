<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Service\ImageService;
use App\Service\ProductService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    public function __construct(private ProductService $productService, private ImageService $imageService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            $uploadImage = $this->imageService->uploadImg($data);
            $data['image'] = $uploadImage;
            $this->productService->create($data);
            return response()->json(['title' => 'Good Job','text'=>'Product created successfully','icon' => 'success']);
        } catch (Exception $error) {
            return response()->json(['title'=>'Error','text'=>$error->getMessage(),'icon'=>'error']);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->productService->getByUid($product->uuid),
            ]);
        } catch (Exception $error) {
            //throw $th;
        }

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $getImage = $this->productService->getByUid($product->uuid);
        try {
            if ($request->hasFile('image')) {
                $uploadImage = $this->imageService->uploadImg($data, $getImage->image);
                $data['image'] = $uploadImage;
            }

            $this->productService->update($data, $product->uuid);

            return response()->json(['title' => 'Good Job','text'=>'Product updated successfully','icon' => 'success']);
        } catch (Exception $error) {
            return response()->json(['title'=>'Error','text'=>$error->getMessage(),'icon'=>'error']);
        }

        // $data['uuid'] = Str::uuid();
        // $data['slug'] = Str::slug($data['name']);
        // Product::find($product->id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productService->delete($product->uuid);
        return response()->json(['message'=>'Product deleted successfully']);
    }

    public function serversideTable(): JsonResponse
    {
        return $this->productService->getDatatable();
    }
}
