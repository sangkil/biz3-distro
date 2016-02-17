<?php

namespace common\classes;

/**
 * Description of MultipleTrait
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
trait MultipleTrait
{

    public static function createMultiple($data, $formName = null, $origin = [], $keys = null)
    {
        if ($formName === null) {
            $ref = new \ReflectionClass(get_called_class());
            $formName = $ref->getShortName();
        }
        if ($formName != '') {
            $data = $data[$formName];
        }
        $result = [];
        if ($keys !== null && !empty($origin)) {
            $newOrigin = [];
            if (is_array($keys)) {
                foreach ($origin as $model) {
                    $id = [];
                    foreach ($keys as $key) {
                        $id[] = $model[$key];
                    }
                    $newOrigin[md5(serialize($id))] = $model;
                }
            } else {
                foreach ($origin as $model) {
                    $newOrigin[$model[$keys]] = $model;
                }
            }
            $origin = $newOrigin;
        }
        /* @var $model \yii\base\Model */
        foreach ($data as $index => $row) {
            if ($keys === null) {
                $model = isset($origin[$index]) ? $origin[$index] : new static();
            } elseif (is_array($keys)) {
                $id = [];
                foreach ($keys as $key) {
                    $id[] = $row[$key];
                }
                $id = md5(serialize($id));
                $model = isset($origin[$id]) ? $origin[$id] : new static();
            } else {
                $model = isset($origin[$row[$keys]]) ? $origin[$row[$keys]] : new static();
            }
            if (!($model instanceof static)) {
                $m = new static();
                foreach ($model as $attr => $value) {
                    $m->$attr = $value;
                }
                $model = $m;
            }
            $model->load($row, '');
            $result[$index] = $model;
        }
        return $result;
    }
}
