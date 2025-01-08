<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::all(); // Fetch products from the database
        
        return view('home', compact('products'));
    }


    public function usage(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,id',
        ]);

        // Fetch products based on product_id if provided, otherwise fetch all products
        $products = $request->has('product_id') 
            ? Product::where('id', $request->input('product_id'))->get() 
            : Product::all();

        $labels = [];
        $datasets = [];

        // Adjust the end date to include the entire day (23:59:59)
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay(); // Set to 23:59:59

        // Loop through products and collect usage data per day
        foreach ($products as $product) {
            // Initialize daily usage array
            $dailyUsage = [];

            // Loop through each date in the range and sum up stock out
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                // Fetch stock logs for each day in the range
                $stockLogs = $product->stockLogs()
                    ->where('type', 'out') // Only stock out logs
                    ->whereDate('created_at', $currentDate->toDateString()) // Match exact date
                    ->sum('quantity'); // Sum up the quantities for that day

                // Store daily usage data
                $dailyUsage[] = $stockLogs;

                // Move to the next day
                $currentDate->addDay();
            }

            // Collect labels (dates) and datasets (usage per product)
            $labels = [];
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $labels[] = $currentDate->toDateString();
                $currentDate->addDay();
            }
            $datasets[] = [
                'label' => $product->product_name,
                'data' => $dailyUsage,
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ];
        }

        // Return the results to the view, passing all products
        return view('home', compact('labels', 'datasets', 'startDate', 'endDate', 'products'));
    }

  
}
