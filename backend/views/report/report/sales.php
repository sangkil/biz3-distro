<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="col-lg-6">
    <h2>Detail</h2>
    <p>Menunjukkan detail penjualan untuk rentang tanggal yang dipilih..</p>
    <p><?= \yii\bootstrap\Html::a('Penjualan Detail &raquo;', ['/sales/sales/index'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Harian</h2>
    <p>Menunjukkan rekam penjualan perfaktur untuk tanggal yg dipilih </p>
    <p><?= \yii\bootstrap\Html::a('Penjualan Harian &raquo;', ['/sales/sales/daily'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Mingguan/Barang</h2>
    <p>Rekam penjualan perbarang untuk laporan prinsipal untuk rentang mingguan</p>
    <p><?= \yii\bootstrap\Html::a('Penjualan Mingguan &raquo;', ['/sales/sales/by-product-week'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Bulanan/Barang</h2>
    <p>Rekam penjualan perbarang untuk laporan prinsipal untuk rentang bulanan</p>
    <p><?= \yii\bootstrap\Html::a('Penjualan Bulanan &raquo;', ['/sales/sales/by-product-month'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Penjualan per Group</h2>
    <p>Menampilkan setiap transaksi penjualan untuk setiap prinsipal/group, termasuk tanggal, tipe, jumlah dan total..</p>    
    <p><?= \yii\bootstrap\Html::a('Penjualan perGroup &raquo;', ['/sales/sales/by-product-group'], ['class' => 'btn btn-default']) ?></p>
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