<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\CategoryProduct;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
            'unit' => 'required',
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
            'unit' => $request->unit,
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
            'unit' => 'required',
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

    /**
     * Tampilkan form cek stok.
     */
    public function showCheckStockForm()
    {
        $products = Product::all(); // Ambil semua produk
        return view('products.check-stock', compact('products'));
    }

    /**
     * Periksa kesesuaian stok produk.
     */
    public function checkProductStock(Request $request)
    {
        $data = $request->input('products'); // Ambil input produk
        $report = [];
        $totalItems = count($data);
        $itemsLurus = 0;

        foreach ($data as $item) {
            $product = Product::find($item['product_id']);

            if ($product) {
                $status = '';
                // Logika pengecekan stok
                if ($product->stock == $item['real_stock']) {
                    $status = 'Stok Sesuai';
                    $itemsLurus++;
                } else {
                    if ($product->stock < $item['real_stock']) {
                        $status = 'Stok Sistem lebih sedikit daripada Real';
                    } elseif ($product->stock > $item['real_stock']) {
                        $status = 'Stok Sistem lebih banyak daripada Real';
                    }
                }

                // Ambil log terkait produk
                $stockLogs = StockLog::where('product_id', $item['product_id'])->orderBy('created_at', 'desc')->get();

                $report[] = [
                    'product_name' => $product->product_name,
                    'product_photo' => $product->product_photo,
                    'system_stock' => $product->stock,
                    'real_stock' => $item['real_stock'],
                    'status' => $status,
                    'logs' => $stockLogs,
                ];
            } else {
                $report[] = [
                    'product_name' => 'Produk Tidak Ditemukan',
                    'product_photo' => null,
                    'system_stock' => null,
                    'real_stock' => $item['real_stock'],
                    'status' => 'Tidak Ditemukan',
                    'logs' => [],
                ];
            }
        }

        // Menghitung disiplin stok
        $disciplinePercentage = ($itemsLurus / $totalItems) * 100;

        // Ambil data produk untuk dropdown
        $products = Product::all();

        return view('products.check-stock', compact('report', 'products', 'disciplinePercentage'));
    }

    public function exportStockReport(Request $request)
    {
        $report = json_decode($request->input('report'), true); // Convert JSON to array
        $disciplinePercentage = $request->input('disciplinePercentage');

        if (!$report) {
            return redirect()->back()->with('error', __('Invalid report data.'));
        }

        // Modify the report data structure
        $modifiedReport = [];
        foreach ($report as $item) {
            $modifiedReport[] = [
                'product_name' => $item['product_name'],
                'product_photo' => $item['product_photo'],
                'system_stock' => $item['system_stock'],
                'real_stock' => $item['real_stock'],
                'status' => $item['status'],
                'logs' => $item['logs'],
            ];
        }

        $pdf = Pdf::loadView('products.stock-report-pdf', compact('modifiedReport', 'disciplinePercentage'))->setPaper('a4', 'landscape');

        return $pdf->stream('stock-report.pdf');
    }

    public function showUsageForm()
    {
        $products = Product::all();
        return view('products.usage-form', compact('products'));
    }

    public function showUsage(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Fetch all products
        $products = Product::all();

        $usageData = [];

        // Adjust the end date to include the entire day (23:59:59)
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay(); // Set to 23:59:59

        foreach ($products as $product) {
            // Fetch the stock logs for the product within the adjusted date range
            $stockLogs = $product->stockLogs()
                ->where('type', 'out') // Only stock out logs
                ->whereBetween('created_at', [$startDate, $endDate]) // Use adjusted date range
                ->with('user') // Assuming there's a relationship to fetch the user who issued the stock
                ->get();

            // Calculate total stock out within the selected date range
            $totalStockOut = $stockLogs->sum('quantity');
            
            // Calculate the number of days between start and end date
            $days = $startDate->diffInDays($endDate) + 1; // +1 to include the start day

            // Calculate the usage per day
            $usagePerDay = $totalStockOut > 0 ? $totalStockOut / $days : 0;

            $usageData[] = [
                'product_name' => $product->product_name,
                'product_photo' => $product->product_photo,
                'unit' => $product->unit,
                'total_stock_out' => $totalStockOut,
                'usage_per_day' => $usagePerDay,
                'stock_logs' => $stockLogs // Add the stock logs to display
            ];
        }

        // Return the results to the view
        return view('products.usage', compact('usageData', 'startDate', 'endDate'));
    }

    public function downloadUsageReport(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Fetch all products and usage data
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        $products = Product::all();

        $usageData = [];
        foreach ($products as $product) {
            $stockLogs = $product->stockLogs()
                ->where('type', 'out')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->with('user')
                ->get();

            $totalStockOut = $stockLogs->sum('quantity');
            $days = $startDate->diffInDays($endDate) + 1;
            $usagePerDay = $totalStockOut > 0 ? $totalStockOut / $days : 0;

            $usageData[] = [
                'product_name' => $product->product_name,
                'product_photo' => $product->product_photo,
                'unit' => $product->unit,
                'total_stock_out' => $totalStockOut,
                'usage_per_day' => $usagePerDay,
                'stock_logs' => $stockLogs,
            ];
        }

        // Generate the PDF view
        $pdf = Pdf::loadView('products.usage-pdf', compact('usageData', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');

        // Download the PDF
        return $pdf->stream('Product_Usage_Report.pdf');
    }

}
