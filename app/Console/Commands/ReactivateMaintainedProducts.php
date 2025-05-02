<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Carbon\Carbon;

class ReactivateMaintainedProducts extends Command
{
    protected $signature = 'reactivate:maintained-products';
    protected $description = 'Automatically reactivates products whose maintenance period has ended.';

    public function handle()
    {
        $now = Carbon::now();

        $affected = Product::where('status', 'maintenance')
            ->whereNotNull('maintenance_to')
            ->where('maintenance_to', '<=', $now)
            ->update([
                'status' => 'available',
                'maintenance_from' => null,
                'maintenance_to' => null,
            ]);

        $this->info("✅ Reactivated $affected products that finished maintenance.");
    }
}
