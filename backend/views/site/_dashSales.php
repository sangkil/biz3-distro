<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
$warna = ['bg-aqua','bg-yellow','bg-red','bg-green'];
$dcolor = $warna[array_rand($warna)]; 
?>
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box <?= $dcolor ?>">
        <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">SALES: <?= $model->branch->name ?></span>
            <span class="info-box-number"><?= number_format($model->value,0) ?></span>

            <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
            </div>
            <span class="progress-description">
                <a href="#">Detail Sales</a>
            </span>
        </div>
        <!-- /.info-box-content -->
    </div>  
</div>
