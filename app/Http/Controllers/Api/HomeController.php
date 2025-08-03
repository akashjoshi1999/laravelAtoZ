<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\DummyProductService;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    protected DummyProductService $dummyProductService;
    
    public function __construct()
    {
        $this->dummyProductService = new DummyProductService();
    }
    

    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $limit = $request->query('limit', 12);
        $skip = $request->query('skip', 0);

        $products = $this->dummyProductService->getProducts($search, $category, $limit, $skip);
        $categories = $this->dummyProductService->getCategories();

        return response()->json([
            'products' => $products['products'] ?? [],
            'categories' => $categories,
            'total' => $products['total'] ?? 0,
        ]);
    }

    public function show($id)
    {
        $product = $this->dummyProductService->getProductById($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => $product
        ]);
    }


}
