<?php
/* @var $this yii\web\View */
$this->title = 'Reports';
?>
<div class="row">
    <div class="nav-tabs-justified col-lg-12">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#fico" data-toggle="tab" aria-expanded="false">Fi & Accounting</a></li>
            <li><a href = "#sales" data-toggle = "tab" aria-expanded = "false">Penjualan</a></li>
            <li><a href = "#pembelian" data-toggle = "tab" aria-expanded = "false">Pembelian</a></li>
            <li><a href = "#inventory" data-toggle = "tab" aria-expanded = "false">Persediaan Barang</a></li>
        </ul>
        <div class = "tab-content" >
            <div class = "tab-pane active" id = "fico"><?= $this->render('fico') ?></div>
            <div class = "tab-pane" id = "sales"><?= $this->render('sales') ?></div>
            <div class = "tab-pane" id = "pembelian"><?= $this->render('pembelian') ?></div>
            <div class = "tab-pane" id = "inventory"><?= $this->render('persediaan') ?></div>
        </div>
    </div>
</div>
