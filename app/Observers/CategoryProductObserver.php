<?php

namespace App\Observers;

use App\Models\CategoryProduct;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class CategoryProductObserver
{
    /**
     * Handle the CategoryProduct "created" event.
     */
    public function created(CategoryProduct $categoryProduct): void
    {
        History::create([
            'table_name' => 'category_products',
            'user_id' => Auth::id(),
            'action' => 'insert',
            'old_data' => null,
            'new_data' => $categoryProduct->toArray(),
        ]);
    }

    /**
     * Handle the CategoryProduct "updated" event.
     */
    public function updated(CategoryProduct $categoryProduct): void
    {
        History::create([
            'table_name' => 'category_products',
            'user_id' => Auth::id(),
            'action' => 'update',
            'old_data' => $categoryProduct->getOriginal(),
            'new_data' => $categoryProduct->getChanges(),
        ]);
    }

    /**
     * Handle the CategoryProduct "deleted" event.
     */
    public function deleted(CategoryProduct $categoryProduct): void
    {
        History::create([
            'table_name' => 'category_products',
            'user_id' => Auth::id(),
            'action' => 'delete',
            'old_data' => $categoryProduct->toArray(),
            'new_data' => null,
        ]);
    }

    /**
     * Handle the CategoryProduct "restored" event.
     */
    public function restored(CategoryProduct $categoryProduct): void
    {
        //
    }

    /**
     * Handle the CategoryProduct "force deleted" event.
     */
    public function forceDeleted(CategoryProduct $categoryProduct): void
    {
        //
    }
}
