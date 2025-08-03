<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Services\DummyProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected DummyProductService $dummyProductService;
    
    public function __construct()
    {
        $this->dummyProductService = new DummyProductService();
    }

    public function index()
    {
        return Inertia::render('Products/Index', [
            'products' => [], // Replace with actual product data
        ]);
    }
    public function create(Request $request)
    {
        return Inertia::render('Products/Create', [
            'product' => $request->all(), // Replace with actual product creation logic
        ]);
    }

    
}
