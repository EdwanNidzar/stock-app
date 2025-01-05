<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        History::create([
            'table_name' => 'products',
            'user_id' => Auth::id(),
            'action' => 'insert',
            'old_data' => null,
            'new_data' => $product->toArray(),
        ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        History::create([
            'table_name' => 'products',
            'user_id' => Auth::id(),
            'action' => 'update',
            'old_data' => $product->getOriginal(),
            'new_data' => $product->getChanges(),
        ]);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        History::create([
            'table_name' => 'products',
            'user_id' => Auth::id(),
            'action' => 'delete',
            'old_data' => $product->toArray(),
            'new_data' => null,
        ]);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
