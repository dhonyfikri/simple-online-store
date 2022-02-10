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
        $result_product = null;

        if ($id) {
            $result_product = $product->find($id);
        }
        if ($name) {
            $result_product = $product->where('name', 'like', '%' . $name . '%');
        }
        if ($description) {
            $result_product = $product->where('description', 'like', '%' . $description . '%');
        }
        if ($tags) {
            $result_product = $product->where('tags', 'like', '%' . $tags . '%');
        }
        if ($price_from) {
            $result_product = $product->where('price', '>=', $price_from);
        }
        if ($price_to) {
            $result_product = $product->where('price', '<=', $price_to);
        }
        if ($categories) {
            $result_product = $product->where('categories', $categories);
        }

        if ($result_product != null && $result_product->count() > 0) {
            return ResponseFormatter::success($id == null ? $result_product->paginate($limit) : $result_product, 'Data produk berhasil diambil.');
        } else {
            return ResponseFormatter::error(null, 'Data produk tidak ada.', 404);
        }
    }
}
