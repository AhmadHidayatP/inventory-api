<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // 6. POST /categories - Tambah kategori baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::create($validator->validated());

        return response()->json($category, 201);
    }

    // 7. GET /categories - Ambil semua kategori
    public function index()
    {
        $categories = Category::with('products')->get();
        return response()->json($categories);
    }
}
