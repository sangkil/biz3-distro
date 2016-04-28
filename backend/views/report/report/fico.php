<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="col-lg-6">
    <h2>Neraca</h2>
    <p>Menampilan apa yang anda miliki (aset), apa yang anda hutang (liabilitas), dan apa yang anda sudah investasikan pada perusahaan anda (ekuitas).</p>
    <p><?= \yii\bootstrap\Html::a('Neraca &raquo;', ['#'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Buku Besar</h2>
    <p>Laporan ini menampilkan semua transaksi yang telah dilakukan untuk suatu periode. Laporan ini bermanfaat jika Anda memerlukandaftar kronologis untuk semua transaksi yang telah dilakukan oleh perusahaan Anda.</p>
    <p><?= \yii\bootstrap\Html::a('Buku Besar &raquo;', ['#'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Laba Rugi</h2>
    <p>Menampilkan setiap tipe transaksi dan jumlah total untuk pendapatan dan pengeluaran anda.</p>
    <p><?= \yii\bootstrap\Html::a('Laba/Rugi &raquo;', ['#'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Arus Kas/Bank</h2>
    <p>Laporan ini mengukur kas yang telah dihasilkan atau digunakan oleh suatu perusahaan dan menunjukkan detail pergerakannya dalam suatu periode.</p>
    <p><?= \yii\bootstrap\Html::a('Kas/Bank &raquo;', ['#'], ['class' => 'btn btn-default']) ?></p>
</div>
<div class="col-lg-6">
    <h2>Jurnal</h2>
    <p>Daftar semua jurnal per transaksi yang terjadi dalam periode waktu. Hal ini berguna untuk melacak di mana transaksi Anda masuk ke masing-masing rekening</p>
    <p><?= \yii\bootstrap\Html::a('Jurnal Umum &raquo;', ['/accounting/general-ledger'], ['class' => 'btn btn-default']) ?></p>
</div>
