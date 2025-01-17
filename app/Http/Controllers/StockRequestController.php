<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockRequest;
use Illuminate\Support\Facades\Auth;

class StockRequestController extends Controller
{
    public function index()
    {
        $stockRequests = StockRequest::with('product', 'user')
            ->orderBy('created_at', 'desc')  // Mengurutkan berdasarkan tanggal pembuatan (terbaru)
            ->get();
        
        return view('stockRequests.index', compact('stockRequests'));
    }

    public function approve($id)
    {
        $stockRequest = StockRequest::findOrFail($id);

        $product = $stockRequest->product;

        // Perbarui stok produk
        $product->stock += $stockRequest->type === 'in' ? $stockRequest->quantity : -$stockRequest->quantity;
        $product->save();

        // Perbarui status permintaan stok
        $stockRequest->update(['status' => 'approved']);

        // Catat log stok
        $product->stockLogs()->create([
            'type' => $stockRequest->type,
            'quantity' => $stockRequest->quantity,
            'expired_at' => $stockRequest->expired_at,
            'note' => $stockRequest->note,
            'photo' => $stockRequest->photo,
            'user_id' => $stockRequest->user_id,
            'verified_by' => Auth::id(),
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('stockRequests.index')->with('success', 'Permintaan stok berhasil disetujui!');
    }

    public function reject($id)
    {
        $stockRequest = StockRequest::findOrFail($id);
        $stockRequest->update(['status' => 'rejected']);
        return redirect()->route('stockRequests.index')->with('success', 'Permintaan stok berhasil ditolak!');
    }

}
