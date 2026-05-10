<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Transaction;

$tx = Transaction::create([
    'customer_id' => 1,
    'customer_name' => 'Test ' . time(),
    'invoice_code' => 'T-' . time(),
    'total_price' => 0,
    'laundry_status' => 'Pending',
    'payment_status' => 'Paid'
]);

echo "Invoice: " . $tx->invoice_code . "\n";
echo "Driver: " . ($tx->driver->name ?? 'N/A') . " (" . ($tx->driver->role ?? 'N/A') . ")\n";
echo "Sorter: " . ($tx->sorter->name ?? 'N/A') . " (" . ($tx->sorter->role ?? 'N/A') . ")\n";
echo "Washer: " . ($tx->washer->name ?? 'N/A') . " (" . ($tx->washer->role ?? 'N/A') . ")\n";
echo "Presser: " . ($tx->presser->name ?? 'N/A') . " (" . ($tx->presser->role ?? 'N/A') . ")\n";
echo "Packer: " . ($tx->packer->name ?? 'N/A') . " (" . ($tx->packer->role ?? 'N/A') . ")\n";
