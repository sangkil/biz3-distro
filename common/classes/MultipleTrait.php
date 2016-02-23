<?php

namespace common\classes;

use yii\helpers\ArrayHelper;

/**
 * Description of MultipleTrait
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
trait MultipleTrait
{

    /**
     *
     * @param array $data
     * @param string $formName
     * @param array $origin
     * @param string|array $keys
     * @return static[]
     */
    public static function createMultiple($data, $formName = null, &$origin = [], $keys = null)
    {
        if ($formName === null) {
            $ref = new \ReflectionClass(get_called_class());
            $formName = $ref->getShortName();
        }
        if ($formName != '') {
            $data = ArrayHelper::getValue($data, $formName, null);
        }
        if($data === null){
            return false;
        }
        $result = [];
        $newOrigin = [];
        if ($keys !== null && !empty($origin)) {
            foreach ($origin as $index => $model) {
                if (is_array($keys)) {
                    $id = [];
                    foreach ($keys as $key) {
                        $id[] = $model[$key];
                    }
                    $id = md5(serialize($id));
                } else {
                    $id = $model[$keys];
                }
                $newOrigin[$id] = [$index, $model];
            }
        }
        /* @var $model \yii\base\Model */
        foreach ($data as $index => $row) {
            $model = [];
            if ($keys === null) {
                if (isset($origin[$index])) {
                    $model = $origin[$index];
                    unset($origin[$index]);
                }
            } elseif (is_array($keys)) {
                $id = [];
                foreach ($keys as $key) {
                    $id[] = $row[$key];
                }
                $id = md5(serialize($id));
                if (isset($newOrigin[$id])) {
                    list($idx, $model) = $newOrigin[$id];
                    unset($origin[$idx], $newOrigin[$id]);
                }
            } else {
                $id = $row[$keys];
                if (isset($newOrigin[$id])) {
                    list($idx, $model) = $newOrigin[$id];
                    unset($origin[$idx], $newOrigin[$id]);
                }
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
