<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category', 'supplier')->paginate(10);
        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $categories = CategoryProduct::all();
        return view('products.create', compact('suppliers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:category_products,id',
            'product_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'threshold' => 'required|integer|min:0',
            'description' => 'required|string',
        ]);

        // Handle upload foto produk
        $photoPath = null;
        if ($request->hasFile('product_photo')) {
            $photoPath = $request->file('product_photo')->store('products', 'public');
        }

        // Simpan produk ke database
        Product::create([
            'product_name' => $request->product_name,
            'supplier_id' => $request->supplier_id,
            'category_id' => $request->category_id,
            'product_photo' => $photoPath,
            'stock' => $request->stock,
            'price' => $request->price,
            'threshold' => $request->threshold,
            'description' => $request->description,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Product has been added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $suppliers = Supplier::all();
        $categories = CategoryProduct::all();
        return view('products.show', compact('product', 'suppliers', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        $categories = CategoryProduct::all();
        return view('products.edit', compact('product', 'suppliers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:category_products,id',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stock' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'threshold' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('product_photo')) {
            // Delete old photo if exists
            if ($product->product_photo) {
                Storage::disk('public')->delete($product->product_photo);
            }
            $validatedData['product_photo'] = $request->file('product_photo')->store('products', 'public');
        }

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Check if product photo exists and delete it
            if ($product->product_photo && Storage::disk('public')->exists($product->product_photo)) {
                Storage::disk('public')->delete($product->product_photo);
            }

            // Delete the product from the database
            $product->delete();

            return redirect()->route('products.index')->with('success', __('Product has been deleted successfully!'));
        } catch (\Exception $e) {
            // Handle any errors during deletion
            return redirect()->route('products.index')->with('error', __('Failed to delete the product: ') . $e->getMessage());
        }
    }

    /**
     * Update the stock of the specified resource in storage.
     */
    public function updateStock(Request $request, Product $product)
    {
        // Validasi request
        $validated = $request->validate([
            'type' => 'required|in:in,out,offer',
            'quantity' => 'required|integer|min:1',
            'photo' => 'nullable|image|max:2048', // Foto opsional, maksimal 2MB
            'expired_at' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        // Proses upload foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('product_stock', 'public');
        }

        try {
            // Perbarui stok produk
            $product->stock += $validated['type'] === 'in' ? $validated['quantity'] : -$validated['quantity'];

            // Simpan perubahan stok
            $product->save();

            // Simpan log perubahan stok jika diperlukan
            $product->stockLogs()->create([
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'expired_at' => $validated['expired_at'],
                'note' => $validated['note'],
                'photo' => $photoPath,
                'user_id' => Auth::id(),
            ]);

            // Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return redirect()->back()->with('error', 'Gagal memperbarui stok: ' . $e->getMessage());
        }
    }

    public function exportPDF()
    {
        $products = Product::with('category', 'supplier')->get();
        $pdf = PDF::loadView('products.pdf', compact('products'));
        return $pdf->stream('products.pdf');
    }

}
