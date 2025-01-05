<?php

namespace App\Observers;

use App\Models\History;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierObserver
{
    /**
     * Handle the Supplier "created" event.
     */
    public function created(Supplier $supplier): void
    {
        History::create([
            'table_name' => 'suppliers',
            'user_id' => Auth::id(),
            'action' => 'insert',
            'old_data' => null,
            'new_data' => $supplier->toArray(),
        ]);
    }

    /**
     * Handle the Supplier "updated" event.
     */
    public function updated(Supplier $supplier): void
    {
        History::create([
            'table_name' => 'suppliers',
            'user_id' => Auth::id(),
            'action' => 'update',
            'old_data' => $supplier->getOriginal(),
            'new_data' => $supplier->getChanges(),
        ]);
    }

    /**
     * Handle the Supplier "deleted" event.
     */
    public function deleted(Supplier $supplier): void
    {
        History::create([
            'table_name' => 'suppliers',
            'user_id' => Auth::id(),
            'action' => 'delete',
            'old_data' => $supplier->toArray(),
            'new_data' => null,
        ]);
    }

    /**
     * Handle the Supplier "restored" event.
     */
    public function restored(Supplier $supplier): void
    {
        //
    }

    /**
     * Handle the Supplier "force deleted" event.
     */
    public function forceDeleted(Supplier $supplier): void
    {
        //
    }
}
