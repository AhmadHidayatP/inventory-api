<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // 1. POST /products - Tambah produk baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::create($validator->validated());

        return response()->json($product, 201);
    }

    // 2. GET /products - Ambil semua produk
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    // 3. GET /products/{id} - Ambil detail produk
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // 4. PUT /products/{id} - Update produk
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'price' => 'numeric|min:0',
            'stock_quantity' => 'integer|min:0',
            'category_id' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product->update($validator->validated());

        return response()->json($product);
    }

    // 5. DELETE /products/{id} - Hapus produk
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    // 6. GET /products/search?name=abc&category_id=1 - Pencarian
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->with('category')->get());
    }

    // 7. POST /products/update-stock - Update stok
    public function updateStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($request->product_id);
        $product->stock_quantity = $request->stock_quantity;
        $product->save();

        return response()->json(['message' => 'Stock updated successfully', 'product' => $product]);
    }

    // 8. GET /inventory/value - Hitung total nilai inventaris
    public function inventoryValue()
    {
        $total = Product::sum(\DB::raw('price * stock_quantity'));
        return response()->json(['total_inventory_value' => $total]);
    }
}
