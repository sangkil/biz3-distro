<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="col-lg-6">
    <h2>Daftar Penjualan</h2>
    <p>Menunjukkan daftar kronologis dari semua faktur dan pembayaran untuk rentang tanggal yang dipilih..</p>
    <p><?= \yii\bootstrap\Html::a('Daftar Penjualan &raquo;', ['/sales/sales/index'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Penjualan per Pelanggan</h2>
    <p>Menampilkan setiap transaksi penjualan untuk setiap pelanggan, termasuk tanggal, tipe, jumlah dan total..</p>
    <p><a class="btn btn-default" href="#">Get Started &raquo;</a></p>
</div>
<div class="col-lg-6">
    <h2>Laporan Piutang Pelanggan</h2>
    <p>Menampilkan tagihan yang belum dibayar untuk setiap pelanggan, termasuk nomor & tanggal faktur, tanggal jatuh tempo, jumlah nilai, dan sisa tagihan yang terhutang pada Anda..</p>
    <p><a class="btn btn-default" href="#">Get Started &raquo;</a></p>
</div>
<div class="col-lg-6">
    <h2>Penjualan per Barang</h2>
    <p>Menampilkan daftar kuantitas penjualan per produk, termasuk jumlah retur, net penjualan, dan harga penjualan rata-rata.</p>
    <p><a class="btn btn-default" href="#">Get Started &raquo;</a></p>
</div>