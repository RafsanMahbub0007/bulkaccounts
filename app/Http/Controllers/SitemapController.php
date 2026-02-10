<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Post;

class SitemapController extends Controller
{
    public function index()
    {
        // Static URLs
        $urls = [
            route('home'),
            route('pricing'),
            route('about'),
            route('faq'),
            route('guidlines'),
            route('blog'),
            route('terms'),
            route('privacy'),
            route('contact'),
        ];

        // Products
        $products = Product::where('is_active', true)->get();
        foreach ($products as $product) {
            $urls[] = route('product.details', $product->slug);
        }

        // Categories
        $categories = Category::where('is_active', true)->get();
        foreach ($categories as $category) {
            $urls[] = route('category.details', $category->slug);
        }

        // SubCategories
        $subCategories = SubCategory::where('is_active', true)->with('category')->get();
        foreach ($subCategories as $subCategory) {
            if ($subCategory->category) {
                $urls[] = route('subcategory.details', [
                    'category' => $subCategory->category->slug,
                    'subcategory' => $subCategory->slug
                ]);
            }
        }

        // Posts (Blog)
        $posts = Post::where('published', true)->get();
        foreach ($posts as $post) {
            $urls[] = route('post.show', $post->slug);
        }

        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
