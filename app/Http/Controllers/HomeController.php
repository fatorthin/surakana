<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'featuredProducts' => Product::query()
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->take(3)
                ->get(),
            'latestArticles' => Article::query()
                ->where('is_published', true)
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
