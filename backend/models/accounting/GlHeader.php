<?php

namespace backend\models\accounting;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "gl_header".
 *
 * @property integer $id
 * @property string $number
 * @property string $date
 * @property integer $periode_id
 * @property integer $branch_id
 * @property integer $reff_type
 * @property integer $reff_id
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property GlDetail[] $glDetails
 * @property AccPeriode $periode
 */
class GlHeader extends \yii\db\ActiveRecord {

    use \mdm\converter\EnumTrait,
        \mdm\behaviors\ar\RelationTrait;

    const STATUS_DRAFT = 10;
    const STATUS_RELEASED = 20;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'gl_header';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['date', 'periode_id', 'branch_id', 'reff_type', 'description', 'status'], 'required'],
            [['number'], 'autonumber', 'format' => 'GL' . date('Ym') . '.?', 'digit' => 4],
            [['date','GlDate'], 'safe'],
            [['periode_id', 'branch_id', 'reff_type', 'reff_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number'], 'string', 'max' => 16],
            [['description'], 'string', 'max' => 255],
            [['periode_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccPeriode::className(), 'targetAttribute' => ['periode_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'date' => 'Date',
            'periode_id' => 'Periode ID',
            'branch_id' => 'Branch ID',
            'reff_type' => 'Reff Type',
            'reff_id' => 'Reff ID',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlDetails() {
        return $this->hasMany(GlDetail::className(), ['header_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function setGlDetails($value) {
        $this->loadRelated('glDetails', $value);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriode() {
        return $this->hasOne(AccPeriode::className(), ['id' => 'periode_id']);
    }
    
        /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch() {
        return $this->hasOne(\backend\models\master\Branch::className(), ['id' => 'branch_id']);
    }

    public function getNmStatus() {
        return $this->getLogical('status', 'STATUS_');
    }

    public function behaviors() {
        return [
            [
                'class' => 'mdm\converter\DateConverter',
                'type' => 'date', // 'date', 'time', 'datetime'
                'logicalFormat' => 'php:d-m-Y',
                'attributes' => [
                    'GlDate' => 'date',
                ]
            ],
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }

}
