<?php
/* @var $this yii\web\View */

use yii\widgets\ListView;

$this->title = 'Dashboard ' . $mperiode;
?>
<div class="row site-index">
    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}",
        'itemView' => '_dashSales',
        'emptyText' => '&nbsp;'
    ]);
    ?> 
    <div class="col-lg-12"></div>
    <!--Transfer Stock-->
    <div class="col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Stock Transfer</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    <?=
                    ListView::widget([
                        'dataProvider' => $transfPro,
                        'layout' => "{items}",
                        'itemView' => '_dashTransfer',
                        'emptyText' => '&nbsp;'
                    ]);
                    ?>                    
                </ul>
            </div>
            <!-- /.box-body
            <div class="box-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
            /.box-footer -->
        </div>
    </div>
    <!--    Hutang Dagang-->
    <div class="col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Hutang Usaha</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    <?=
                    ListView::widget([
                        'dataProvider' => $hutangPro,
                        'layout' => "{items}\n{pager}",
                        'itemView' => '_dashHutang',
                        'emptyText' => '&nbsp;'
                    ]);
                    ?>                    
                </ul>
            </div>
            <!-- /.box-body
            <div class="box-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
            /.box-footer -->
        </div>
    </div>

</div>
