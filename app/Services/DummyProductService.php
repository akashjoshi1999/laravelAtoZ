<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DummyProductService
{
    protected string $dummyJsonUrl;

    public function __construct()
    {
        $this->dummyJsonUrl = config('app.dummy_json_url', 'https://dummyjson.com');
    }

    public function getCategories(): array
    {
        return Cache::remember('dummy_categories', now()->addHours(6), function () {
            $res = Http::get($this->dummyJsonUrl . '/products/categories');
            return $res->successful() ? $res->json() : [];
        });
    }

    public function getProducts(?string $search = null, ?string $category = null, int $limit = 12, int $skip = 0): array
    {
        $query = [
            'limit' => $limit,
            'skip' => $skip,
        ];

        if ($search) {
            $query['q'] = $search;
            $response = Http::get($this->dummyJsonUrl . '/products/search', $query);
        } elseif ($category && $category !== 'all') {
            $response = Http::get($this->dummyJsonUrl . "/products/category/{$category}", $query);
        } else {
            $response = Http::get($this->dummyJsonUrl . '/products', $query);
        }

        return $response->successful() ? $response->json() : [];
    }

    public function getProductById(int $id): ?array
    {
        return Cache::remember("product_{$id}", now()->addMinutes(30), function () use ($id) {
            $response = Http::get($this->dummyJsonUrl . "/products/{$id}");
            return $response->successful() ? $response->json() : null;
        });
    }

}
