<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:150',
            'barcode'     => 'nullable|string|max:50|unique:products,barcode',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:1',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            $data['image'] = null;
        }

        $data['is_active'] = $request->boolean('is_active', true);

        Product::create($data);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:150',
            'barcode'     => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:1',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $product->update($data);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * AJAX: Lookup product by barcode for scanner input.
     */
    public function lookupByBarcode(Request $request)
    {
        $code = trim($request->input('code', ''));
        if ($code === '') {
            return response()->json(['found' => false]);
        }

        $product = Product::where('barcode', $code)
            ->orWhere('id', $code)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->first();

        if (!$product) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'product' => [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'barcode' => $product->barcode,
            ],
        ]);
    }
}
