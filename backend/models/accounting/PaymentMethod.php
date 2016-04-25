<?php

namespace backend\models\accounting;

use Yii;

/**
 * This is the model class for table "{{%payment_method}}".
 *
 * @property integer $id
 * @property integer $branch_id
 * @property string $method
 * @property integer $coa_id
 * @property integer $potongan
 * @property integer $coa_id_potongan
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    public $coa_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_method}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id', 'method', 'coa_id'], 'required'],
            [['branch_id', 'coa_id', 'coa_id_potongan'], 'integer'],
            [['method'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Branch ID',
            'method' => 'Method',
            'coa_id' => 'Coa ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(\backend\models\master\Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoa()
    {
        return $this->hasOne(\backend\models\accounting\Coa::className(), ['id' => 'coa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoaPotongan()
    {
        return $this->hasOne(\backend\models\accounting\Coa::className(), ['id' => 'coa_id_potongan']);
    }

    public static function selectOptions($branch_id = null)
    {
        $query = self::find();
        if ($branch_id !== null) {
            $query->andWhere(['branch_id' => $branch_id]);
        }
        return \yii\helpers\ArrayHelper::map($query->asArray()->all(), 'id', 'method');
    }

    public function behaviors()
    {
        return[
            'yii\behaviors\BlameableBehavior',
            'yii\behaviors\TimestampBehavior',
        ];
    }
}
