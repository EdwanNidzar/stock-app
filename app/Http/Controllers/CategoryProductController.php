<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategoryProduct::paginate(10);
        return view('categoryProduct.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoryProduct.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        CategoryProduct::create($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryProduct $category)
    {
        return view('categoryProduct.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryProduct $category)
    {
        return view('categoryProduct.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryProduct $category)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryProduct $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }

    public function exportPDF()
    {
        $categories = CategoryProduct::withCount('products')->get();
        $pdf = PDF::loadView('CategoryProduct.pdf', compact('categories'));
        return $pdf->stream('categories.pdf');
    }
}
