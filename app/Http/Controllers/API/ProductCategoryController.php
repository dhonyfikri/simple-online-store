<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        $categories = ProductCategory::query();
        $result_categories = null;

        if ($id) {
            $result_categories = $categories->with(['products'])->find($id);
        }
        if ($name) {
            $result_categories = $categories->where('name', 'like', '%' . $name . '%');
        }
        if ($show_product) {
            $result_categories = $categories->with(['products']);
        }

        if ($result_categories != null && $result_categories->count() > 0) {
            return ResponseFormatter::success($id == null ? $result_categories->paginate($limit) : $result_categories, 'Data kategori berhasil diambil.');
        } else {
            return ResponseFormatter::error(null, 'Data kategori tidak ada.', 404);
        }
    }
}
