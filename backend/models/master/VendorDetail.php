<?php

namespace backend\models\master;

use Yii;

/**
 * This is the model class for table "vendor_detail".
 *
 * @property integer $id
 * @property integer $distric_id
 * @property string $addr1
 * @property string $addr2
 * @property double $latitude
 * @property double $longtitude
 * @property integer $kab_id
 * @property integer $kec_id
 * @property integer $kel_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Vendor $id0
 */
class VendorDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'distric_id', 'kab_id', 'kec_id', 'kel_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['latitude', 'longtitude'], 'number'],
            [['addr1', 'addr2'], 'string', 'max' => 128],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'distric_id' => 'Distric ID',
            'addr1' => 'Addr1',
            'addr2' => 'Addr2',
            'latitude' => 'Latitude',
            'longtitude' => 'Longtitude',
            'kab_id' => 'Kab ID',
            'kec_id' => 'Kec ID',
            'kel_id' => 'Kel ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'id']);
    }
}
