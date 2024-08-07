<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\TagRepositoryInterface;
use App\Services\CatalogService;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $catalogService;
    protected $productRepository;
    protected $tagRepository;

    public function __construct(
        CatalogService $catalogService,
        ProductRepositoryInterface $productRepository,
        TagRepositoryInterface $tagRepository,
    ){
        $this->catalogService = $catalogService;
        $this->productRepository = $productRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index()
    {
        $products = $this->productRepository->all()->map(function ($product) {
            $product->tag_names = $product->tags->pluck('name')->implode(', ');
            return $product;
        });

        return view('products.index', compact('products'));
    }


    public function create()
    {
        $categories = $this->productRepository->all();
        $tags = $this->tagRepository->all();
        return view('products.create', compact('categories', 'tags'));
    }


    public function store(StoreRequest $request)
    {
                $data = $request->validated();
        try {
            $product = $this->catalogService->createProduct($data);
            return response()->json([
                'message' => 'Product added successfully!',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating product'], 500);
        }
    }

    public function show($id)
    {
        $product = $this->productRepository->find($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = $this->productRepository->find($id);
        $categories = $this->productRepository->all();
        $tags = $this->tagRepository->all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $product = $this->catalogService->updateProduct($id, $data);
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении продукта: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Ошибка при обновлении продукта.');
        }
    }

    public function destroy($id)
    {
        try {
            $this->catalogService->deleteProduct($id);
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Ошибка при удалении продукта: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Ошибка при удалении продукта.');
        }
    }
}
