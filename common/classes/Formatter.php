<?php

namespace common\classes;

use yii\i18n\Formatter as BaseFormatter;
use yii\helpers\Html;

/**
 * Description of Formatter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Formatter extends BaseFormatter
{
    public static $viewMap = [
        'SA' => ['backend\models\sales\Sales', '/sales/sales/view'],
        'PU' => ['backend\models\purchase\Purchase', '/purchase/purchase/view'],
        'GM' => ['backend\models\inventory\GoodsMovement', '/inventory/gm-manual/view'],
        'IT' => ['backend\models\inventory\Transfer', '/inventory/transfer/view'],
        'IV' => ['backend\models\accounting\Invoice', '/accounting/invoice/view'],
        'PY' => ['backend\models\accounting\Payment', '/accounting/payment/view'],
        'GL' => ['backend\models\accounting\GlHeader', '/accounting/general-ledger/view'],
    ];

    public function asReference($value)
    {
        return preg_replace_callback('/\[(([A-Z]+)[\d\-\.]+)\]/', function($matches) {
            if (isset($matches[2]) && isset(Formatter::$viewMap[$matches[2]])) {
                list($class, $route) = Formatter::$viewMap[$matches[2]];
                if (($model = $class::findOne(['number' => $matches[1]])) !== null) {
                    return Html::a($matches[1], [$route, 'id' => $model->id]);
                }
            }
            return $matches[0];
        }, $value);
    }
}
