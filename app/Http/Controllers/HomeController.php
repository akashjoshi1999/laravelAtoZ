<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\DummyProductService;

class HomeController extends Controller
{
    protected DummyProductService $dummyProductService;
    
    public function __construct()
    {
        $this->dummyProductService = new DummyProductService();
    }
    
    public function index(Request $request, DummyProductService $service)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $limit = $request->query('limit', 12);
        $skip = $request->query('skip', 0);

        $products = $service->getProducts($search, $category, $limit, $skip);
        $categories = $service->getCategories();

        return Inertia::render('Welcome', [
            'products' => $products['products'] ?? [],
            'total' => $products['total'] ?? 0,
            'limit' => $limit,
            'skip' => $skip,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => $category ?? 'all',
            ],
        ]);
    }


    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
}
