<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php echo Html::img('@web/../../vendor/bower/adminlte/dist/img/user1-128x128.jpg', ['class' => 'img-circle', 'alt' => 'User Image']); ?>
            </div>
            <div class="pull-left info">
                <p>User Login</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->

        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li><a href="<?= Url::to(['/site/index']); ?>"><i class="fa fa-dashboard active"></i> <span>Dashboard</span></a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-wrench"></i>
                    <span>Tools</span>         
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" >
                    <li><a href="<?= Url::to(['/organizations/index']); ?>"><i class="fa fa-check"></i> Manage Orgn</a></li>
                    <li><a href="<?= Url::to(['/branch/index']); ?>"><i class="fa fa-check"></i> Manage Divisi</a></li>
                    <li style="<?php echo (\Yii::$app->user->can('/admin/assignment/index')) ? '' : 'display:none;'; ?>"><a href="<?= Url::to(['/admin/assignment']); ?>"><i class="fa fa-check"></i> Manage Users</a></li>
                    <li style="<?php echo (\Yii::$app->user->can('/admin/assignment/index')) ? '' : 'display:none;'; ?>"><a href="#"><i class="fa fa-check"></i> App Configuration</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-th"></i> 
                    <span>Data Master</span>          
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= Url::to(['/master/product/index']); ?>"><i class="fa fa-check"></i> Product</a></li>
                    <li><a href="<?= Url::to(['/master/customers/index']); ?>"><i class="fa fa-check"></i> Outlet</a></li>
                    <li><a href="<?= Url::to(['/master/groups/index']); ?>"><i class="fa fa-check"></i> Prinsipal</a></li>
                    <li><a href="<?= Url::to(['/master/vehicles/index']); ?>"><i class="fa fa-check"></i> Vehicles</a></li>                    
                    <li>
                        <a href="<?= Url::to(['/master/warehouse/index']); ?>"><i class="fa fa-check"></i> Warehouse</a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart text-orange"></i>
                    <span>Sales & Distribution</span>                    
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-check"></i> Sales Order</a></li>
                    <li><a href="#"><i class="fa fa-check"></i> Sales Return</a></li>
                    <li><a href="#"><i class="fa fa-check"></i> Sales Journey</a></li>
                    <li style="<?php echo (\Yii::$app->user->can('/loreal/index')) ? '' : 'display:none;'; ?>">
                        <a href="#"><i class="fa fa-check text-orange"></i> L'Real Interface<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?= Url::to(['/loreal/import-faktur']); ?>"><i class="fa fa-cloud-upload"></i> Upload Faktur</a></li>
                            <li><a href="<?= Url::to(['/loreal/import-retur']); ?>"><i class="fa fa-cloud-upload"></i> Upload Retur</a></li>
                            <li><a href="<?= Url::to(['/loreal/export-pelunasan']); ?>"><i class="fa fa-download"></i> Export Pelunasan</a></li>          
                        </ul>
                    </li>
                    <li style="<?php echo (\Yii::$app->user->can('/intrasari/index')) ? '' : 'display:none;'; ?>">
                        <a href="#"><i class="fa fa-check text-orange"></i> Intrasari Interface<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?= Url::to(['/intrasari/import-faktur']); ?>"><i class="fa fa-cloud-upload"></i> Upload Faktur</a></li>    
                        </ul>
                    </li>
                </ul>
            </li>  
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck  text-success"></i> <span>Warehouse Mangmnt</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#"><i class="fa fa-check"></i> Goods Receipt<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> DN</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Mutasi Dari Gdg</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Return</a></li>                           
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> Goods Issued <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> Surat Jalan</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Mutasi Ke Gdg</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Cancel Issue</a></li>                            
                        </ul>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/inventory/do-kanvas']); ?>"><i class="fa fa-check text-success"></i> DO Kanvas</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> Stock Management <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> Adjust by Opname</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Adjustment</a></li>                           
                        </ul>
                    </li>
                </ul>
            </li>            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-buysellads"></i>
                    <span>FI & Accounting</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Url::to(['/account/index']); ?>"><i class="fa fa-check"></i> COA</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> General Journal</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> Cash In<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> Penerimaan SO</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Piutang Karyawan</a></li> 
                            <li><a href="#"><i class="fa fa-check"></i> Penerimaan Lain</a></li>                          
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> Cash Out<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> Biaya-biaya</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Pembelian Asset</a></li>  
                            <li><a href="#"><i class="fa fa-check"></i> Setoran Bank</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Piutang Karyawan</a></li>     
                            <li><a href="#"><i class="fa fa-check"></i> Prive Owner</a></li>                            
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> Bank In<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> Giro SO</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Clearing Giro</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> Transfer SO</a></li>  
                            <li><a href="#"><i class="fa fa-check"></i> Transfer Lain</a></li>           
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> Bank Out<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> Penarikan Tunai</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>  
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>                          
                        </ul>
                    </li>
                </ul>
            </li> 
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-pie-chart text-aqua"></i>
                    <span>Reports</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#"><i class="fa fa-check text-aqua"></i> Sales Report<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?= Url::to(['/vsales/index']); ?>"><i class="fa fa-check"></i> Status Order</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>  
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>                          
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check text-aqua"></i> Principal Report<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?= Url::to(['/uci/index']); ?>"><i class="fa fa-check"></i> Unicharm</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>  
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>                          
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> Stocks<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>  
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>                          
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-check"></i> FI & Accounting<i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-check"></i> GL</a></li>
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>  
                            <li><a href="#"><i class="fa fa-check"></i> ...</a></li>                          
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <?php
//        $menuCallback = function($menu) {
//            $item = [
//                'label' => $menu['name'],
//                'url' => MenuHelper::parseRoute($menu['route']),
//            ];
//            if (!empty($menu['data'])) {
//                $item['icon'] = 'fa ' . $menu['data'];
//            } else {
//                $item['icon'] = 'fa fa-angle-double-right';
//            }
//            if ($menu['children'] != []) {
//                $item['items'] = $menu['children'];
//            }
//            return $item;
//        };
//
//        $items = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $menuCallback);
//        echo SideMenu::widget([
//            'items' => $items,
//        ]);
        ?>
    </section>
    <!-- /.sidebar -->
</aside>
