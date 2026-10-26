<?php
// Hitung PPN
function hitungPpn($subtotal, $rate = 0.11) {
    return round($subtotal * $rate);
}

// Hitung Grandtotal
function hitungGrandTotal($subtotal, $ppn) {
    return $subtotal + $ppn;
}
?>