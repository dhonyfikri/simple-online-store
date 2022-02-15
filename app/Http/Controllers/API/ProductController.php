<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        $product = Product::with(['category', 'galleries']);

        if ($id) {
            $product = $product->find($id);
        }
        if ($name) {
            $product = $product->where('name', 'like', '%' . $name . '%');
        }
        if ($description) {
            $product = $product->where('description', 'like', '%' . $description . '%');
        }
        if ($tags) {
            $product = $product->where('tags', 'like', '%' . $tags . '%');
        }
        if ($price_from) {
            $product = $product->where('price', '>=', $price_from);
        }
        if ($price_to) {
            $product = $product->where('price', '<=', $price_to);
        }
        if ($categories) {
            $product = $product->where('categories', $categories);
        }

        if ($product->count() > 0) {
            return ResponseFormatter::success($id == null ? $product->paginate($limit) : $product, 'Data produk berhasil diambil.');
        } else {
            return ResponseFormatter::error(null, 'Data produk tidak ada.', 404);
        }
    }
}
