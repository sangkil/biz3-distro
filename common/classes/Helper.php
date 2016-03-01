<?php

namespace common\classes;

use yii\base\Model;
use ReflectionClass;

/**
 * Description of Helper
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Helper
{
    /**
     * Populates a set of models with the data from end user.
     * This method is mainly used to collect tabular data input.
     * The data to be loaded for each model is `$data[formName][index]`, where `formName`
     * refers to the sort name of model class, and `index` the index of the model in the `$data` array.
     * If `$formName` is empty, `$data[index]` will be used to populate each model.
     * The data being populated to each model is subject to the safety check by [[setAttributes()]].
     * @param string $class Model class name.
     * @param array $data the data array. This is usually `$_POST` or `$_GET`, but can also be any valid array
     * supplied by end user.
     * @param string $formName the form name to be used for loading the data into the models.
     * If not set, it will use the sort name of called class.
     * @param Model[] $origin original models to be populated. It will be check using `$keys` with supplied data.
     * If same then will be used for result model.
     * @param string|array $keys list of attribute to check is two model are equals. If `$keys` is `null`
     * then it will use array index to check. If `$keys` is empty then always create new model.
     * @param string $scenario scenario for model.
     * @return boolean|Model[] whether at least one of the models is successfully populated.
     */
    public static function createMultiple($class, $data, $formName = null, &$origin = [], $keys = null, $scenario = null)
    {
        if ($formName === null) {
            $reflector = new ReflectionClass($class);
            $formName = $reflector->getShortName();
        }
        if ($formName != '') {
            $data = isset($data[$formName]) ? $data[$formName] : null;
        }
        if ($data === null) {
            return false;
        }

        $models = [];
        foreach ($data as $i => $row) {
            /* @var $model Model */
            $model = null;
            if ($keys === null) {
                if (isset($origin[$i])) {
                    $model = $origin[$i];
                    unset($origin[$i]);
                }
            } elseif (empty($keys)) {

            } elseif (is_string($keys)) {
                foreach ($origin as $j => $oldModel) {
                    if ($row[$keys] == $oldModel[$keys]) {
                        $model = $oldModel;
                        unset($origin[$j]);
                        break;
                    }
                }
            } else {
                $rowKeys = [];
                foreach ($keys as $key) {
                    $rowKeys[] = $row[$key];
                }
                foreach ($origin as $j => $oldModel) {
                    $oldKeys = [];
                    foreach ($keys as $key) {
                        $oldKeys[] = $oldModel[$key];
                    }
                    if ($rowKeys == $oldKeys) {
                        $model = $oldModel;
                        unset($origin[$j]);
                        break;
                    }
                }
            }

            $model = $model ? : new $class();
            if ($scenario !== null) {
                $model->scenario = $scenario;
            }
            $model->load($row, '');
            $models[$i] = $model;
        }
        return $models;
    }
}
