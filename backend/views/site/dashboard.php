<?php
/* @var $this yii\web\View */

use yii\grid\GridView;

$this->title = 'SangkilBiz3-Distro';
?>
<div class="row site-index">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Sales <?= $mperiode ?></span>
                <span class="info-box-number"><?= ($msales > 0) ? number_format($msales, 0) : '-'; ?></span>

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
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-truck"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Open GR</span>
                <span class="info-box-number">
                    <?= ($mreceipt = '' ) ? $mreceipt : '-'; ?>
                </span>

                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <a href="#">Detail GR</a>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-plane"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Stock Transfer</span>
                <span class="info-box-number"><?= $mtransfer ?></span>

                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <a href="#">Detail Transfer</a>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
</div>
