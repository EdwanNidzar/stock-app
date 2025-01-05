<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\History;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        // Validasi input
        $validated = $request->validate([
            'name_supplier' => 'required|string|max:255',
            'address_supplier' => 'required|string',
            'merchant' => 'required|string|max:255',
            'sosmed' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
        ]);

        // Simpan data supplier baru
        Supplier::create($validated);

        // Redirect dengan pesan sukses
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
       // Validasi input
        $validated = $request->validate([
            'name_supplier' => 'required|string|max:255',
            'address_supplier' => 'required|string',
            'merchant' => 'required|string|max:255',
            'sosmed' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
        ]);

        // Perbarui data supplier
        $supplier->update($validated);

        // Redirect dengan pesan sukses
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Hapus data supplier
        $supplier->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully!');
    }

    public function tableName($tableName)
    {
        $history = History::with('user') // Eager load the user relationship
            ->where('table_name', $tableName)
            ->get();

        return view('suppliers.index', compact('history')); // Return the history data to the view
    }

    public function exportPDF()
    {
        $suppliers = Supplier::withCount('products')->get();
        $pdf = PDF::loadView('suppliers.pdf', compact('suppliers'));
        return $pdf->stream('suppliers.pdf');
    }
}
