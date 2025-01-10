<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockRequest;

class StockRequestController extends Controller
{
    public function index()
    {
        $stockRequests = StockRequest::with('product', 'user')->where('status', 'pending')->get();
        return view('stockRequests.index', compact('stockRequests'));
    }

    public function approve($id)
    {
        $stockRequest = StockRequest::findOrFail($id);

        $product = $stockRequest->product;
        $product->stock += $stockRequest->type === 'in' ? $stockRequest->quantity : -$stockRequest->quantity;
        $product->save();

        $stockRequest->update(['status' => 'approved']);

        return redirect()->route('stockRequests.index')->with('success', 'Permintaan stok berhasil disetujui!');
    }

    public function reject($id)
    {
        $stockRequest = StockRequest::findOrFail($id);
        $stockRequest->update(['status' => 'rejected']);
        return redirect()->route('stockRequests.index')->with('success', 'Permintaan stok berhasil ditolak!');
    }

}
