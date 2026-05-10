<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Transaction;
use App\Models\Employee;

$transactions = Transaction::all();
foreach ($transactions as $tx) {
    $tx->update([
        'driver_id'  => Employee::where('role', 'driver')->inRandomOrder()->first()?->id,
        'sorter_id'  => Employee::where('role', 'sorter')->inRandomOrder()->first()?->id,
        'washer_id'  => Employee::where('role', 'washer')->inRandomOrder()->first()?->id,
        'presser_id' => Employee::where('role', 'presser')->inRandomOrder()->first()?->id,
        'packer_id'  => Employee::where('role', 'packer')->inRandomOrder()->first()?->id,
    ]);
}

echo "Resynced " . $transactions->count() . " transactions.\n";
