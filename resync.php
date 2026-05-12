<?php
$transactions = App\Models\Transaction::all();
foreach ($transactions as $t) {
    $t->syncTotal();
    echo "Synced #{$t->id}: subtotal={$t->subtotal}, delivery={$t->delivery_fee}, asap={$t->asap_surcharge}, promo=-{$t->promo_discount}, total={$t->total_price}\n";
}
